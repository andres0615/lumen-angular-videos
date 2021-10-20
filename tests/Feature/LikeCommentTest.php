<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Comment;
use App\LikeComment;
use Illuminate\Support\Facades\Log;

class LikeCommentTest extends TestCase
{
    public function testLikeCommentFactory()
    {
        $likeComment = factory(LikeComment::class)->create();

        $this->assertInstanceOf(LikeComment::class, $likeComment);
        
        LikeComment::where('user_id', $likeComment->user_id)
        ->where('comment_id', $likeComment->comment_id)
        ->delete();
    }


    public function testGetFunction()
    {
        $likeComment = LikeComment::all()->shuffle()->first();

        $url = '/like-comment/' . $likeComment->user_id . '/' . $likeComment->comment_id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'type',
                'user_id',
                'comment_id',
                'created_at',
                'updated_at'
            ]);
    }

    public function testStoreFunction()
    {
        $faker = Faker\Factory::create();

        $user = User::all()->shuffle()->first();
        $comment = Comment::all()->shuffle()->first();

        $payload = [
            'type' => true,
            'user_id' => $user->id,
            'comment_id' => $comment->id
        ];

        $response = $this->call('POST', '/like-comment', $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testDeleteFunction()
    {
        $likeComment = LikeComment::all()->shuffle()->first();

        $url = '/like-comment/' . $likeComment->user_id . '/' . $likeComment->comment_id;

        $response = $this->call('delete', $url);

        $this->assertEquals(200, $response->status());
    }

    public function testGetCommentLikes()
    {
        $likeComment = LikeComment::where('type', true)
                    ->get()
                    ->shuffle()
                    ->first();

        $url = '/like-comment/likes/total/' . $likeComment->comment_id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'likes'
            ]);
    }

    public function testGetCommentDislikes()
    {
        $dislikeComment = LikeComment::where('type', false)
                        ->get()
                        ->shuffle()
                        ->first();

        $url = '/like-comment/dislikes/total/' . $dislikeComment->comment_id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'dislikes'
            ]);
    }
}
