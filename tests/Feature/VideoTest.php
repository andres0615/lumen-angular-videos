<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;
use App\User;
use App\Video;
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

        $file = new UploadedFile($path, $name, filesize($path), 'video/mp4', null, true);

        $user = User::all()->shuffle()->first();

        $payload = [
            'title' => $faker->sentence,
            'description' => $faker->sentence,
            'video' => $file,
            'thumbnail' => '/video/test.jpg',
            'user_id' => $user->id
        ];

        $response = $this->call('POST', '/video', $payload);

        $this->assertEquals(200, $response->status());
    }

    public function testUpdateFunction()
    {
        $faker = Faker\Factory::create();

        $video = Video::all()->shuffle()->first();
        $id = $video->id;

        $name = 'test.mp4';
        $path = storage_path($name);

        $payload = [
            'title' => $faker->sentence,
            'description' => $faker->sentence,
        ];

        $response = $this->call('put', '/video/' . $id, $payload);

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
}
