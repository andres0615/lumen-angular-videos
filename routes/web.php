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

/**
 * Routes for resource image-controller
 */
//$router->get('image', 'ImageControllersController@all');
//$router->get('image/{id}', 'ImageControllersController@get');
//$router->post('image', 'ImageControllersController@add');
//$router->put('image/{id}', 'ImageControllersController@put');
//$router->delete('image/{id}', 'ImageControllersController@remove');


//$router->get('image/test', 'ImageControllersController@test');