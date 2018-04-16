<?php

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

// Only allow access to these routes with HTTPS
Route::group([
    'middleware' => 'https',
    'prefix' => '/v1',
    ], function() {
    Route::get('/exchange/{source}/{target}[/{key}[/{options}]]', 'ExchangeController@index');
    Route::get('/latest[/{key}]', 'ApiLatestQuotes@index');
});
