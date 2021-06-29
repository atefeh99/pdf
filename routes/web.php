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

$router->post('/{identifier}', 'PdfMakerController@getPdf');


$router->group(['prefix' => 'interpreters'], function () use ($router) {
    $router->post('', 'InterpreterController@store');
    $router->get('', 'InterpreterController@index');
    $router->get('/{id}', 'InterpreterController@show');
    $router->delete('/{id}', 'InterpreterController@remove');

});
