<?php

/** @var \Laravel\Lumen\Routing\Router $router */

use App\Models\User;

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

$router->post('/auth/{provider}', ['as' => 'authenticate', 'uses' => 'AuthController@postAuthenticate']);

$router->get('/users/myUser', ['as' => 'myUser', 'uses' => 'MyUserController@getMyUser']);

$router->post('/transactions', ['as' => 'postTransaction', 'uses' => 'Transactions\TransactionsController@postTransaction']);
