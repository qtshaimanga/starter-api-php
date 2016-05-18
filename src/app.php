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
      $sql = "SELECT rowid, * FROM USER";
      $users = $app['db']->fetchAll($sql);
      return  json_encode($users);
  });

  $app->get('/graines/', function () use ($app) {
     $sql = "SELECT rowid, * FROM GRAINE";
     $graines = $app['db']->fetchAll($sql);
     return  json_encode($graines);
  });

  $app->get('/pollens/', function () use ($app) {
       $sql = "SELECT rowid, * FROM POLLEN";
       $pollens = $app['db']->fetchAll($sql);
       return  json_encode($pollens);
  });

  //RETURN USER
  $app->get('/users/id={id}&param={param}', function ($id, $param) use ($app) {
      if($param == "all"){
        $param = "*";
      }
      
      $sql = "SELECT rowid, $param FROM USER WHERE rowid=".$id;
      $user = $app['db']->fetchAll($sql);
      return  json_encode($user);
  });

 //RETURN DISTANCE
 //Type of request: http://localhost:8080/graines/latitude=408.6962578192132&longitude=-97.8127746582031&perimeter=375
 $app->get('/graines/latitude={latitude}&longitude={longitude}&perimeter={perimeter}', function (Silex\Application $app, $latitude, $longitude, $perimeter) use ($app) {
    $sql = "SELECT rowid, * FROM GRAINE";
    $graines = $app['db']->fetchAll($sql);

    $distance = array();
    foreach ($graines as $index => $graine) {
      $latitudeRef = $graine['latitude'];
      $longitudeRef = $graine['longitude'];
      $calDistance = strval(abs(sqrt(pow($latitudeRef-$latitude, 2)+pow($longitudeRef-$longitude, 2))));

      if($perimeter>$calDistance){
        $distance[$graine['rowid']] = $calDistance;
      }
    }

    if (!isset($graines)) {
        $app->abort(404, "Parameter {$distance} is not valid.");
    }
    return json_encode([$distance]);
 });


 //RETURN CHILDS AND PARENT OF A SEED
 //Type of request: http://localhost:8080/graines/id=2
 $app->get('/graines/id={id}', function (Silex\Application $app, $id) use ($app) {

   $sql = "SELECT USER.nom, GRAINE.nom,USER.rowid, GRAINE.rowid FROM USER JOIN GRAINE ON GRAINE.rowid=USER.parent
   WHERE USER.parent=$id
   UNION SELECT GRAINE.nom, USER.nom, GRAINE.rowid, USER.rowid FROM GRAINE JOIN USER ON GRAINE.parent=USER.rowid
   WHERE GRAINE.parent=(SELECT GRAINE.parent FROM USER JOIN GRAINE ON GRAINE.rowid=USER.parent WHERE USER.parent=$id)";

   $parents = $app['db']->fetchAll($sql);
   //PARENT = $parents[0] AND CHILS = $parent[n!=0]
   return json_encode($parents);
 });


 //RETURN ALL CHILDS FOR EACH COLONIES OF AN USER
 //Type of request: http://localhost:8080/users/id=2
 $app->get('/users/parents/id={id}', function (Silex\Application $app, $id) use ($app) {

   $sql = "SELECT USER.nom as 'User.nom', USER.rowid as 'User.rowid', GRAINE.nom, GRAINE.rowid FROM USER
   INNER JOIN GRAINE ON (USER.parent=GRAINE.rowid)
   WHERE USER.parent
   IN (SELECT GRAINE.rowid FROM GRAINE WHERE GRAINE.parent=$id)";

   $colonies = $app['db']->fetchAll($sql);
   return json_encode($colonies);
 });


 //POST ALL
  $app->before(function (Request $request) {
      if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
          $data = json_decode($request->getContent(), true);
          $request->request->replace(is_array($data) ? $data : array());
      }
  });

  $app->post('/user/', function (Request $request) use ($app){
     $post = array(
         'pseudo' => $request->request->get('pseudo'),
         'date'  => $request->request->get('date'),
         'email'  => $request->request->get('email'),
         'parent'  => $request->request->get('parent'),
         'mdp'  => $request->request->get('mdp'),
     );
     $sql = 'INSERT INTO "main"."USER" ("pseudo","date","email","parent","mdp") VALUES
     ("'.$post['pseudo'].'","'.$post['date'].'","'.$post['email'].'","'.$post['parent'].'","'.$post['mdp'].'")';

     $data = $app['db']->fetchAll($sql);
     $rowid = $app['db']->lastInsertId();

     return new Response(json_encode('USER sent'), 200, ['Data' => $rowid]);
  });

 $app->post('/graine/', function (Request $request) use ($app){
    $post = array(
        'latitude' => $request->request->get('latitude'),
        'longitude'  => $request->request->get('longitude'),
        'nom'  => $request->request->get('nom'),
        'date'  => $request->request->get('date'),
        'parent'  => $request->request->get('parent'),
    );
    $sql = 'INSERT INTO "main"."GRAINE" ("latitude","longitude","nom","date","parent") VALUES
    ('.$post['latitude'].','.$post['longitude'].','.$post['nom'].','.$post['date'].','.$post['parent'].')';

    $data = $app['db']->fetchAll($sql);
    return json_encode('GRAINE sent', 201);
 });

 $app->post('/pollen/', function (Request $request) use ($app){
    $post = array(
        'user' => $request->request->get('user'),
        'latitude'  => $request->request->get('latitude'),
        'longitude'  => $request->request->get('longitude'),
    );
    $sql = 'INSERT INTO "main"."POLLEN" ("user","latitude","longitude") VALUES
    ('.$post['user'].','.$post['latitude'].','.$post['longitude'].')';

    $data = $app['db']->fetchAll($sql);
    return json_encode('POLLEN sent', 201);
 });

 //PUT -> UPDATE USER
 $app->put('/users/id={id}', function (Request $request, $id) use ($app) {
   $put = array(
     'pseudo' => $request->request->get('pseudo'),
     'date'  => $request->request->get('date'),
     'email'  => $request->request->get('email'),
     'parent'  => $request->request->get('parent'),
     'mdp'  => $request->request->get('mdp'),
   );

   $sql = 'UPDATE USER SET pseudo="'
   .$put['pseudo'].'" ,date="'
   .$put['date'].'" ,email="'
   .$put['email'].'" ,parent="'
   .$put['parent'].'" ,mdp="'
   .$put['mdp']
   .'" WHERE rowid="'.$id.'"';

   $data = $app['db']->fetchAll($sql);
   return json_encode('USER update', 201);
 });


 $app->run();
