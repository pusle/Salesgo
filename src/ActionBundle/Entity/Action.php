<?php

namespace ActionBundle\Entity;

use AppBundle\Entity\Contact;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="ActionBundle\Repository\ActionRepository")
 * @ORM\Table(name="actions")
 */
class Action
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $startedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $done;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $doneAt;

    /**
     * 
     * @ORM\JoinColumn(name="action_type_id", referencedColumnName="id")
     * @ORM\ManyToOne(targetEntity="ActionBundle\Entity\ActionType", inversedBy="action")
     */
    private $actionType;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="actions")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;



    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNotes() {
        return $this->notes;
    }

    /**
     * @param mixed $notes
     *
     * @return Action
     */
    public function setNotes($notes) {
        $this->notes = $notes;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStartedAt() {
        return $this->startedAt;
    }

    /**
     * @param mixed $startedAt
     *
     * @return Action
     */
    public function setStartedAt($startedAt) {
        $this->startedAt = $startedAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDone() {
        return $this->done;
    }

    /**
     * @param mixed $done
     *
     * @return Action
     */
    public function setDone($done) {
        $this->done = $done;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDoneAt() {
        return $this->doneAt;
    }

    /**
     * @param mixed $doneAt
     *
     * @return Action
     */
    public function setDoneAt($doneAt) {
        $this->doneAt = $doneAt;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getActionType() {
        return $this->actionType;
    }

    /**
     * @param mixed $actionType
     *
     * @return Action
     */
    public function setActionType($actionType) {
        $this->actionType = $actionType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getContact() {
        return $this->contact;
    }

    /**
     * @param mixed $contact
     *
     * @return Action
     */
    public function setContact(Contact $contact) {
        $this->contact = $contact;
        return $this;
    }
}
