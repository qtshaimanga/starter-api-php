<?php

namespace Api\UserBundle\Controller;

use Silex\Application;
use Silex\Provider\SerializerServiceProvider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;


class UserController
{

  /*
  * return all users
  */
  public function FindAllUsers (Application $app)
  {

    $token = $app['security.token_storage']->getToken();
    try {

      if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {

        $response = $app['dao.user']->findAll();

      }else{

        throw new AccessDeniedException('Access denied for this username...');

      }

    } catch (AccessDeniedException $e) {

      $response = [
          'acces' => 'denied',
          'error' => 'Invalid role...',
      ];

    }

    $format = "json";
    $users = $app['serializer']->serialize($response, $format);
    return new Response($users, 200);
  }


  public function UserByToken (Application $app)
  {

    $jwt = 'no';
    $token = $app['security.token_storage']->getToken();

    if ($token instanceof Silex\Component\Security\Http\Token\JWTToken) {
        $jwt = 'yes';
    }
    $granted = 'no';
    if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
        $granted = 'yes';
    }
    $granted_user = 'no';
    if($app['security.authorization_checker']->isGranted('ROLE_USER')) {
        $granted_user = 'yes';
    }
    $granted_super = 'no';
    if($app['security.authorization_checker']->isGranted('ROLE_SUPER_ADMIN')) {
        $granted_super = 'yes';
    }

    $user = $token->getUser();

    $format = "json";
    $result = $app['serializer']->serialize($user, $format);
    return new Response($result, 200);

  }


  public function Login (Request $request, Application $app)
  {

    parse_str($request->getContent(), $vars);

    try {
      if (empty($vars['_username']) || empty($vars['_password'])) {
          throw new UsernameNotFoundException(sprintf('Username "%s" is empty...'));
      }

      $user = $app['dao.user']->loadUserByUsername($vars['_username']);

      if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $vars['_password'], $user->getSalt() ) ) {
          throw new UsernameNotFoundException(sprintf('Username "%s" is not valid...'));
      } else {
          $response = [
              'success' => true,
              'token' => $app['security.jwt.encoder']->encode(['email' => $user->getEmail()]),
          ];
      }
    } catch (UsernameNotFoundException $e) {

      $response = [
          'success' => false,
          'error' => 'Invalid credentials...',
      ];
    }

    return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));

  }


  /*
  * register
  */
  public function Register (Request $request, Application $app)
  {
    parse_str($request->getContent(), $data);
    $salt = 'cocacola';
    $encoder = new MessageDigestPasswordEncoder();
    $data['salt'] = $salt;
    $data['_password'] = $encoder->encodePassword($data['password'], $salt);
    $result = $app['dao.user']->addUser($data);

    return new Response($result, 200);
  }

  /*
  * Logout
  */
  // public function Logout (Request $request, Application $app)
  // {
  //
  //   return false
  // }

  /*
  * Delete User
  */
  public function UserById (Application $app, $id)
  {
    $result = $app['dao.user']->findUserById($id);
    $format = "json";
    $user = $app['serializer']->serialize($result, $format);

    return new Response($user, 200);
  }

  /*
  * Find User By id
  */
  public function DeleteUser (Request $request, Application $app)
  {
    parse_str($request->getContent(), $data);
    $result = $app['dao.user']->deleteUser($data);

    return new Response($result, 200);
  }


  /* TODO
  * will use for admin path
  */
  public function loginAction(Request $request, Application $app)
  {
    return $app['twig']->render('login.html.twig', array());
  }

}
