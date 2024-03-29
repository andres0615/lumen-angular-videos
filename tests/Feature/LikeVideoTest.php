<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Video;
use App\LikeVideo;
use Illuminate\Support\Facades\Log;

class LikeVideoTest extends TestCase
{
    public function testLikeVideoFactory()
    {
        $likeVideo = factory(LikeVideo::class)->create();
        //Log::info($comment);
        $this->assertInstanceOf(LikeVideo::class, $likeVideo);
        LikeVideo::where('user_id', $likeVideo->user_id)
        ->where('video_id', $likeVideo->video_id)
        ->delete();
    }


    public function testGetFunction()
    {
        $likeVideo = LikeVideo::all()->shuffle()->first();

        $url = '/like-video/' . $likeVideo->user_id . '/' . $likeVideo->video_id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'type',
                'user_id',
                'video_id',
                'created_at',
                'updated_at'
            ]);
    }

    public function testStoreFunction()
    {
        $faker = Faker\Factory::create();

        $user = User::all()->shuffle()->first();
        $video = Video::all()->shuffle()->first();

        $payload = [
            'type' => true,
            'user_id' => $user->id,
            'video_id' => $video->id
        ];

        $response = $this->call('POST', '/like-video', $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testDeleteFunction()
    {
        $likeVideo = LikeVideo::all()->shuffle()->first();

        $url = '/like-video/' . $likeVideo->user_id . '/' . $likeVideo->video_id;

        $response = $this->call('delete', $url);

        $this->assertEquals(200, $response->status());
    }

    public function testGetVideoLikes()
    {
        $likeVideo = LikeVideo::where('type', true)
                    ->get()
                    ->shuffle()
                    ->first();

        $url = '/like-video/likes/total/' . $likeVideo->video_id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'likes'
            ]);
    }

    public function testGetVideoDislikes()
    {
        $dislikeVideo = LikeVideo::where('type', false)
                        ->get()
                        ->shuffle()
                        ->first();

        $url = '/like-video/dislikes/total/' . $dislikeVideo->video_id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'dislikes'
            ]);
    }

    public function logObject($object, $msg = null)
    {
        Log::info($msg);

        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        Log::info($contents);
        return;
    }
}
