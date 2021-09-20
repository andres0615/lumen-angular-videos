<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\LikeVideo;
use Illuminate\Support\Facades\Log;

class LikeVideoTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->get('/');

        $this->assertEquals(
            $this->app->version(),
            $this->response->getContent()
        );

        //$this->json()->seeJson();
    }

    public function testLikeVideoFactory(){
    	$likeVideo = factory(LikeVideo::class)->create();
    	//Log::info($comment);
		$this->assertInstanceOf(LikeVideo::class, $likeVideo);
        LikeVideo::where('user_id', $likeVideo->user_id)
        ->where('video_id', $likeVideo->video_id)
        ->delete();
    }
}
