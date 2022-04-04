<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post('contas', 'ContaController@store');
$router->put('contas/{uuid}/bancoemissor', 'ContaController@bancoemissor');
$router->put('contas/{uuid}/valorminimo', 'ContaController@valorminimo');
$router->put('contas/{uuid}/status', 'ContaController@status');

$router->post('agencias', 'AgenciaController@store');
$router->put('agencias/{uuid}/bancoemissor', 'AgenciaController@bancoemissor');
