<?php
//better to make public directory and keep all public files there
require_once __DIR__.'/vendor/autoload.php';

use App\Application;

$app = new Application();
$app->run();
