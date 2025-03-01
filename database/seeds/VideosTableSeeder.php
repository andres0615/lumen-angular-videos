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
        // se crean videos fake
        $videos = factory(Video::class, 15)->create();
    }
}
