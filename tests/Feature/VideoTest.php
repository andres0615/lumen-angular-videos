<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Video;
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class VideoTest extends TestCase
{
    public function testVideoFactory()
    {
        $video = factory(Video::class)->create();
        //Log::info($video);
        $this->assertInstanceOf(Video::class, $video);
        $video->delete();
    }

    public function testAllFunction()
    {
        $this->json('GET', '/video')
        ->seeJsonStructure([
            [
                'id',
                'title',
                'description',
                'video',
                'thumbnail',
                'user_id',
                'created_at',
                'updated_at',
                'username'
            ]
        ]);
    }

    public function testGetFunction()
    {
        $id = Video::all()->shuffle()->first()->id;
        $url = '/video/' . $id;

        $this->json('GET', $url)
        ->seeJsonStructure([
                'id',
                'title',
                'description',
                'video',
                'thumbnail',
                'username',
                'user_photo',
                'user_id',
                'created_at',
                'updated_at'
            ]);
    }

    public function testStoreFunction()
    {
        $faker = Faker\Factory::create();

        $name = 'test.mp4';
        $path = storage_path($name);

        Log::info(basename(__FILE__) . ':' . __LINE__);
        Log::info($path);

        Log::info(is_file($path));

        $file = new UploadedFile($path, $name, filesize($path), 'video/mp4', null, true);

        // $user = User::all()->shuffle()->first();
        $user = factory(User::class)->create();

        // form data
        $payload = [
            'title' => $faker->sentence,
            'description' => $faker->sentence,
            'video' => $file,
            'thumbnail' => '/video/test.jpg',
            'user_id' => $user->id
        ];

        Log::info('=============== fd3lyx3benvfhs2 ==============');

        $response = $this->call('POST', '/video', $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testUpdateFunction()
    {
        Log::info('=============== opoiatb9azxnu0y ==============');

        $faker = Faker\Factory::create();

        // create video

        $video = factory(Video::class)->create();

        $id = $video->id;

        $name = 'test.mp4';
        $path = storage_path($name);

        $payload = [
            'title' => $faker->sentence,
            'description' => $faker->sentence,
        ];

        $response = $this->call('post', '/video/' . $id, $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testDeleteFunction()
    {
        $video = Video::all()->shuffle()->first();
        $id = $video->id;

        $url = '/video/' . $id;

        $response = $this->call('delete', $url);

        $this->assertEquals(200, $response->status());
    }

    public function testGetVideosByUserId()
    {
        $userId = Video::all()->shuffle()->first()->user_id;

        //$this->logObject($userId);

        $this->json('GET', '/video/user/' . $userId)
        ->seeJsonStructure([
            [
                'id',
                'title',
                'description',
                'video',
                'thumbnail',
                'user_id',
                'created_at',
                'updated_at',
                'username'
            ]
        ]);
    }

    public function testSearchFunction()
    {
        $faker = Faker\Factory::create();

        $video = Video::all()->shuffle()->first();

        $payload = [
            'keyword' => $video->title,
        ];

        $response = $this->call('POST', '/video/search', $payload);

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

    public function testPruebaHola()
    {
        $this->assertTrue(true);
    }
}
