<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/../app/app.php';

$app = new App();
$app->handleRequest();
