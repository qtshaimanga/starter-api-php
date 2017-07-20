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
  public function findAll() {
    $sql = "SELECT rowid, * FROM USER";
    $result = $this->getDb()->fetchAll($sql);
    // $entities = array();
    // foreach ($result as $row) {
    //   $id = $row['rowid'];
    //   $entities[$id] = $this->buildDomainObject($row);
    // }
    return $result;
  }

  /*
  * ADD USER
  */
  public function addUser($nom, ?){
    // $sql = "INSERT ?";
    // $row = $this->getDb()->fetchAssoc($sql, array(?));
    return true
  }

  /**
  * {@inheritDoc}
  */
  public c loadUserByUsername($username) {
    $sql = "SELECT rowid, * FROM USER WHERE nom=?";
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
   protected function buildDomainObject($row) {
      $user = new User();
      $user->setId($row['rowid']);
      $user->setUsername($row['nom']);
      $user->setPrenom($row['prenom']);
      $user->setEntreprise($row['entreprise']);
      $user->setPassword($row['mdp']);
      $user->setSalt($row['salt']);
      $user->setRole($row['role']);
      return $user;
    }

}
