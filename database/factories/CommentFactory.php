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

$factory->define(App\Comment::class, function (
    Faker\Generator $faker
) {
    $user = factory(User::class)->create();
    
    //Log::info('------'.$user.'-------');

    return [
        'comment' => $faker->sentence,
        'user_id' => $user->id
    ];
});
