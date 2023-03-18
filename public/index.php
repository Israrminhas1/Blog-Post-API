<?php


use Slim\Factory\AppFactory;

use ProjectApi\Controller\PostController;
use ProjectApi\Controller\CategoryController;


require __DIR__ . '/../boot.php';

$container = require __DIR__ . '/../config/container.php';

AppFactory::setContainer($container);
$app = AppFactory::create();

//Post Routes
$app->post('/v1/posts/create',PostController::class . ':create');
$app->get('/v1/posts',PostController::class . ':list');
$app->get('/v1/posts/{id}',PostController::class . ':read');
$app->get('/v1/posts/slug/{slug}',PostController::class . ':readBySlug');
$app->put('/v1/posts/update/{id}',PostController::class . ':update');
$app->delete('/v1/posts/delete/{id}',PostController::class . ':delete');
//Category Routes
$app->post('/v1/categories/create',CategoryController::class . ':create');
$app->get('/v1/categories',CategoryController::class . ':list');
$app->get('/v1/categories/{id}',CategoryController::class . ':read');
$app->put('/v1/categories/update/{id}',CategoryController::class . ':update');
$app->delete('/v1/categories/delete/{id}',CategoryController::class . ':delete');

$app->addErrorMiddleware(true, true, true);

$app->run();