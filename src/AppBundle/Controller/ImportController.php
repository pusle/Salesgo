<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Address;
use AppBundle\Entity\IndustryCode;
use AppBundle\Entity\Organization;
use AppBundle\Entity\PermitCounty;
use AppBundle\Entity\Permit;
use AppBundle\Entity\PermitType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * Class ImportController
 * @package AppBundle\Controller
 * @Route("/import")
 */
class ImportController extends Controller
{
    /**
     * @Route("/", name="import_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        return $this->render('import/index.html.twig', array(

        ));
    }

    /**
     * @Route("/csv", name="import_csv")
     * @Method({"GET", "POST"})
     */
    public function importCSVAction(Request $request)
    {
        ini_set('max_execution_time', 1000);


        $em = $this->getDoctrine()->getManager();
        $data = array();

        // Create file upload form
        $form = $this->createFormBuilder($data)
            ->setAction($this->generateUrl('import_csv'))
            ->add('csvfile', FileType::class)
            ->add('submit', SubmitType::class)
            ->getForm();

        // Handle form submit
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $uploadedFile = $form->get('csvfile')->getData();

            $file = file($uploadedFile->getPathname());

            foreach ($file as $line) {
                $lineArray = str_getcsv($line, ";");

                $orgArray = $this->createOrgArray($lineArray);

                // Check if organization exists in database
                $organization = $em
                    ->getRepository('AppBundle:Organization')
                    ->findOneBy(array('number' => $orgArray['number']));

                if ( ! ($organization instanceof Organization)) {
                    // Add organization
                    $organization = $this->createOrganization($orgArray);
                }

                $permit = $em
                    ->getRepository('AppBundle:Permit')
                    ->findOneBy(array(
                        'permitNumber' => $orgArray['permit']['number'],
                        'organization' => $organization));

                if ( ! ($permit instanceof Permit)) {
                    $permit = $this->createPermit($orgArray, $organization);
                    $organization->addPermit($permit);
                }
            }

            $em->flush();

            // Form submitted, redirect to..
            return $this->redirectToRoute('organization_index');
        }

