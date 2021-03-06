<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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


$router->group(['prefix' => 'api'], function () use ($router) {

   

      resource('movie', "API\MovieController", $router);
      $router->post('/searchMovies', 'API\MovieController@searchMovies');
});

function resource($uri, $controller, $router)
{
	$router->get($uri, $controller.'@index');
	$router->get($uri.'/create', $controller.'@create');
	$router->post($uri, $controller.'@store');
	$router->get($uri.'/{id}', $controller.'@show');
	$router->get($uri.'/{id}/edit', $controller.'@edit');
	$router->put($uri.'/{id}', $controller.'@update');
	$router->patch($uri.'/{id}', $controller.'@update');
	$router->delete($uri.'/{id}', $controller.'@destroy');
}