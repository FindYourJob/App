<?php

require_once __DIR__ . '/../vendor/autoload.php';

$app = new Base\Application();

$app->after(function (Symfony\Component\HttpFoundation\Request $request, Symfony\Component\HttpFoundation\Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

return $app;