<?php

namespace Api\UserBundle\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Api\UserBundle\Entity\User;

class UserController {

  public function AllUser(Application $app) {
    $user = $app['dao.user']->findAll();
    return json_encode($user);
  }

  public function loginAction(Request $request, Application $app) {
    return $app['twig']->render('login.html.twig', array(
      'error'         => $app['security.last_error']($request),
      'last_username' => $app['session']->get('_security.last_username'),
    ));
  }

  public function register(Request $request, Application $app){
    //encode
    $user = $app['dao.user']->addUser($nom);
    return json_encode($user);
  }

}
