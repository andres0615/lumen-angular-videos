<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Video;
use App\LikeVideo;

class LikeVideoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // se obtienen los videos
        $videos = Video::all();

        $addLikes = true;

        $videos->each(function ($video)use(&$addLikes){

            // La creacion de likes es intermitente
            if($addLikes){
                $likesLimit = 2;
                $likesQuantity = rand(1,$likesLimit);

                // se obtienen usuarios random
                $users = User::all()->shuffle()->take($likesQuantity);

                // se agregan los likes al video
                $users->each(function($user)use($video){
                    factory(LikeVideo::class)->create([
                        'user_id' => $user->id,
                        'video_id' => $video->id,
                    ]);
                });
            }

            // se cambia el valor del flag
            $addLikes = !$addLikes;
        });
    }
}
