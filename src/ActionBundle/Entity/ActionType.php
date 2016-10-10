<?php

namespace ActionBundle\Entity;

use Doctrine\ORM\Mapping AS ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="action_types")
 */
class ActionType
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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="ActionBundle\Entity\Action", mappedBy="actionType")
     */
    private $action;

    /**
     * 
     */
    private $actions;


    /**
     * ActionType constructor.
     */
    public function __construct() {
        $this->actions = new ArrayCollection();
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
     * @return ActionType
     */
    public function setName($name) {
        $this->name = $name;
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
     * @return ActionType
     */
    public function setDescription($description) {
        $this->description = $description;
        return $this;
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
     * @return ActionType
     */
    public function addAction(Action $action) {
        $this->actions->add($action);
        return $this;
    }

    public function removeAction(Action $action) {
        $this->actions->remove($action);
    }
}
