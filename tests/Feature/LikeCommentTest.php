<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\LikeComment;
use Illuminate\Support\Facades\Log;

class LikeCommentTest extends TestCase
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

    public function testLikeCommentFactory()
    {
        $likeComment = factory(LikeComment::class)->create();
        Log::info($likeComment);
        $this->assertInstanceOf(LikeComment::class, $likeComment);
        LikeComment::where('user_id', $likeComment->user_id)
        ->where('comment_id', $likeComment->comment_id)
        ->delete();
    }
}
