<?php

namespace AppBundle\Entity;

use ActionBundle\Entity\Action;
use Doctrine\Common\Collections\ArrayCollection;
use LeadBundle\Entity\LeadStatus;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ContactRepository")
 * @ORM\Table(name="contacts")
 */
class Contact
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ContactMethod", mappedBy="contact", cascade={"all"})
     * @Assert\Count(min = 1, minMessage = "Add at least one method of contact (phone or email)")
     */
    private $contactMethods;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Note", mappedBy="contact")
     */
    private $notes;

    /**
     * @ORM\OneToMany(targetEntity="ActionBundle\Entity\Action", mappedBy="contact")
     * @ORM\OrderBy({"startedAt":"DESC"})
     * 
     */
    private $actions;

    /**
     * @ORM\OneToMany(targetEntity="LeadBundle\Entity\LeadStatus", mappedBy="contact", cascade={"persist"})
     */
    private $leadStatuses;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organization", inversedBy="contacts")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    private $organization;

    /**
     * @ORM\ManyToOne(targetEntity="SalesgoSecurityBundle\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;



    public function __construct() {
        $this->contactMethods = new ArrayCollection();
        $this->actions = new ArrayCollection();
        $this->leadStatuses = new ArrayCollection();
        $this->notes = new ArrayCollection();
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
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     *
     * @return Contact
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContactMethods() {
        return $this->contactMethods;
    }

    /**
     * @param mixed $contactMethod
     *
     * @return Contact
     */
    public function addContactMethod(ContactMethod $contactMethod) {
        $contactMethod->setContact($this);
        $this->contactMethods->add($contactMethod);
        return $this;
    }

    /**
     * @param \AppBundle\Entity\ContactMethod $contactMethod
     */
    public function removeContactMethod(ContactMethod $contactMethod) {
        $this->contactMethods->removeElement($contactMethod);
    }

    /**
     * @return mixed
     */
    public function getLeadStatuses() {
        return $this->leadStatuses;
    }

    /**
     * @param mixed $leadStatus
     *
     * @return Contact
     */
    public function addLeadStatus(LeadStatus $leadStatus) {
        $this->leadStatuses->add($leadStatus);
        return $this;
    }

    public function removeLeadStatus(LeadStatus $leadStatus) {
        $this->leadStatuses->removeElement($leadStatus);
    }

    /**
     * @return mixed
     */
    public function getActions() {
        return $this->actions;
    }

    /**
     * @param mixed $action
     *
     * @return Contact
     */
    public function addAction(Action $action) {
        $action->setOrganization($this);
        $this->actions->add($action);
        return $this;
    }

    /**
     * @param Action $action
     */
    public function removeAction(Action $action) {
        $this->actions->removeElement($action);
    }

    /**
     * @return mixed
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @param mixed $note
     *
     * @return Contact
     */
    public function addNote($note) {
        $this->notes->add($note);
        return $this;
    }

    public function removeNote(Note $note) {
        $this->notes->removeElement($note);
    }

    /**
     * @return mixed
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * @param mixed $organization
     *
     * @return Contact
     */
    public function setOrganization(Organization $organization) {
        $this->organization = $organization;
        return $this;
    }
}
