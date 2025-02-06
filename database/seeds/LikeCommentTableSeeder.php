<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Video;
use App\LikeComment;
use App\Comment;

class LikeCommentTableSeeder extends Seeder
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

            $comments = Comment::where([
                'video_id' => $video->id
            ]);

            // $addLikes = true;

            $comments->each(function ($comment)use(&$addLikes){
                // La creacion de likes es intermitente
                if($addLikes){
                    $likesLimit = 1;
                    $likesQuantity = rand(1,$likesLimit);

                    // se obtienen usuarios random
                    $users = User::all()->shuffle()->take($likesQuantity);

                    // se agregan los likes al video
                    $users->each(function($user)use($comment){
                        factory(LikeComment::class)->create([
                            'user_id' => $user->id,
                            'comment_id' => $comment->id,
                        ]);
                    });
                }

                // se cambia el valor del flag
                $addLikes = !$addLikes;
            });
        });
    }
}
