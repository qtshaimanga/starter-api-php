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

  //POST ALL
  $app->before(function (Request $request) {
    //if (0 === strpos($request->headers->get('Content-Type'), 'application/octet-stream')) {
      // $data = json_decode($request->getContent(), true);
      // $request->request->replace(is_array($data) ? $data : array());
    //}
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
      $data = json_decode($request->getContent(), true);
      $request->request->replace(is_array($data) ? $data : array());
    }
  });

  ///upload/
  $app->post('/upload', function (Request $request) use ($app){

    // var_dump("yolooo", $request->request);
    //
    // $ds          = DIRECTORY_SEPARATOR;
    // $storeFolder = 'uploads';
    // if (!empty($_FILES)) {
    //     $tempFile = $_FILES['file']['tmp_name'];
    //     $targetPath = dirname( __FILE__ ) . $ds. $storeFolder . $ds;
    //     $targetFile =  $targetPath. $_FILES['file']['name'];
    //     move_uploaded_file($tempFile,$targetFile);
    // }

    //change name with id
    // add info in BDD
    //css transforme form in capture zone
    // optimise image taille etc.
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

    // $files = $request->files->get($form->getName());
    // /* Make sure that Upload Directory is properly configured and writable */
    // $path = __DIR__.'/../upload/';
    // $filename = $files['FileUpload']->getClientOriginalName();
    // $files['FileUpload']->move($path,$filename);
    // $message = 'File was successfully uploaded!';

    return json_encode('GIF uploaded', 201);
   });

/* POST */
/* PUT */

/* uplaod
if(isset($_FILES['chanson'])){
                  $error = false;
                  $extensions_valides = array( 'mp3','ogg' );
                  //1. strrchr renvoie l'extension avec le point (« . »).
                  //2. substr(chaine,1) ignore le premier caractère de chaine.
                  //3. strtolower met l'extension en minuscules.
                  $extension_upload = strtolower(  substr(  strrchr($_FILES['chanson']['name'], '.')  ,1)  );
                   if ( !in_array($extension_upload,$extensions_valides) ){
                          $error="Extension non valide".$extension_upload;
                      echo "error : $error";}
                      if($error==false && $_FILES['chanson']['error']==0 ){
                             $fichier = "./uploads/".uniqid().".$extension_upload";
                             if(move_uploaded_file( //voir copy()
                                  $_FILES['chanson']['tmp_name'], $fichier)){
                                 $c = new Chanson($_POST['nom'], "",$fichier,date("Y-m-d h:i:s"),$_POST['style'], $_SESSION['id']);
                                 $c->save();
                                 //header("location:".URL);
                                 header("Location:".$_SERVER['HTTP_REFERER']);
                             }
                  }

*/

  $app->run();
