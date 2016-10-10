<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;
use PermitBundle\Entity\Permit;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OrganizationRepository")
 * @ORM\Table(name="organizations")
 */
class Organization
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $registrationDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numEmployees;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $bankrupt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $liquidation;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Address", mappedBy="organization", cascade={"persist"})
     */
    private $addresses;

    /**
     * @ORM\OneToMany(targetEntity="PermitBundle\Entity\Permit", mappedBy="organization", cascade={"all"})
     */
    private $permits;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Contact", mappedBy="organization", cascade={"all"})
     */
    private $contacts;

    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\IndustryCode", inversedBy="organization", cascade={"persist"})
     * @ORM\JoinTable(
     *     name="IndustryCodeToOrganization",
     *     joinColumns={@ORM\JoinColumn(name="organization_entity_id", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="industry_code_entity_id", referencedColumnName="id", nullable=false)}
     * )
     */
    private $industryCodes;



    /**
     * Organization constructor.
     */
    public function __construct() {
        $this->addresses = new ArrayCollection();
        $this->permits = new ArrayCollection();
        $this->contacts = new ArrayCollection();
        $this->industryCodes = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNumber() {
        return $this->number;
    }

    /**
     * @param mixed $number
     *
     * @return Organization
     */
    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Organization
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRegistrationDate() {
        return $this->registrationDate;
    }

    /**
     * @param mixed $registrationDate
     *
     * @return Organization
     */
    public function setRegistrationDate($registrationDate) {
        $this->registrationDate = $registrationDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @param mixed $type
     *
     * @return Organization
     */
    public function setType($type) {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNumEmployees() {
        return $this->numEmployees;
    }

    /**
     * @param mixed $numEmployees
     *
     * @return Organization
     */
    public function setNumEmployees($numEmployees) {
        $this->numEmployees = $numEmployees;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getBankrupt() {
        return $this->bankrupt;
    }

    /**
     * @param mixed $bankrupt
     *
     * @return Organization
     */
    public function setBankrupt($bankrupt) {
        $this->bankrupt = $bankrupt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLiquidation() {
        return $this->liquidation;
    }

    /**
     * @param mixed $liquidation
     *
     * @return Organization
     */
    public function setLiquidation($liquidation) {
        $this->liquidation = $liquidation;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAddresses() {
        return $this->addresses;
    }

    /**
     * @param mixed $address
     *
     * @return Organization
     */
    public function addAddress(Address $address) {
        $address->setOrganization($this);
        $this->addresses->add($address);
        return $this;
    }

    /**
     * @param $address
     */
    public function removeAddress(Address $address) {
        $this->addresses->removeElement($address);
    }

    /**
     * @return mixed
     */
    public function getPermits() {
        return $this->permits;
    }

    /**
     * @param mixed $permit
     *
     * @return Organization
     */
    public function addPermit(Permit $permit) {
        $this->permits->add($permit);
        return $this;
    }

    /**
     * @param $permit
     */
    public function removePermit(Permit $permit) {
        $this->permits->removeElement($permit);
    }

    /**
     * @return mixed
     */
    public function getContacts() {
        return $this->contacts;
    }

    /**
     * @param mixed $contact
     *
     * @return Organization
     */
    public function addContact(Contact $contact) {
        $contact->setOrganization($this);
        $this->contacts->add($contact);
        return $this;
    }

    /**
     * @param \AppBundle\Entity\Contact $contact
     */
    public function removeContact(Contact $contact) {
        $this->contacts->removeElement($contact);
    }

    /**
     * @return mixed
     */
    public function getIndustryCodes() {
        return $this->industryCodes;
    }

    /**
     * @param mixed $industryCode
     *
     * @return Organization
     */
    public function addIndustryCode(IndustryCode $industryCode) {
        $this->industryCodes->add($industryCode);
        return $this;
    }

    /**
     * @param $industryCode
     */
    public function removeIndustryCode(IndustryCode $industryCode) {
        $this->industryCodes->removeElement($industryCode);
    }
}
