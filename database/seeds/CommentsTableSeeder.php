<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Video;
use App\Comment;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $videos = Video::all();

        $user = User::all()->shuffle()->first();

        $faker = Faker\Factory::create();

        foreach ($videos as $video) {
            $size = 3;

            for ($i=0; $i < $size; $i++) {
                $comment = new Comment;

                $comment->video_id = $video->id;
                $comment->user_id = $user->id;
                $comment->comment = $faker->sentence();

                $comment->save();
            }
        }
    }
}
