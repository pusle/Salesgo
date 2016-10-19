<?php
namespace TodoBundle\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping AS ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="todo_tags")
 */
class TodoTag
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
     * @ORM\ManyToMany(targetEntity="TodoBundle\Entity\Todo", inversedBy="todoTags")
     * @ORM\JoinTable(
     *     name="TodoHasTodoTag",
     *     joinColumns={@ORM\JoinColumn(name="todo_tag_id", referencedColumnName="id", nullable=false)},
     *     inverseJoinColumns={@ORM\JoinColumn(name="todo_id", referencedColumnName="id", nullable=false)}
     * )
     */
    private $todos;


    /**
     * TodoTag constructor.
     */
    public function __construct() {
        $this->todos = new ArrayCollection();
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
     * @return TodoTag
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTodos() {
        return $this->todos;
    }

    /**
     * @param Todo $todo
     *
     * @return TodoTag
     */
    public function addTodo(Todo $todo) {
        $this->todos->add($todo);
        return $this;
    }

    /**
     * @param $todo
     *
     * @return TodoTag
     */
    public function removeTodo(Todo $todo) {
        $this->todos->removeElement($todo);
        return $this;
    }
}
