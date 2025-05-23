<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use Illuminate\Support\Facades\Log;
use App\User;
use App\Comment;
use App\LikeComment;

$factory->define(App\LikeComment::class, function (
    Faker\Generator $faker
) {

    return [
        'type' => $faker->boolean,
        'user_id' => function(){
            $user = factory(User::class)->create();
            return $user->id;
        },
        'comment_id' => function(){
            $comment = factory(Comment::class)->create();
            return $comment->id;
        }
    ];
});
