<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class ImageTest extends TestCase
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

    /*public function testTestFunction() {
        //$response = $this->call('GET', '/image/test');

        //$this->assertEquals(200, $response->status());

        $this->json('GET', '/image/test')
        ->seeJsonStructure(['id', 'urls']);
        //->seeJson(['url' => true]);
    }*/
}
