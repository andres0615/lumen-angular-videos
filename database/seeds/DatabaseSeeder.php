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
        $this->call('UsersTableSeeder');
        $this->call('VideosTableSeeder');
        $this->call('CommentsTableSeeder');
        $this->call('LikeVideoTableSeeder');
        $this->call('LikeCommentTableSeeder');
    }
}
