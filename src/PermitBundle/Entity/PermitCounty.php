<?php

namespace PermitBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="permitCountys")
 */
class PermitCounty
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
    private $county;



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
     * @return PermitCounty
     */
    public function setNumber($number) {
        $this->number = $number;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCounty() {
        return $this->county;
    }

    /**
     * @param mixed $county
     *
     * @return PermitCounty
     */
    public function setCounty($county) {
        $this->county = $county;
        return $this;
    }
}