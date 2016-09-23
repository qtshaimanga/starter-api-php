<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Api\UserBundle\Entity\User;

  /* GET */
  $app->get('/starterApiSecure/current/api', "Api\UserBundle\Controller\UserController::AllUser")->bind('api_all_user');

  $app->get('/starterApiSecure/current/login', "Api\UserBundle\Controller\UserController::loginAction")->bind('login');
