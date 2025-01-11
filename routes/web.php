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
    //return $router->app->version();
    //phpinfo();
});

/**
 * Routes for resource user
 */
$router->get('user', 'UserController@all');
$router->get('user/{id}', 'UserController@get');
$router->post('user', 'UserController@store');
$router->post('user/{id}', ['middleware' => ['jwt.auth'], 'uses' => 'UserController@update']);
$router->delete('user/{id}', 'UserController@delete');

/**
 * Routes for resource video
 */
$router->get('video', 'VideoController@all');
$router->get('video/user/{userId}[/[{withVideoUrl}]]', 'VideoController@getVideosByUserId');
$router->get('video/{id}', 'VideoController@get');
$router->post('video', 'VideoController@store');
$router->post('video/search', 'VideoController@search');
$router->post('video/{id}', 'VideoController@update');
$router->delete('video/{id}', 'VideoController@delete');


/**
 * Routes for resource comment
 */
$router->get('comment', 'CommentController@all');
$router->get('comment/{id}', 'CommentController@get');
$router->post('comment', ['middleware' => ['jwt.auth'], 'uses' => 'CommentController@store']);
$router->put('comment/{id}', 'CommentController@update');
$router->delete('comment/{id}', 'CommentController@delete');
$router->get('comment/video/{id}', 'CommentController@getCommentsByVideoId');

/**
 * Routes for resource like-comment
 */
$router->group([

    'prefix' => 'like-comment'

], function ($router) {
    $router->get('{userId}/{commentId}', 'LikeCommentController@get');
    $router->post('/', 'LikeCommentController@store');
    $router->delete('{userId}/{commentId}', 'LikeCommentController@delete');
    $router->get('likes/total/{commentId}', 'LikeCommentController@getCommentLikes');
    $router->get('dislikes/total/{commentId}', 'LikeCommentController@getCommentDislikes');
});

/**
 * Routes for resource like-video
 */

$router->group([

    'prefix' => 'like-video'

], function ($router) {
    $router->get('{userId}/{videoId}', 'LikeVideoController@get');
    $router->post('/', 'LikeVideoController@store');
    $router->delete('{userId}/{videoId}', 'LikeVideoController@delete');
    $router->get('likes/total/{videoId}', 'LikeVideoController@getVideoLikes');
    $router->get('dislikes/total/{videoId}', 'LikeVideoController@getVideoDislikes');
});
