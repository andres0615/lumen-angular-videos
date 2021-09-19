<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use Illuminate\Support\Facades\Log;

class UserTest extends TestCase
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

    public function testUserFactory(){
    	$user = factory(User::class)->make();
    	//Log::info($user);
		$this->assertInstanceOf(User::class, $user);
    }
}
