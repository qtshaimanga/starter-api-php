<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


$yolo = array(
       "teamOfYolo" => "Camille, Etienne, Victoire, Quentin",
 );

$pedometerData = array(
  [
         'numberOfSteps' => '100',
         'distance' => '2',
         'currentCadence' => '5',
         'floorsAscended' => '3',
         'floorsDescended' => '1',
         'startDate' => '2016-03-20',
         'endDate' => '2016-03-21',
  ],
  [
         'numberOfSteps' => '180',
         'distance' => '3',
         'currentCadence' => '4',
         'floorsAscended' => '4',
         'floorsDescended' => '1',
         'startDate' => '2016-03-21',
         'endDate' => '2016-03-22',
  ]
 );

 $app->get('/', function() use ($yolo) {
     return json_encode($yolo);
 });

 // PedometerData
 $app->get('/pedometerData/', function() use ($pedometerData) {
     return json_encode($pedometerData);
 });

/*
 $app->get('/pedometerData/{stockcode}', function (Silex\Application $app, $stockcode) use ($pedometerData) {
     if (!isset($pedometerData[$stockcode])) {
         $app->abort(404, "Stockcode {$stockcode} does not exist.");
     }
     return json_encode($pedometerData[$stockcode]);
 });
*/

 // LocationData
 $app->get('/locationData/', function () use ($app) {
     $sql = "SELECT * FROM locationData";
     $data = $app['db']->fetchAll($sql);

     return  json_encode($data);

 });

 $app->post('/locationDataSend/', function (Request $request){
    $id = $request->get('id');
    $nom = $request->get('nom');
    $prenom = $request->get('prenom');
    $couleur = $request->get('couleur');
    $date = $request->get('date');
    $longitude = $request->get('longitude');
    $latitude = $request->get('latitude');

    $locationData = array(
      'id' => $id,
      'nom' => $nom,
      'prenom' => $prenom,
      'couleur' => $couleur,
      'date' => $date,
      'longitude' => $longitude,
      'latitude' => $latitude,
    );

    return new Response('Data sent', 201);
 });


 $app->run();
