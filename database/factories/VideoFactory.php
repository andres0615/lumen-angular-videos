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

$factory->define(App\Video::class, function (
    Faker\Generator $faker
) {

    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'video' => '/video/test.mp4',
        'thumbnail' => '/thumbnail/thumbnail.png',
        'user_id' => function() {
            $user = factory(User::class)->create();
            return $user->id;
        }
    ];
});
