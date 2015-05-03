<?php

require_once __DIR__.'/bootstrap.php';

use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/views',
));

//----------------------------------->
// Routing
//------------->
$app->get('/', function() {
    return new Response('Welcome to my new Silex app');
});

$app->get('/backend', function () use($app) {
    return $app['twig']->render('test.twig', array(
        'name' => 'test',
    ));
});
//-----------------------------------<

return $app;