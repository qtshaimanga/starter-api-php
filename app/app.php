<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUserProvider;
use Symfony\Component\Security\Core\User\User;

// Set Timezone
date_default_timezone_set('Europe/Paris');

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register Twig service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__.'/../views'));

$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
}));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->before(function (Symfony\Component\HttpFoundation\Request $request) {
    if ($request->getMethod() === "OPTIONS") {
        $response = new \Symfony\Component\HttpFoundation\ResponseHeaderBag();
        $response->headers->set("Access-Control-Allow-Origin", "*");
        $response->headers->set("Access-Control-Allow-Methods", "GET,POST,PUT,DELETE,OPTIONS");
        $response->headers->set("Access-Control-Allow-Headers", "Content-Type");
        $response->setStatusCode(200);
        return $response->send();
    }
}, \Silex\Application::EARLY_EVENT);

$app['security.jwt'] = [
    'secret_key' => 'Very_secret_key',
    'life_time'  => 86400,
    'options'    => [
        'username_claim' => 'name', // default name, option specifying claim containing username
        'header_name' => 'X-Access-Token', // default null, option for usage normal oauth2 header
        'token_prefix' => 'Bearer',
    ]
];

//TODO
//$app->register(new Silex\Provider\SecurityServiceProvider(), array(
// 'security.firewalls' => array(),
// 'security.role_hierarchy' => array(
//      ROLE_ADMIN' => array('ROLE_USER')
//  )
// 'security.access_rules' => array(
//      array('^/api', 'ROLE_USER'),
//  )
// ));

$app['security.firewalls'] = array(
    'login' => [
        'pattern' => 'login|register|oauth',
        'anonymous' => true,
    ],
    'secured' => array(
        'pattern' => '^.*$',
        'logout' => array('logout_path' => '/logout'),
        'users' => $app->share(function () use ($app) {
            return new Api\UserBundle\DAO\UserDAO($app['db']);
        }),
        'jwt' => array(
            'use_forward' => true,
            'require_previous_session' => false,
            'stateless' => true,
        )
    ),
);

$app->register(new Silex\Provider\SecurityServiceProvider());

$app->register(new Silex\Provider\SecurityJWTServiceProvider());

//Register service provider for JWT/cnam
$app['users'] = $app->share(function ($app) {
    return new Api\UserBundle\DAO\UserDAO($app['db']);
});

// Register DATA BASE services
$app['dao.user'] = $app->share(function ($app) {
    return new Api\UserBundle\DAO\UserDAO($app['db']);
});

// Register error handler
$app->error(function (\Exception $e, $code) use ($app) {
    switch ($code) {
        case 403:
            $message = 'Access denied.';
            break;
        case 404:
            $message = 'The requested resource could not be found.';
            break;
        default:
            $message = "Something went wrong.";
    }
    return $app['twig']->render('error.html.twig', array('message' => $message));
});

$app->register(new Silex\Provider\FormServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider());

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\MonologServiceProvider(), array(
    'monolog.logfile' => __DIR__.'/../var/logs/api.log',
    'monolog.name' => 'Api',
    'monolog.level' => $app['monolog.level']
));

$app->register(new Silex\Provider\ServiceControllerServiceProvider());
if (isset($app['debug']) && $app['debug']) {
    $app->register(new Silex\Provider\HttpFragmentServiceProvider());
    $app->register(new Silex\Provider\WebProfilerServiceProvider(), array(
        'profiler.cache_dir' => __DIR__.'/../var/cache/profiler'
    ));
}
