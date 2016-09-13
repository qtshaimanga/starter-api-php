<?php

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->before(function($request){
    $request->getSession()->start();
});

$app['twig'] = $app->share($app->extend('twig', function(Twig_Environment $twig, $app) {
    $twig->addExtension(new Twig_Extensions_Extension_Text());
    return $twig;
}));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider(), array(
    'session.storage.save_path' => __DIR__.'/../tmp/sessions',
));
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
  //'foo' => array('pattern' => '^/foo'),
  'security.firewalls' => array(
    'secured' => array(
      'pattern' => '^/',
      // 'stateless' => true,
      'anonymous' => true,
      'logout' => true, //array('logout_path' => '/deconnexion'),
      'form' => array(
        'login_path' => '/login',
        'check_path' => '/login_check',
        'require_previous_session' => false,
        'username_parameter' => '_username',
        'password_parameter' => '_password',
        'default_target_path' => '/api',
        'always_use_default_target_path' => true
      ),
      'users' => $app->share(function () use ($app) {
        return new Api\UserBundle\DAO\UserDAO($app['db']);
      }),
    ),
  ),
  'security.role_hierarchy' => array(
    'ROLE_ADMIN' => array('ROLE_USER')
  ),
  'security.access_rules' => array(
    array('^/api', 'ROLE_USER')
    //array('^/admin', 'ROLE_ADMIN'),
    //array('^.*$', 'ROLE_USER'),
    //array('^/foo$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
  ),
));

// Register services.
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
