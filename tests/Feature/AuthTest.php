<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use Illuminate\Support\Facades\Log;

class AuthTest extends TestCase
{
    public function testLoginFunction()
    {
        $payload = [
            'username' => 'admin',
            'password' => 'admin'
        ];

        $response = $this->call('POST', '/auth/login', $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testMeFunction()
    {
        $payload = [
            'username' => 'admin',
            'password' => 'admin'
        ];

        $response = $this->call('POST', '/auth/login', $payload);

        $token = json_decode($response->getContent())->access_token;

        $response = $this->call(
            'POST',
            '/auth/me',
            [],
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );

        $this->assertEquals(200, $response->status());
    }

    public function testLogoutFunction()
    {
        $payload = [
            'username' => 'admin',
            'password' => 'admin'
        ];

        $response = $this->call('POST', '/auth/login', $payload);

        $token = json_decode($response->getContent())->access_token;

        $response = $this->call(
            'POST',
            '/auth/logout',
            [],
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );

        $this->assertEquals(200, $response->status());
    }
}
