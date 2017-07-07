<?php

use Silex\Application;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

//TODO rename UserDao
// use Api\UserBundle\Entity\User;

  /* GET */
  // $app->get('/', "Api\UserBundle\Controller\UserController::AllUser")->bind('api_all_user');
  // $app->get('/login', "Api\UserBundle\Controller\UserController::loginAction")->bind('login');

  $app->get('/api/user', function() use ($app){

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

      return $app->json([
          'hello' => $token->getUsername(),
          'username' => $user->getUsername(),
          'auth' => $jwt,
          'granted' => $granted,
          'granted_user' => $granted_user,
          'granted_super' => $granted_super,
      ]);
  });

  /*POST*/

  $app->post('/api/login', function(Request $request) use ($app){
    $vars = json_decode($request->getContent(), true);

    //test
    $salt = "cocacola";
    $encoder = new MessageDigestPasswordEncoder();
    $mdp_crypted = $encoder->encodePassword('foo', $salt);

    try {
      if (empty($vars['_username']) || empty($vars['_password'])) {
          throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
      }

      /**
       * @var $user User
       */
      $user = $app['dao.user']->loadUserByUsername($vars['_username']);

      if (! $app['security.encoder.digest']->isPasswordValid($user->getPassword(), $vars['_password'], $salt)) {
          throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $vars['_username']));
      } else {
          $response = [
              'success' => true,
              'token' => $app['security.jwt.encoder']->encode(['name' => $user->getUsername()]),
          ];
      }
    } catch (UsernameNotFoundException $e) {

      $response = [
          'success' => false,
          'error' => 'Invalid credentials ',
      ];
    }

    return $app->json($response, ($response['success'] == true ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST));
  });


  /*PUT*/

  /*DELETE*/
