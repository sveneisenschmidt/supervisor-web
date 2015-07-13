<?php

// web/index.php
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();

$app->register(new Silex\Provider\SessionServiceProvider());
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../src/SE/Supervisor/Web/Resources/views',
    'twig.options' => array('debug' => $app['debug'])
));
$app->register(new Igorw\Silex\ConfigServiceProvider(
    __DIR__."/../config.json"
));
$app->register(new SE\Supervisor\Web\SupervisorServiceProvider());
$app->mount('/', new \SE\Supervisor\Web\ControllerProvider());
$app->run();
