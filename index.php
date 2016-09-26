<?php

header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Cache-Control");
header('Access-Control-Allow-Origin: http://localhost:8080, https://'.$_SERVER['HTTP_HOST']);
//https://api.air-edf.io
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: GET, POST, PUT');
header('content-type: application/json; charset=utf-8');

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;
$app['monolog.level'] = 'INFO';

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
