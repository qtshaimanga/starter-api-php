<?php

use Silex\Application;

  /*
  * GET
  */
  $app->get('/api/users', "Api\UserBundle\Controller\UserController::FindAllUsers")->bind('api_all_users');
  $app->get('/api/user', "Api\UserBundle\Controller\UserController::UserByToken")->bind('api_user');
  $app->get('/api/user/{id}', "Api\UserBundle\Controller\UserController::UserById")->bind('api_user_byid');

  /*
  * POST
  */
  $app->post('/api/login', "Api\UserBundle\Controller\UserController::Login")->bind('api_login');
  $app->post('/api/register', "Api\UserBundle\Controller\UserController::Register")->bind('api_register');
  $app->post('/api/delete', "Api\UserBundle\Controller\UserController::DeleteUser")->bind('api_delete_user');
  $app->post('/api/update', "Api\UserBundle\Controller\UserController::UpdateUser")->bind('api_update_user');
  $app->post('/api/update/role', "Api\UserBundle\Controller\UserController::UpdateUserRole")->bind('api_update_user_role');
  $app->post('/api/renew', "Api\UserBundle\Controller\UserController::Renewal")->bind('api_renew_token');
  $app->post('/api/logout', "Api\UserBundle\Controller\UserController::Logout")->bind('api_logout');
