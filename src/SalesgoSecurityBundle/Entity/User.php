<?php

namespace SalesgoSecurityBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping AS ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true, length=255, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $birthDate;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confirmationToken;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $passwordRequestedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updated;


    /**
     * User constructor.
     */
    public function __construct() {
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
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param mixed $email
     *
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;
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
     * @return User
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsername() {
        return $this->username;
    }

    /**
     * @param mixed $username
     *
     * @return User
     */
    public function setUsername($username) {
        $this->username = $username;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * @param mixed $password
     *
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword() {

    }

    /**
     * @param mixed $plainPassword
     *
     * @return User
     */
    public function setPlainPassword($plainPassword) {

    }

    public function getSalt() {
        // you *may* need a real salt depending on your encoder
        // see section on salt below
        return null;
    }

    /**
     * @return mixed
     */
    public function getBirthDate() {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     *
     * @return User
     */
    public function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRoles() {
        return array('ROLE_ADMIN');
    }

    /**
     * @param mixed $roles
     *
     * @return User
     */
    public function setRoles($roles) {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken() {
        return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     *
     * @return User
     */
    public function setConfirmationToken($confirmationToken) {
        $this->confirmationToken = $confirmationToken;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPasswordRequestedAt() {
        return $this->passwordRequestedAt;
    }

    /**
     * @param mixed $passwordRequestedAt
     *
     * @return User
     */
    public function setPasswordRequestedAt($passwordRequestedAt) {
        $this->passwordRequestedAt = $passwordRequestedAt;
        return $this;
    }

    public function serialize() {
        return serialize(array($this->id, $this->username, $this->password));
    }

    public function unserialize($serialized) {
        list ($this->id, $this->username, $this->password) = unserialize($serialized);
    }

    /**
     * @return mixed
     */
    public function getCreated() {
        return $this->created;
    }

    /**
     * @param mixed $created
     *
     * @return User
     */
    public function setCreated($created) {
        $this->created = $created;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdated() {
        return $this->updated;
    }

    /**
     * @param mixed $updated
     *
     * @return User
     */
    public function setUpdated($updated) {
        $this->updated = $updated;
        return $this;
    }

    /**
     * @param \Symfony\Component\Security\Core\User\UserInterface $user
     * @return bool
     */
    public function isEqualTo(UserInterface $user) {
        if (!$user instanceof User) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }

    /**
     *
     */
    public function eraseCredentials() {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimeStamps()
    {
        $this->setUpdated(new \DateTime('now'));

        if ($this->getCreated() === null) {
            $this->setCreated(new \DateTime('now'));
        }
    }
}
