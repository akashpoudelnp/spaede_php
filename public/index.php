<?php

use Spaede\Support\Application;
use Spaede\Support\Database\Connection;
use Spaede\Support\Engine\ConfigReader;

require_once '../vendor/autoload.php';

$app = Application::make();

// Start the application
$app->start();

// You add any classes to IOC at this point

// Handle Request
$app->sendRequestThroughRouter();

