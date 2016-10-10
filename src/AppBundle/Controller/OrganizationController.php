<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Entity\Organization;
use AppBundle\Form\OrganizationType;
use AppBundle\Repository\OrganizationRepository;

/**
 * Organization controller.
 *
 * @Route("/organization")
 */
class OrganizationController extends Controller
{
    /**
     * Lists all Organization entities.
     *
     * @Route("/", name="organization_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        /**
         * Paginator
         */
        $page = $request->get('page') ? $request->get('page') : 1;
        $offset = $request->get('offset') ? $request->get('offset') : 30;

        $limit = $offset;
        $from = ($page * $offset) - $offset;

        $total = $em->getRepository('AppBundle:Organization')->findNumberOfOrganizations();

        $searchString = $request->get('search') ? $request->get('search') : false;

        /**
         * Get organizations
         */
        $orderBy = $request->get('order');
        $orderDirection = $request->get('order_direction');
        $organizations = $em->getRepository('AppBundle:Organization')->findAllOrderedByPermits($from, $limit, $orderBy, $orderDirection, $searchString);

        if (sizeof($organizations) == 1) {
            $organization = $organizations[0]['Organization'];
            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }

        return $this->render('organization/index.html.twig', array(
            'organizations' => $organizations,
            'page' => $page,
            'offset' => $limit,
            'total' => $total
        ));
    }

    /**
     * Creates a new Organization entity.
     *
     * @Route("/new", name="organization_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $organization = new Organization();
        $form = $this->createForm('AppBundle\Form\OrganizationType', $organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);
            $em->flush();

            return $this->redirectToRoute('organization_show', array('id' => $organization->getId()));
        }

        return $this->render('organization/new.html.twig', array(
            'organization' => $organization,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Organization entity.
     *
     * @Route("/{id}", name="organization_show")
     * @Method("GET")
     */
    public function showAction(Organization $organization)
    {
        $deleteForm = $this->createDeleteForm($organization);

        $contact = new Contact();
        $contact->setOrganization($organization);
        $contactForm = $this->createForm('AppBundle\Form\ContactType', $contact, array(
            'action' => $this->generateUrl('contact_new', array('org_id' => $organization->getId()))
        ));

        return $this->render('organization/show.html.twig', array(
            'organization' => $organization,
            'delete_form' => $deleteForm->createView(),
            'contact_form' => $contactForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Organization entity.
     *
     * @Route("/{id}/edit", name="organization_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Organization $organization)
    {
        $deleteForm = $this->createDeleteForm($organization);
        $editForm = $this->createForm('AppBundle\Form\OrganizationType', $organization);
        $editForm->handleRequest($request);

        $contact = new Contact();
        $contact->setOrganization($organization);
        $contactForm = $this->createForm('AppBundle\Form\ContactType', $contact, array(
            'action' => $this->generateUrl('contact_new', array('org_id' => $organization->getId()))
        ));

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($organization);
            $em->flush();

            return $this->redirectToRoute('organization_edit', array('id' => $organization->getId()));
        }

        return $this->render('organization/edit.html.twig', array(
            'organization' => $organization,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'contact_form' => $contactForm->createView(),
        ));
    }

    /**
     * Deletes a Organization entity.
     *
     * @Route("/{id}", name="organization_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Organization $organization)
    {
        $form = $this->createDeleteForm($organization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($organization);
            $em->flush();
        }

        return $this->redirectToRoute('organization_index');
    }

    /**
     * Creates a form to delete a Organization entity.
     *
     * @param Organization $organization The Organization entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Organization $organization)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('organization_delete', array('id' => $organization->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
