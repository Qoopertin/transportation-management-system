<?php

define('LARAVEL_START', microtime(true));

// Register The Composer Auto Loader
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/../bootstrap/app.php';

// Run The Application
$app->handleRequest(
    Illuminate\Http\Request::capture()
);