        // Form not submitted, render output
        return $this->render('import/import_csv.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * Import data from BRREG
     *
     * @Route("/brreg/{orgNumber}", name="import_brreg")
     */
    public function importBrregDataAction($orgNumber)
    {
        $em = $this->getDoctrine()->getManager();

        $orgExist = $em
            ->getRepository('AppBundle:Organization')
            ->findOneBy(array('number' => $orgNumber));

        if ( ! ($orgExist instanceof Organization)) {
            $this->addFlash('error', 'Organization not found in Salesgo database!');
            return $this->redirectToRoute('import_brreg_failed');
        }

        $url = 'http://data.brreg.no/enhetsregisteret/enhet/' . $orgNumber . '.json';

        try {
            $json = file_get_contents($url);
        } catch(\Exception $e) {
            $this->addFlash('error', 'Organization data was not imported from BRREG!');
            return $this->redirectToRoute('organization_show', array('id' => $orgExist->getId()));
        }

        $data = json_decode($json, true);

        $registrationDate = \DateTime::createFromFormat("Y-m-d", $data['registreringsdatoEnhetsregisteret']);
        $organizationType = $data['organisasjonsform'];
        $numEmployees     = $data['antallAnsatte'];
        $industrialCode1  = (isset($data['naeringskode1'])) ? $data['naeringskode1'] : false;
        $industrialCode2  = (isset($data['naeringskode2'])) ? $data['naeringskode2'] : false;
        $industrialCode3  = (isset($data['naeringskode3'])) ? $data['naeringskode3'] : false;
        $postalAddress    = (isset($data['postadresse'])) ? $data['postadresse'] : false;
        $visitAddress     = (isset($data['forretningsadresse'])) ? $data['forretningsadresse'] : false;
        $bankrupt         = $data['konkurs'];
        $liquidation      = $data['underAvvikling'];

        // Add registration date
        $orgExist->setRegistrationDate($registrationDate);

        // Add organization type
        $orgExist->setType($organizationType);

        // Add number of employees
        $orgExist->setNumEmployees($numEmployees);

        // Add bankrupt
        $orgExist->setBankrupt($bankrupt);

        // Add liquidation
        $orgExist->setLiquidation($liquidation);

        // Add industrial codes
        if ($industrialCode1) $this->addIndustryCode($industrialCode1, $orgExist);
        if ($industrialCode2) $this->addIndustryCode($industrialCode2, $orgExist);
        if ($industrialCode3) $this->addIndustryCode($industrialCode3, $orgExist);

        // Add addresses
        if ($postalAddress) $this->addAddress($postalAddress, 'post', $orgExist);
        if ($visitAddress) $this->addAddress($visitAddress, 'visit', $orgExist);

        // Flush
        $em->flush();

        $this->addFlash('success', 'Organization data imported from BRREG!');
        return $this->redirectToRoute('organization_show', array('id' => $orgExist->getId()));
    }

    /**
     * @Route("/brreg/failed", name="import_brreg_failed")
     */
    public function importBrregDataFailedAction()
    {
        return $this->render('import/import_brreg_failed.html.twig');
    }

    /**
     * Create organization array
     *
     * @param $lineArray
     * @return array
     */
    private function createOrgArray($lineArray)
    {
        $permit = array();
        $permitNumber = preg_replace('/[^A-Za-z0-9\-]/', '', $lineArray[2]);
        $permitNumber = str_replace(' ', '', $permitNumber);
        $permit['number'] = (string)trim(substr($permitNumber, -4));
        $permit['county'] = (integer)trim(substr($permitNumber, 0, 2));
        $permit['type'] = (integer)trim(substr($permitNumber, 2, 2));
        $permit['central'] = $lineArray[3];
        $permit['calledBack'] = ($lineArray[4] != "") ? true : false;

        $organization = array();
        $organization['number'] = $lineArray[0];
        $organization['name'] = $lineArray[1];
        $organization['permit'] = array();

        $organization['permit'] = $permit;

        return $organization;
    }

    /**
     * Create Organization entity
     *
     * @param $orgArray
     * @return \AppBundle\Entity\Organization
     */
    private function createOrganization($orgArray)
    {
        $em = $this->getDoctrine()->getManager();

        $organization = new Organization();
        $organization->setNumber($orgArray['number']);
        $organization->setName($orgArray['name']);

        $em->persist($organization);
        $em->flush();

        return $organization;
    }

    /**
     * Create Permit entity
     *
     * @param $orgArray
     * @param $orgEntity
     * @return Permit $permit
     */
    private function createPermit($orgArray, $orgEntity) {
        $em = $this->getDoctrine()->getManager();

        // First get county
        $county = $em
            ->getRepository('AppBundle:PermitCounty')
            ->findOneBy(array('number' => $orgArray['permit']['county']));

        if ( ! $county instanceof PermitCounty) {
            echo "County with number " . $orgArray['permit']['county'] . " could not be found!";
            exit;
        }

        $type = $em
            ->getRepository('AppBundle:PermitType')
            ->findOneBy(array('number' => $orgArray['permit']['type']));

        if ( ! ($type instanceof PermitType)) {
            echo "Permit type with number " . $orgArray['permit']['type'] . " could not be found!";
            exit;
        }

        $permit = new Permit();
        $permit->setPermitNumber($orgArray['permit']['number']);
        $permit->setPermitCounty($county);
        $permit->setPermitType($type);
        $permit->setCentralName($orgArray['permit']['central']);
        $permit->setCalledBack($orgArray['permit']['calledBack']);
        $permit->setOrganization($orgEntity);

        $em->persist($permit);

        return $permit;
    }

    /**
     * @param $code
     * @param \AppBundle\Entity\Organization $organization
     */
    private function addIndustryCode($code, Organization $organization)
    {
        $em = $this->getDoctrine()->getManager();

        $industryCode = $em
            ->getRepository('AppBundle:IndustryCode')
            ->findOneBy(array('code' => $code['kode']));

        if ( ! ($industryCode instanceof IndustryCode)) {
            $industryCode = new IndustryCode();
            $industryCode->setCode($code['kode']);
            $industryCode->setDescription($code['beskrivelse']);
            $em->persist($industryCode);
        }

        if ( ! $organization->getIndustryCodes()->contains($industryCode)) {
            $organization->addIndustryCode($industryCode);
            $em->persist($organization);
        }
    }

    /**
     * @param $newaddress
     * @param $type
     * @param \AppBundle\Entity\Organization $organization
     */
    private function addAddress($newaddress, $type, Organization $organization)
    {
        $em = $this->getDoctrine()->getManager();

        // Check if address type exists first
        $existingAddress = $em
            ->getRepository('AppBundle:Address')
            ->findOneBy(array('organization' => $organization->getId(), 'type' => $type));

        if ($existingAddress instanceof Address) {
            $this->addFlash('error', 'Address type ' . $type . ' already exists. Importing this address is dropped.');
            return;
        }

        $address = new Address();
        $address->setType($type);

        if (isset($newaddress['adresse'])) $address->setAddress($newaddress['adresse']);
        if (isset($newaddress['postnummer'])) $address->setZip($newaddress['postnummer']);
        if (isset($newaddress['poststed'])) $address->setCity($newaddress['poststed']);
        if (isset($newaddress['landkode'])) $address->setCountryCode($newaddress['landkode']);
        if (isset($newaddress['land'])) $address->setCountry($newaddress['land']);

        $em->persist($address);
        $organization->addAddress($address);
        $em->persist($organization);
    }


    /**

    private function addOrganization($org)
    {
        $em = $this->getDoctrine()->getManager();

        $orgExist = $em
            ->getRepository('AppBundle:Organization')
            ->findOneBy(array('number' => $org['organisasjonsnummer']));

        if ($orgExist instanceof Organization) return;

        $organization = new Organization();
        $organization->setNumber($org['organisasjonsnummer']);
        $organization->setName($org['navn']);
        //if(isset($org['stiftelsesdato'])) $organization->setRegistrationDate($org['stiftelsesdato']);
        if(isset($org['organisasjonsform'])) $organization->setType($org['organisasjonsform']);
        if(isset($org['antallAnsatte'])) $organization->setNumEmployees($org['antallAnsatte']);
        if(isset($org['konkurs']) && $org['konkurs'] == 'Y') { $organization->setBankrupt(1); } else { $organization->setBankrupt(0); }
        if(isset($org['underAvvikling']) && $org['underAvvikling'] == 'Y') { $organization->setLiquidation(1); } else { $organization->setLiquidation(0); }

        if (isset($org['naeringskode1'])) {
            $industryCode = $em
                ->getRepository('AppBundle:IndustryCode')
                ->findOneBy(array('code' => $org['naeringskode1']['kode']));

            if ( ! ($industryCode instanceof IndustryCode)) {
                $industryCode = new IndustryCode();
                $industryCode->setCode($org['naeringskode1']['kode']);
                $industryCode->setDescription($org['naeringskode1']['beskrivelse']);
                $em->persist($industryCode);
            }

            $organization->addIndustryCodes($industryCode);
        }

        if (isset($org['naeringskode2'])) {
            $industryCode = $em
                ->getRepository('AppBundle:IndustryCode')
                ->findOneBy(array('code' => $org['naeringskode2']['kode']));

            if ( ! ($industryCode instanceof IndustryCode)) {
                $industryCode = new IndustryCode();
                $industryCode->setCode($org['naeringskode2']['kode']);
                $industryCode->setDescription($org['naeringskode2']['beskrivelse']);
                $em->persist($industryCode);
            }

            $organization->addIndustryCodes($industryCode);
        }

        if (isset($org['naeringskode3'])) {
            $industryCode = $em
                ->getRepository('AppBundle:IndustryCode')
                ->findOneBy(array('code' => $org['naeringskode3']['kode']));

            if ( ! ($industryCode instanceof IndustryCode)) {
                $industryCode = new IndustryCode();
                $industryCode->setCode($org['naeringskode3']['kode']);
                $industryCode->setDescription($org['naeringskode3']['beskrivelse']);
                $em->persist($industryCode);
            }

            $organization->addIndustryCodes($industryCode);
        }

        if (isset($org['postadresse'])) {
            $postaddress = new Address();
            $postaddress->setType('post');
            if (isset($org['postadresse']['adresse'])) $postaddress->setAddress($org['postadresse']['adresse']);
            if (isset($org['postadresse']['postnummer'])) $postaddress->setZip($org['postadresse']['postnummer']);
            if (isset($org['postadresse']['poststed'])) $postaddress->setCity($org['postadresse']['poststed']);
            if (isset($org['postadresse']['landkode'])) $postaddress->setCountryCode($org['postadresse']['landkode']);
            if (isset($org['postadresse']['land'])) $postaddress->setCountryCode($org['postadresse']['land']);

            $em->persist($postaddress);
            $organization->addAddresses($postaddress);
        }

        if (isset($org['forretningsadresse'])) {
            $visitaddress = new Address();
            $visitaddress->setType('visit');
            if (isset($org['forretningsadresse']['adresse'])) $visitaddress->setAddress($org['forretningsadresse']['adresse']);
            if (isset($org['forretningsadresse']['postnummer'])) $visitaddress->setZip($org['forretningsadresse']['postnummer']);
            if (isset($org['forretningsadresse']['poststed'])) $visitaddress->setCity($org['forretningsadresse']['poststed']);
            if (isset($org['forretningsadresse']['landkode'])) $visitaddress->setCountryCode($org['forretningsadresse']['landkode']);
            if (isset($org['forretningsadresse']['land'])) $visitaddress->setCountryCode($org['forretningsadresse']['land']);

            $em->persist($visitaddress);
            $organization->addAddresses($visitaddress);
        }

        $em->persist($organization);
        $em->flush();
    } */
}
