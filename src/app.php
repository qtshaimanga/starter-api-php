<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Cache-Control");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('content-type: application/json; charset=utf-8');

  //DATA COMPOSITION
  $yolo = array(
    "interaction1" => "yolo",
    "interaction2" => "yoolo",
    "interaction3" => "yolo",
    "interaction4" => "yolo",
    "interaction5" => "yolo",
    "interaction6" => "yolo"
  );

  //si pas du l'application alors pas de request et si pas logé pas post etc
  /* GET */
  $app->get('/api/', function() use ($yolo) {
    return json_encode($yolo);
  });

  $app->get('/users/', function () use ($app) {
      $sql = "SELECT rowid, * FROM USER";
      $users = $app['db']->fetchAll($sql);
      return  json_encode($users);
  });

  //GET IMAGE
  $app->get('/uploads/{file}', function ($file) use ($app) {
    if (!file_exists(__DIR__.'/../uploads/'.$file)) {
      $url = __DIR__.'/../uploads/'.$file;
      return $app->abort(404, 'The image was not found.'.$url);
    }
    $stream = function () use ($file) {
      readfile(__DIR__.'/../uploads/'.$file);
    };

    return $app->stream($stream, 200, array('Content-Type' => 'image/GIF'));
  });

  //POST ALL
  $app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
      $data = json_decode($request->getContent(), true);
      $request->request->replace(is_array($data) ? $data : array());
    }
  });

  ///UPLOAD
  $app->post('/upload', function (Request $request) use ($app){
    //TODO
    //change name with id
    // add info in BDD
    //css transforme form in capture zone
    // optimise image taille etc.
    //change the upload_max_filesize post_max_size -> /etc/php5/cli/conf.d/php.ini
    $file = $request->files->get('upload');

    if ($file == NULL){
      $send = json_encode(array("status" => "Fail"));

      return $app->json($send, 500);

    } else {
      $file->move(__DIR__.'/../uploads',
      $file->getClientOriginalName());
      $send = json_encode(array("status" => "Ok"));

      return $app->json($send, 200);
    }

    return json_encode('GIF uploaded', 201);
   });

/* POST */
/* PUT */


  $app->run();
