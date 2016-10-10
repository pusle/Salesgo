<?php
namespace LeadBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity(repositoryClass="LeadBundle\Repository\LeadStatusRepository")
 * @ORM\Table(name="leadStatuses")
 */
class LeadStatus
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $changedAt;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact", inversedBy="leadStatuses")
     * @ORM\JoinColumn(name="contact_id", referencedColumnName="id")
     */
    private $contact;

    /**
     * @ORM\ManyToOne(targetEntity="LeadBundle\Entity\LeadStatusType", inversedBy="leadStatus")
     * @ORM\JoinColumn(name="lead_status_type_id", referencedColumnName="id")
     */
    private $leadStatusType;



    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getChangedAt() {
        return $this->changedAt;
    }

    /**
     * @param mixed $changedAt
     *
     * @return LeadStatus
     */
    public function setChangedAt($changedAt) {
        $this->changedAt = $changedAt;
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
     * @return LeadStatus
     */
    public function setContact($contact) {
        $this->contact = $contact;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLeadStatusType() {
        return $this->leadStatusType;
    }

    /**
     * @param mixed $leadStatusType
     *
     * @return LeadStatus
     */
    public function setLeadStatusType($leadStatusType) {
        $this->leadStatusType = $leadStatusType;
        return $this;
    }
}
