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
    //echo $app['security.encoder.digest']->encodePassword('password', 'cocacola');

    // $json = ([
    //    'status' => true,
    //    'info'   => [
    //        'name'    => $app['user']->getUsername(),
    //        'role' => $app['user']->getRoles()
    //      ]
    // ]);
    // return json_encode($json);
    
    return $app['twig']->render('login.html.twig', array(
      'error'         => $app['security.last_error']($request),
      'last_username' => $app['session']->get('_security.last_username'),
    ));

    //return $app['session.storage_handler'];
  }

}
