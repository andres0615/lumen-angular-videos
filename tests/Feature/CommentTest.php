<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Comment;
use App\Video;
use App\LikeComment;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;
// use Tymon\JWTAuth\JWTAuth;

class CommentTest extends TestCase
{
    public function testCommentFactory()
    {
        $comment = factory(Comment::class)->create();
        //Log::info($comment);
        $this->assertInstanceOf(Comment::class, $comment);
        $comment->delete();
    }

    public function testAllFunction()
    {
        $this->json('GET', '/comment')
        ->seeJsonStructure([
            [
                'id',
                'comment',
                'user_id',
                'video_id',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testGetFunction()
    {
        $id = Comment::all()->shuffle()->first()->id;
        $url = '/comment/' . $id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'id',
                'comment',
                'user_id',
                'video_id',
                'created_at',
                'updated_at'
            ]);
    }

    public function testStoreFunction()
    {
        Log::info('=============== aq4t75mrcrfl3rl ==============');

        $faker = Faker\Factory::create();

        // create user and video

        $user = factory(User::class)->create();
        $video = factory(Video::class)->create();

        // login user

        $token = JWTAuth::fromUser($user);

        // create comment

        $payload = [
            'comment' => $faker->paragraph,
            'user_id' => $user->id,
            'video_id' => $video->id
        ];

        $url = '/comment?token=' . $token;

        $response = $this->call('POST', $url, $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testUpdateFunction()
    {
        $faker = Faker\Factory::create();

        $comment = Comment::all()->shuffle()->first();
        $id = $comment->id;

        $payload = [
            'comment' => $faker->paragraph
        ];

        $response = $this->call('put', '/comment/' . $id, $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testDeleteFunction()
    {
        $comment = Comment::all()->shuffle()->first();
        $id = $comment->id;

        $url = '/comment/' . $id;

        $response = $this->call('delete', $url);

        $this->assertEquals(200, $response->status());
    }

    public function testGetCommentsByVideoId()
    {

        /* Se obtiene el id de algun video que tenga al menos un comentario. */

        $id = Comment::all()->shuffle()->first()->video_id;
        $url = '/comment/video/' . $id;

        $this->json('GET', $url)
        ->seeJsonStructure([
            [
                'id',
                'comment',
                'user_id',
                'video_id',
                'username',
                'user_photo',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function logObject($object, $msg)
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
