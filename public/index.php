<?php

require __DIR__ . '/../vendor/autoload.php';
require_once __DIR__.'/../framework/app.php';

$app = new App();
$app->handleRequest();
