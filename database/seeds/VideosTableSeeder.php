<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Video;

class VideosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $video = factory(Video::class)->make();

        $user = User::where('username', 'admin')
                ->first();

        $video->user_id = $user->id;
        $video->save();
    }
}
