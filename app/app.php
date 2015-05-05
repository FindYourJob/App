<?php

$app = require_once __DIR__ . '/bootstrap.php';

$app->start(__DIR__ . '/views');

// Add routers
$app->addRouter(new App\Router\RootRouter());

return $app;
