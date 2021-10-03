<?php

$router->group([

    'middleware' => 'jwt.auth',
    'prefix' => 'auth'

], function ($router) {

    $router->post('login', 'AuthController@login');
    $router->post('logout', 'AuthController@logout');
    $router->post('refresh', 'AuthController@refresh');
    $router->post('me', 'AuthController@me');

});

$router->post('auth/login', 'AuthController@login');