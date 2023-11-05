<?php

use MiladRahimi\PhpRouter\Router;
use Projects\Intensa\Controllers\LinkController;

$router = Router::create();
$router->get('/{shortLink}', [LinkController::class, 'checkUrl']);
$router->post('/links', [LinkController::class, 'createShortUrl']);
$router->get('/', [LinkController::class, 'index']);
$router->dispatch();
