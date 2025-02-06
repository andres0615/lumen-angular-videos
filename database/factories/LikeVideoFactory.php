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
use App\Video;

$factory->define(App\LikeVideo::class, function (
    Faker\Generator $faker
) {

    return [
        'type' => $faker->boolean,
        'user_id' => function(){
            $user = factory(User::class)->create();
            return $user->id;
        },
        'video_id' => function(){
            $video = factory(Video::class)->create();
            return $video->id;
        }
    ];
});
