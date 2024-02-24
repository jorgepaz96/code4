<?php
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('/users', 'Home::user');
$routes->get('/pdf', 'PdfController::index');
$routes->post('/auth/register', 'AuthController::register');
$routes->post('/auth/login', 'AuthController::login');


$routes->group('api', function ($routes) {
    $routes->group('v1', function ($routes) {        
        $routes->resource('examen',['controller' => 'ExamenController']);
        $routes->resource('procedenciamuestra',['controller' => 'ProcedenciaMuestraController']);
        $routes->resource('muestra',['controller' => 'MuestraController']);
        $routes->resource('compania',['controller' => 'CompaniaController']);
        $routes->resource('plantilla',['controller' => 'PlantillaController']);
        $routes->resource('tipopersona',['controller' => 'TipoPersonaController']);
    });
    // $routes->resource('examen', ['controller' => 'App\Controllers\Examen']);
});