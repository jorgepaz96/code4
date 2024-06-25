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

        $routes->group('tipopersona', function ($routes) {
            $routes->get('getTodos','TipoPersonaController::getTodos');
            $routes->get('(.*)','TipoPersonaController::show/$1');
        });
        $routes->group('tipodocumento', function ($routes) {
            $routes->get('getTodos','TipoDocumentoController::getTodos');
            $routes->get('(.*)','TipoDocumentoController::show/$1');
        });
        $routes->group('profesion', function ($routes) {
            $routes->get('getTodos','ProfesionController::getTodos');
            $routes->get('(.*)','ProfesionController::show/$1');
        });
        $routes->group('examen', function ($routes) {
            $routes->get('listadespegable','ExamenController::listaDespegable');
            $routes->get('listadespegable/(.*)','ExamenController::listaDespegableById/$1');
            $routes->get('tarifarioprecio/(.*)/(.*)','ExamenController::getExamenTarifarioPrecio/$1/$2');
        });
        $routes->group('muestra', function ($routes) {
            $routes->get('listadespegable','MuestraController::listaDespegable');
            $routes->get('listadespegable/(.*)','MuestraController::listaDespegableById/$1');
        });
        $routes->group('procedenciamuestra', function ($routes) {
            $routes->get('listadespegable','ProcedenciaMuestraController::listaDespegable');
            $routes->get('listadespegable/(.*)','ProcedenciaMuestraController::listaDespegableById/$1');
        });
        $routes->group('plantilla', function ($routes) {
            $routes->get('listadespegable','PlantillaController::listaDespegable');
            $routes->get('listadespegable/(.*)','PlantillaController::listaDespegableById/$1');
        });
        $routes->group('etiqueta', function ($routes) {
            $routes->get('listadespegable','EtiquetaController::listaDespegable');
            $routes->get('listadespegable/(.*)','EtiquetaController::listaDespegableById/$1');
        });
        $routes->group('estudio', function ($routes) {
            $routes->get('listadespegable','EstudioController::listaDespegable');
            $routes->get('listadespegable/(.*)','EstudioController::listaDespegableById/$1');
        });
        $routes->group('compania', function ($routes) {
            $routes->get('listadespegable','CompaniaController::listaDespegable');
            $routes->get('listadespegable/(.*)','CompaniaController::listaDespegableById/$1');
        });
        $routes->group('medico', function ($routes) {
            $routes->get('listadespegable','MedicoController::listaDespegable');
            $routes->get('listadespegable/(.*)','MedicoController::listaDespegableById/$1');
        });
        $routes->group('paciente', function ($routes) {
            $routes->get('listadespegable','PacienteController::listaDespegable');
            $routes->get('listadespegable/(.*)','PacienteController::listaDespegableById/$1');
        });
        $routes->group('ordencab', function ($routes) {
            $routes->get('print/(.*)','OrdenCabController::printOrden/$1');            
            $routes->get('obtenercelular/(.*)/(.*)','OrdenCabController::getCelularEnvio/$1/$2');
            $routes->get('obteneremail/(.*)/(.*)','OrdenCabController::getEmailEnvio/$1/$2');
        });

        $routes->resource('examen',['controller' => 'ExamenController']);
        $routes->resource('procedenciamuestra',['controller' => 'ProcedenciaMuestraController']);
        $routes->resource('muestra',['controller' => 'MuestraController']);
        $routes->resource('compania',['controller' => 'CompaniaController']);
        $routes->resource('plantilla',['controller' => 'PlantillaController']);
        $routes->resource('ordencab',['controller' => 'OrdenCabController']);
        $routes->resource('medico',['controller' => 'MedicoController']);
        $routes->resource('paciente',['controller' => 'PacienteController']);
        $routes->resource('trabajador',['controller' => 'TrabajadorController']);
        $routes->resource('etiqueta',['controller' => 'EtiquetaController']);
        $routes->resource('resultado',['controller' => 'ResultadoController']);
        $routes->resource('menbrete',['controller' => 'MenbreteController']); //falta
        // $routes->resource('estudio',['controller' => 'EstudioController']);

        
    });
    // $routes->resource('examen', ['controller' => 'App\Controllers\Examen']);
});