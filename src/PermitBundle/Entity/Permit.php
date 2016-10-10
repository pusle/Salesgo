<?php

namespace PermitBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use AppBundle\Entity\Organization;

/**
 * @ORM\Entity
 * @ORM\Table(name="permits")
 */
class Permit
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
    private $permitNumber;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $calledBack;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $centralName;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Organization", inversedBy="permits")
     * @ORM\JoinColumn(name="organization_id", referencedColumnName="id")
     */
    private $organization;

    /**
     * @ORM\ManyToOne(targetEntity="PermitBundle\Entity\PermitCounty")
     * @ORM\JoinColumn(name="permit_county_id", referencedColumnName="id")
     */
    private $permitCounty;

    /**
     * @ORM\ManyToOne(targetEntity="PermitBundle\Entity\PermitType")
     * @ORM\JoinColumn(name="permit_type_id", referencedColumnName="id")
     */
    private $permitType;



    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getPermitType() {
        return $this->permitType;
    }

    /**
     * @param mixed $permitType
     *
     * @return Permit
     */
    public function setPermitType($permitType) {
        $this->permitType = $permitType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermitNumber() {
        return $this->permitNumber;
    }

    /**
     * @param mixed $permitNumber
     *
     * @return Permit
     */
    public function setPermitNumber($permitNumber) {
        $this->permitNumber = $permitNumber;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermitCounty() {
        return $this->permitCounty;
    }

    /**
     * @param mixed $permitCounty
     *
     * @return Permit
     */
    public function setPermitCounty($permitCounty) {
        $this->permitCounty = $permitCounty;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCalledBack() {
        return $this->calledBack;
    }

    /**
     * @param mixed $calledBack
     *
     * @return Permit
     */
    public function setCalledBack($calledBack) {
        $this->calledBack = $calledBack;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCentralName() {
        return $this->centralName;
    }

    /**
     * @param mixed $centralName
     *
     * @return Permit
     */
    public function setCentralName($centralName) {
        $this->centralName = $centralName;
        return $this;
    }

    /**
     * @return Organization
     */
    public function getOrganization() {
        return $this->organization;
    }

    /**
     * @param Organization $organization
     *
     * @return Permit
     */
    public function setOrganization(Organization $organization) {
        $organization->addPermit($this);
        $this->organization = $organization;
        return $this;
    }
}