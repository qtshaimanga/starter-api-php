<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\ParameterBag;
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


    //$sql = 'INSERT INTO "main"."locationData" ("id","nom","prenom","couleur","date","longitude","latitude") VALUES
    //('.$post[id].','.$post[nom].','.$post[prenom].','.$post[couleur].','.$post[date].','.$post[longitude].','.$post[latitude].')';


    $sql = 'INSERT INTO "main"."locationData" ("id","nom","prenom","couleur","date","longitude","latitude") VALUES
    ('.$post[id].',"'.$post[nom].'","'.$post[prenom].'","'.$post[couleur].'","'.$post[date].'","'.$post[longitude].'","'.$post[latitude].'")';



    $data = $app['db']->fetchAll($sql);

    //$post['id'] = createPost($post);
    //return $app->json($post[id], 201);
    return Response('Data sent', 201);

 });
//curl -X POST -d '{"id": "","nom": "","prenom": "","couleur": "","date": "","longitude": "","latitude": ""}' http://localhost:8080/locationDataSend/ --header "Content-Type:application/json" -v



 $app->run();
