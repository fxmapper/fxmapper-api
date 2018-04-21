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
Route::group(
    [
        'middleware' => 'https',
        'prefix' => '/v1',
    ], function() {
        Route::get('/exchange/{source}/{target}[/{key}[/{options}]]', 'Version1\ExchangeEndpoint@index');

        Route::get('/latest',       'Version1\Errors@missingParams');
        Route::get('/latest/{key}', 'Version1\LatestQuotes@index');

        Route::get('/convert',                                      'Version1\Errors@missingParams');
        Route::get('/convert/{quantity}',                           'Version1\Errors@missingParams');
        Route::get('/convert/{quantity}/{source}',                  'Version1\Errors@missingParams');
        Route::get('/convert/{quantity}/{source}/{target}',         'Version1\Errors@missingParams');
        Route::get('/convert/{quantity}/{source}/{target}/{key}',   'Version1\Converter@index');
    }
);
