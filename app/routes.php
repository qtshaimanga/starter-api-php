<?php

use Silex\Application;

  /* GET */
  $app->get('/api/users', "Api\UserBundle\Controller\UserController::FindAllUsers")->bind('api_all_users');

  $app->get('/api/user', "Api\UserBundle\Controller\UserController::UserByToken")->bind('api_user');

  /*POST*/
  $app->post('/api/login', "Api\UserBundle\Controller\UserController::Login")->bind('api_login');

  /*TODO*/
  // $app->post('/api/register', function(Request $request) use ($app){
  //
  //   parse_str($request->getContent(), $data);
  //   //$vars['_password']
  //
  //   return $app->json_encode();
  //
  // });

  /*PUT*/

  /*DELETE*/
