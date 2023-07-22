<?php

use Spaede\Support\Application;
use Spaede\Support\Engine\ConfigReader;

require_once '../vendor/autoload.php';

$app = Application::make();

// Start the application
$app->start();

// You add any classes to IOC at this point
dd(ConfigReader::getConfig('database.drivers.mysql.sdfsdf'));


// Handle Request
$app->sendRequestThroughRouter();

