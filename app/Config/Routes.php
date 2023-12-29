<?php

use App\Controllers\ExamenController;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/users', 'Home::user');

$routes->group('api', function ($routes) {
    $routes->group('v1', function ($routes) {
        $routes->resource('examen',['controller' => 'ExamenController']);
        $routes->resource('procedencia_muestra',['controller' => 'ProcedenciaMuestraController']);
    });
    // $routes->resource('examen', ['controller' => 'App\Controllers\Examen']);
});