<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Video;
use App\LikeVideo;
use App\Comment;
use App\LikeComment;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // factory(User::class, 10)->create();
        // factory(Video::class, 10)->create();
        // factory(LikeVideo::class, 5)->create();
        // factory(Comment::class, 10)->create();
        // factory(LikeComment::class, 5)->create();

        $this->call('UsersTableSeeder');
        $this->call('VideosTableSeeder');
        $this->call('CommentsTableSeeder');
        $this->call('LikeVideoTableSeeder');
        $this->call('LikeCommentTableSeeder');
    }
}
