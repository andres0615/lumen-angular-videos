<?php

$router->group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');

});

$router->post('auth/login', 'AuthController@login');