<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Comment;
use App\Video;
use App\LikeComment;
use Illuminate\Support\Facades\Log;

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
        $faker = Faker\Factory::create();

        $user = User::all()->shuffle()->first();
        $video = Video::all()->shuffle()->first();

        $payload = [
            'comment' => $faker->paragraph,
            'user_id' => $user->id,
            'video_id' => $video->id
        ];

        $response = $this->call('POST', '/comment', $payload);

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
        $url = '/video-comments/' . $id;

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
}
