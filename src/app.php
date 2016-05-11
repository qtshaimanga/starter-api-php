<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;

  //DATA
  $yolo = array(
    "Symbiosis" => "Camille, Etienne, Victoire, Quentin",
   );

 //GET ALL
  $app->get('/', function() use ($yolo) {
    return json_encode($yolo);
  });

  $app->get('/users/', function () use ($app) {
      $sql = "SELECT * FROM USER";
      $users = $app['db']->fetchAll($sql);
      return  json_encode($users);
  });

  $app->get('/graines/', function () use ($app) {
     $sql = "SELECT * FROM GRAINE";
     $graines = $app['db']->fetchAll($sql);
     return  json_encode($graines);
   });

   $app->get('/pollens/', function () use ($app) {
       $sql = "SELECT * FROM POLLEN";
       $pollens = $app['db']->fetchAll($sql);
       return  json_encode($pollens);
   });

 //GET WITH PARAMETERS -> RETURN DISTANCE
 $app->get('/graines/latitude={latitude}&longitude={longitude}', function (Silex\Application $app, $latitude, $longitude) use ($graines) {
    $sql = "SELECT rowid, * FROM GRAINE";
    $graines = $app['db']->fetchAll($sql);

    $distance = array();
    foreach ($graines as $index => $graine) {
      $latitudeRef = $graine[latitude];
      $longitudeRef = $graine[longitude];

      $calDistance = strval(sqrt(pow($latitudeRef-$latitude, 2)+pow($longitudeRef-$longitude, 2)));
      $distance[$graine[rowid]] = $calDistance;
    }

    if (!isset($graines)) {
        $app->abort(404, "Parameter {$distance} is not valid.");
    }
    return json_encode([$distance]);
 });


 //POST
 /*
  $app->before(function (Request $request) {
      if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
          $data = json_decode($request->getContent(), true);
          $request->request->replace(is_array($data) ? $data : array());
      }
  });

 $app->post('/locationDataSend/', function (Request $request) use ($app){

    $post = array(
        'id' => $request->request->get('id'),
        'nom'  => $request->request->get('nom'),
        'prenom'  => $request->request->get('prenom'),
        'couleur'  => $request->request->get('couleur'),
        'date'  => $request->request->get('date'),
        'longitude'  => $request->request->get('longitude'),
        'latitude'  => $request->request->get('latitude'),
    );

    $sql = 'INSERT INTO "main"."locationData" ("id","nom","prenom","couleur","date","longitude","latitude") VALUES
    ('.$post[id].',"'.$post[nom].'","'.$post[prenom].'","'.$post[couleur].'","'.$post[date].'","'.$post[longitude].'","'.$post[latitude].'")';

    $data = $app['db']->fetchAll($sql);

    return Response('Data sent', 201);
 });
 */

 $app->run();
