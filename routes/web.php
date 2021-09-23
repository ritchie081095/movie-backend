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


    $router->post('/register', 'AuthController@register');
    $router->post('/login', 'AuthController@authenticate');




    $router->group(['middleware' => 'auth'], function () use ($router) {
        $router->post('/getuserdata', 'AuthController@getuserdata');
        $router->post('/logout', 'AuthController@logout');
        
        $router->get('/user', 'API\UserManagementController@index');
        $router->post('/user', 'API\UserManagementController@store');
        $router->put('/user/{id}', 'API\UserManagementController@update');
        $router->delete('/user/{id}', 'API\UserManagementController@destroy');
    });

    
});