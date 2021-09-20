<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Video;
use Illuminate\Support\Facades\Log;

class VideoTest extends TestCase
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

    public function testVideoFactory(){
    	$video = factory(Video::class)->create();
    	//Log::info($video);
		$this->assertInstanceOf(Video::class, $video);
        $video->delete();
    }
}
