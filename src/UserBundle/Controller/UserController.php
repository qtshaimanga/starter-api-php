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
  * GET all users
  */
  public function FindAllUsers (Application $app)
  {

    $token = $app['security.token_storage']->getToken();
    try {

      if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
        $app['dao.user']->findAll();
        $response = [
            'sucess' => true,
            'acces' => 'allowed',
            'action' => 'all users',
        ];
      }else{
        throw new AccessDeniedException('Access denied for this username...');
      }

    } catch (AccessDeniedException $e) {
      $response = [
          'sucess' => false,
          'acces' => 'denied',
          'error' => 'Invalid role...',
      ];
    }

    $format = "json";
    $users = $app['serializer']->serialize($response, $format);
    return new Response($users, 200);
  }

  /*
  * GET current user
  */
  public function UserByToken (Application $app)
  {

    $jwt = 'denied';
    $role = 'unknown';
    $token = $app['security.token_storage']->getToken();
    if ($token instanceof Silex\Component\Security\Http\Token\JWTToken) {
        $jwt = 'allowed';
    }
    if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
      $role = 'ROLE_ADMIN';
    }
    $response = [
        'sucess' => true,
        'acces' => $jwt,
        'role' => $role,
        'action' => 'current user',
        'user' => $token->getUser(),
        'token' => $token->getCredentials()
    ];

    $format = "json";
    $result = $app['serializer']->serialize($response, $format);
    return new Response($result, 200);

  }

  /*
  * POST Login
  */
  public function Login (Request $request, Application $app)
  {

    parse_str($request->getContent(), $vars);

    try {
      if (empty($vars['email']) || empty($vars['password'])) {
          throw new UsernameNotFoundException(sprintf('Email "%s" is empty...'));
      }

      $format = "json";
      $user = $app['dao.user']->loadUserByUsername($vars['email']);

      if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $vars['password'], $user->getSalt() ) ) {
          throw new UsernameNotFoundException(sprintf('Email "%s" is not valid...'));
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
  * POST register
  */
  public function Register (Request $request, Application $app)
  {
    parse_str($request->getContent(), $data);
    $encoder = new MessageDigestPasswordEncoder();
    $salt = 'cocacola';
    $data['_password'] = $encoder->encodePassword($data['password'], $salt);
    $data['salt'] = $salt;
    $data['role'] = 'ROLE_USER';
    if($data['password'] && $data['email'] && $data['username']){
      $app['dao.user']->addUser($data);
      $response = [
          'success' => true,
          'action' => 'User added...',
      ];
    }else{
      $response = [
          'success' => false,
          'error' => 'Invalid credentials...',
      ];
    }

    return new Response(json_encode($response), 200);
  }


  /*
  * POST Update User by id
  */
  public function UpdateUser (Request $request, Application $app)
  {

    try{

      $token = $app['security.token_storage']->getToken();
      $user = $token->getUser();
      parse_str($request->getContent(), $data);

      if($app['security.authorization_checker']->isGranted('ROLE_ADMIN') || $user->getId() == $data['id'] ) {
        $app['dao.user']->Update($data);
        $response = [
          'success' => true,
          'acces' => 'allowed',
          'action' => 'user updated'
        ];
      }else{
        throw new AccessDeniedException('Access denied for this username...');
      }

    } catch (AccessDeniedException $e) {

      $response = [
        'success' => false,
        'acces' => 'denied',
        'error' => 'Invalid role...',
      ];

    }

    $format = "json";
    $result = $app['serializer']->serialize($response, $format);
    return new Response($result, 200);

  }

  /*
  * POST Update User ROLE
  */
  public function UpdateUserRole (Request $request, Application $app)
  {
    try{

      $token = $app['security.token_storage']->getToken();
      $user = $token->getUser();
      parse_str($request->getContent(), $data);

      if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')) {
        $app['dao.user']->UpdateRole($data);
        $response = [
          'success' => true,
          'acces' => 'allowed',
          'action' => 'role updated'
        ];
      }else{
        throw new AccessDeniedException('Access denied for this username...');
      }

    }catch(AccessDeniedException $e){
      $response = [
        'success' => false,
        'acces' => 'denied',
        'error' => 'Invalid role...',
      ];
    }

    $format = "json";
    $result = $app['serializer']->serialize($response, $format);
    return new Response($result, 200);
  }

  /*
  * Find User By id
  */
  public function UserById (Application $app, $id)
  {
    $user = $app['dao.user']->findUserById($id);
    $response = [
        'sucess' => true,
        'action' => 'user by id',
        'user' => $user
    ];
    $format = "json";
    $result = $app['serializer']->serialize($response, $format);
    return new Response($result, 200);
  }

  /*
  * Delete User
  */
  public function DeleteUser (Request $request, Application $app)
  {
    try{

      $token = $app['security.token_storage']->getToken();
      $user = $token->getUser();
      parse_str($request->getContent(), $data);

      if($app['security.authorization_checker']->isGranted('ROLE_ADMIN') || $user->getId() == $data['id'] ) {
        $app['dao.user']->deleteUser($data);
        $response = [
          'sucess' => true,
          'acces' => 'allowed',
          'action' => 'user deleted'
        ];
      }else{
        throw new AccessDeniedException('Access denied for this username...');
      }
    }catch(AccessDeniedException $e){
      $response = [
        'sucess' => false,
        'acces' => 'denied',
        'error' => 'Invalid role...',
      ];
    }

    $format = "json";
    $result = $app['serializer']->serialize($response, $format);
    return new Response($result, 200);
  }

  /*
  * POST Renewal
  */
  public function Renewal (Application $app)
  {
    $token = $app['security.token_storage']->getToken();
    $_user = $token->getUser();
    $user = $app['dao.user']->refreshUser($_user);

    $format = "json";
    $u = $app['serializer']->serialize($user, $format);

    $response = [
      'sucess' => true,
      'acces' => 'allowed',
      'user' => $u,
      'token' => $app['security.jwt.encoder']->encode(['email' => $user->getEmail()]),
    ];

    return new Response(json_encode($response), 200);
  }

  /*
  * POST Logout
  */
  public function Logout (Request $request, Application $app)
  {
    $token = $app['security.token_storage']->getToken();
    $user = $token->getUser();

    if($app['security.authorization_checker']->isGranted('ROLE_USER') || $app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
      $response = [
        'sucess' => true,
        'acces' => 'allowed',
        'user' => $user,
        'token' => null,
      ];
    }else{
      $response = [
        'sucess' => false,
        'acces' => 'denied'
      ];
    }

    return new Response(json_encode($response), 200);

  }

  /* TODO
  * will use for admin path
  */
  public function loginAction(Request $request, Application $app)
  {
    return $app['twig']->render('login.html.twig', array());
  }

}
