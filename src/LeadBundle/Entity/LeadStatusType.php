<?php
namespace LeadBundle\Entity;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="leadstatustypes")
 */
class LeadStatusType
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="LeadBundle\Entity\LeadStatus", mappedBy="leadStatusType")
     */
    private $leadStatus;



    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * @param mixed $title
     *
     * @return LeadStatusType
     */
    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param mixed $description
     *
     * @return LeadStatusType
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLeadStatus() {
        return $this->leadStatus;
    }

    /**
     * @param mixed $leadStatus
     *
     * @return LeadStatusType
     */
    public function setLeadStatus($leadStatus) {
        $this->leadStatus = $leadStatus;
        return $this;
    }
}
