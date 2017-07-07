<?php
// Header for testing
// header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Cache-Control");
// header('Access-Control-Allow-Origin: http://localhost:7000');   //provenance
// header('Access-Control-Allow-Credentials: true');
// header('Access-Control-Allow-Methods: GET, POST, PUT');
// header('content-type: application/json; charset=utf-8');

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app['monolog.level'] = 'INFO';

// Handling CORS preflight request
// $app->before(function (Request $request) {
//     if ($request->getMethod() === 'OPTIONS') {
//         $response = new Response();
//         $response->headers->set('Access-Control-Allow-Origin', '*');    //*
//         $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
//         $response->headers->set('Access-Control-Allow-Headers', 'Content-Type');
//         $response->setStatusCode(200);
//         return $response->send();
//     }
// }, Application::EARLY_EVENT);

// Handling CORS respons with right headers
// $app->after(function (Request $request, Response $response) {
//     $response->headers->set('Access-Control-Allow-Origin', '*'); //*
//     $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,DELETE,OPTIONS');
// });

// Accepting JSON
// $app->before(function (Request $request) {
//     if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
//         $data = json_decode($request->getContent(), true);
//         $request->request->replace(is_array($data) ? $data : array());
//     }
// });


$filename = __DIR__.preg_replace('#(\?.*)$#', '', $_SERVER['REQUEST_URI']);
if (php_sapi_name() === 'cli-server' && is_file($filename)) {
    return false;
}

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => 'pdo_sqlite',
        'path'     => __DIR__.'/bdd/bdd.sqlite',
    ),
));

require __DIR__.'/app/app.php';
require __DIR__.'/app/routes.php';

$app->run();
