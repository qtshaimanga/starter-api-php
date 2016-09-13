<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use Api\UserBundle\Entity\User;

  /* GET */
  $app->get('/api', "Api\UserBundle\Controller\UserController::AllUser")->bind('api_all_user');

  $app->get('/login', "Api\UserBundle\Controller\UserController::loginAction")->bind('login');


  // $app->get('/users/', function () use ($app) {
  //     $sql = "SELECT rowid, * FROM USER";
  //     $users = $app['db']->fetchAll($sql);
  //     return  json_encode($users);
  // });
  //
  // //GET IMAGE
  // $app->get('/uploads/{file}', function ($file) use ($app) {
  //   if (!file_exists(__DIR__.'/../uploads/'.$file)) {
  //     $url = __DIR__.'/../uploads/'.$file;
  //     return $app->abort(404, 'The image was not found.'.$url);
  //   }
  //   $stream = function () use ($file) {
  //     readfile(__DIR__.'/../uploads/'.$file);
  //   };
  //
  //   return $app->stream($stream, 200, array('Content-Type' => 'image/GIF'));
  // });
  //
  // //REF POST
  // $app->before(function (Request $request) {
  //   if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
  //     $data = json_decode($request->getContent(), true);
  //     $request->request->replace(is_array($data) ? $data : array());
  //   }
  // });
  //
  // ///UPLOAD
  // $app->post('/upload', function (Request $request) use ($app){
  //   //TODO
  //   //change name with id
  //   // add info in BDD
  //   //css transforme form in capture zone
  //   // optimise image taille etc.
  //   //change the upload_max_filesize post_max_size -> /etc/php5/cli/conf.d/php.ini
  //   $file = $request->files->get('upload');
  //
  //   if ($file == NULL){
  //     $send = json_encode(array("status" => "Fail"));
  //
  //     return $app->json($send, 500);
  //
  //   } else {
  //     $file->move(__DIR__.'/../uploads',
  //     $file->getClientOriginalName());
  //     $send = json_encode(array("status" => "file uploaded"));
  //
  //     return $app->json($send, 200);
  //   }
  //
  //   return json_encode('GIF uploaded', 201);
  //  });
  //
