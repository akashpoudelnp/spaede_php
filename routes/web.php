<?php

use App\Http\Controllers\HomeController;
use Spaede\Support\Router;

Router::get('/', [HomeController::class, 'index']);
Router::post('/save', [HomeController::class, 'save']);
