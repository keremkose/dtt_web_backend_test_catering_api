<?php

/** @var Bramus\Router\Router $router */


$router->get('/Facility', App\Controllers\FacilityController::class . '@getFacility');
$router->get("/Facility/{id}", App\Controllers\FacilityController::class . '@getsingleFacility');
$router->get('/Location', App\Controllers\LocationController::class . '@getLocation');
$router->get('/Location/{id}', App\Controllers\LocationController::class . '@getsingleLocation');
$router->get('/Tag', App\Controllers\TagController::class . '@getTag');
$router->get('/Tag/{id}', App\Controllers\TagController::class . '@getsingleTag');

$router->delete('/Facility', App\Controllers\FacilityController::class . '@deleteFacility');
$router->delete('/Facility/{id}', App\Controllers\FacilityController::class . '@deletesingleFacility');
$router->delete('/Location', App\Controllers\LocationController::class . '@deleteLocation');
$router->delete('/Location/{id}', App\Controllers\LocationController::class . '@deletesingleLocation');
$router->delete('/Tag', App\Controllers\TagController::class . '@deleteTag');
$router->delete('/Tag/{id}', App\Controllers\TagController::class . '@deletesingleTag');

$router->post('/Facility', App\Controllers\FacilityController::class . '@postFacility');
$router->post('/Location', App\Controllers\LocationController::class . '@postLocation');
$router->post('/Tag', App\Controllers\TagController::class . '@postTag');



$router->put('/Facility', App\Controllers\FacilityController::class . '@putFacility');
$router->put('/Location', App\Controllers\LocationController::class . '@putLocation');
$router->put('/Tag', App\Controllers\TagController::class . '@putTag');
