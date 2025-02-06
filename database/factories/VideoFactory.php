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

    // videos demo
    $demoVideos = [
        [
            'video' => '/demo/video/video-1.mp4',
            'thumbnail' => '/demo/thumbnail/thumbnail-1.png',
        ],
        [
            'video' => '/demo/video/video-2.mp4',
            'thumbnail' => '/demo/thumbnail/thumbnail-2.png',
        ],
        [
            'video' => '/demo/video/video-3.mp4',
            'thumbnail' => '/demo/thumbnail/thumbnail-3.png',
        ]
    ];

    // obtener video random
    $demoVideo = collect($demoVideos)->shuffle()->first();

    return [
        'title' => $faker->sentence,
        'description' => $faker->sentence,
        'video' => $demoVideo['video'],
        'thumbnail' => $demoVideo['thumbnail'],
        'user_id' => function() {
            $user = factory(User::class)->create();
            return $user->id;
        }
    ];
});
