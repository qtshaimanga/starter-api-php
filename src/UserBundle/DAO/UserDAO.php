<?php

namespace Api\UserBundle\DAO;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Api\UserBundle\Entity\User;


class UserDAO extends DAO implements UserProviderInterface
{

  /*
  * FIN ALL USERS
  */
  public function findAll()
  {
    $sql = "SELECT rowid, * FROM USER";
    $result = $this->getDb()->fetchAll($sql);
    return $result;
  }

  /* TODO
  * User BY id
  */
  public function findAllById($id)
  {
    // $sql = "SELECT rowid, * FROM USER WHERE rowid=?";
    // $result = $this->getDb()->fetchAll($sql);
    // return $result;
  }


  /*
  * ADD USER
  */
  public function addUser($user)
  {
    $sql = "INSERT INTO USER (username, firstname, email, business, password, role, salt) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $resutl = $this->getDb()->fetchAssoc( $sql, array( $user['username'], $user['firstname'], $user['email'], $user['business'], $user['_password'], $user['role'], $user['salt'] ) );
    return $resutl;
  }

  /* TODO
  * ADD USER
  */
  public function deleteUser($user)
  {
    $sql = "DELETE FROM USER WHERE rowid=?";
    $resutl = $this->getDb()->fetchAssoc($user['id']);
    return $resutl;
  }


  /**
  * {@inheritDoc}
  */
  public function loadUserByUsername($username)
  {
    $sql = "SELECT rowid, * FROM USER WHERE email=?";
    $row = $this->getDb()->fetchAssoc($sql, array($username));

    if ($row){
      return $this->buildDomainObject($row);
    }else{
      throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
    }
  }

  /**
  * {@inheritDoc}
  */
  public function refreshUser(UserInterface $user)
  {
    $class = get_class($user);
    if (!$this->supportsClass($class)) {
      throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $class));
    }
    return $this->loadUserByUsername($user->getUsername());
  }

  /**
   * {@inheritDoc}
   */
  public function supportsClass($class)
  {
      return 'Api\UserBundle\Entity\User' === $class;
  }

  /**
   * Creates a User object based on a DB row.
   *
   * @param array $row The DB row containing User data.
   * @return \Api\UserBundle\Entity\User
   */
   protected function buildDomainObject($row)
   {
      $user = new User();
      $user->setId($row['rowid']);
      $user->setUsername($row['username']);
      $user->setFirstname($row['firstname']);
      $user->setEmail($row['email']);
      $user->setBusiness($row['business']);
      $user->setPassword($row['password']);
      $user->setSalt($row['salt']);
      $user->setRole($row['role']);
      return $user;
    }

}
