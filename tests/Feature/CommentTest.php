<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\Comment;
use Illuminate\Support\Facades\Log;

class CommentTest extends TestCase
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

    public function testCommentFactory(){
    	$comment = factory(Comment::class)->create();
    	//Log::info($comment);
		$this->assertInstanceOf(Comment::class, $comment);
        $comment->delete();
    }
}
