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

// Return the value of how many To's one From is worth
// Example: Show the USD/BTC to exchange rate
// Valid API is required to get options, otherwise it'll only return the price, not the rest of the data.
$router->get('/v1/exchange/{source}/{target}[/{key}[/{options}]]', 'ExchangeController@index');

$router->get('/v1/latest[/{key}]', 'ApiLatestQuotes@index');