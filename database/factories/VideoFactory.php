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
        'description' => $faker->sentence(30,false),
        // 'video' => $demoVideo['video'],
        'video' => function()use($demoVideo){
            $videoPath = $demoVideo['video'];

            // se agrega un hash diferente a cada video para que el frontend detecte el cambio
            $hash = str_random(10);
            $videoPath = $videoPath . '?hash=' . $hash;
            return $videoPath;
        },
        'thumbnail' => $demoVideo['thumbnail'],
        'user_id' => function() {
            $user = factory(User::class)->create();
            return $user->id;
        }
    ];
});
