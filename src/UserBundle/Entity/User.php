<?php

namespace Api\UserBundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{

  /**
  * User $id.
  * @var integer
  */
  private $id;

  /**
  * User $username.
  * @var varchar
  */
  private $username;

  /**
  * User $firstname.
  * @var varchar
  */
  private $firstname;

  /**
  * User $email.
  * @var varchar
  */
  private $email;

  /**
  * User $business.
  * @var varchar
  */
  private $business;

  /**
  * User $password.
  * @var varchar
  */
  private $password;

  /**
  * User $role.
  * @var varchar
  */
  private $role;

  /**
  * User $salt.
  * @var varchar
  */
  private $salt;


  public function getId() {
    return $this->id;
  }

  public function setId($id) {
    $this->id = $id;
  }

  /**
  * @inheritDoc
  */
  public function getUsername() {
    return $this->username;
  }

  public function setUsername($username) {
    $this->username = $username;
  }

  /**
  * @inheritDoc
  */
  public function getFirstname() {
    return $this->firstname;
  }

  public function setFirstname($firstname) {
    $this->firstname = $firstname;
  }

  /**
  * @inheritDoc
  */
  public function getEmail() {
    return $this->email;
  }

  public function setEmail($email) {
    $this->email = $email;
  }

  /**
  * @inheritDoc
  */
  public function getBusiness() {
    return $this->business;
  }

  public function setBusiness($business) {
    $this->business = $business;
  }

  /**
  * @inheritDoc
  */
  public function getPassword() {
    return $this->password;
  }

  public function setPassword($password) {
    $this->password = $password;
  }

  /**
  * @inheritDoc
  */
  public function getRole() {
    return $this->role;
  }

  public function setRole($role) {
    $this->role = $role;
  }

  /**
  * @inheritDoc
  */
  public function getSalt() {
    return $this->salt;
  }

  public function setSalt($salt) {
    $this->salt = $salt;
  }

  /**
   * @inheritDoc
   */
  public function getRoles()
  {
      return array($this->getRole());
  }

  /**
  * @inheritDoc
  */
  public function eraseCredentials() {
    // Nothing to do here
  }

}
