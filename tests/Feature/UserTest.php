<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class UserTest extends TestCase
{
    public function testUserFactory()
    {
        $user = factory(User::class)->create();
        //Log::info($user);
        $this->assertInstanceOf(User::class, $user);
        $user->delete();
    }

    public function testAllFunction()
    {
        $this->json('GET', '/user')
        ->seeJsonStructure([
            [
                'id',
                'username',
                'photo',
                'created_at',
                'updated_at'
            ]
        ]);
    }

    public function testGetFunction()
    {
        $id = User::all()->shuffle()->first()->id;
        $url = '/user/' . $id;

        $this->json('GET', $url)
        ->seeJsonStructure(
            [
                'id',
                'username',
                'photo',
                'created_at',
                'updated_at'
            ]
        );
    }

    public function testStoreFunction()
    {
        $faker = Faker\Factory::create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $payload = [
            'username' => $faker->userName,
            'password' => $faker->password,
            'photo' => $file
        ];

        $response = $this->call('POST', '/user', $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testUpdateFunction()
    {

        //Se obtiene el token jwt

        $payload = [
            'username' => 'admin',
            'password' => 'admin'
        ];

        $response = $this->call('POST', '/auth/login', $payload);

        $token = json_decode($response->getContent())->access_token;

        //Se realiza el el resto del test

        $faker = Faker\Factory::create();

        $file = UploadedFile::fake()->image('avatar.jpg');

        $payload = [
            'username' => $faker->userName,
            'password' => $faker->password,
            'photo' => $file
        ];

        $user = User::all()->shuffle()->first();

        $id = $user->id;

        $url = '/user/' . $id;

        //Se usa el token jwt

        $response = $this->call(
            'POST',
            $url,
            $payload,
            [],
            [],
            ['HTTP_Authorization' => 'Bearer ' . $token]
        );

        $this->assertEquals(200, $response->status());
    }

    public function testDeleteFunction()
    {
        $user = User::all()->shuffle()->first();
        $id = $user->id;

        $url = '/user/' . $id;

        $response = $this->call('delete', $url);

        //Log::info($this->getObjectContent($response->getContent()));

        $this->assertEquals(200, $response->status());
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
