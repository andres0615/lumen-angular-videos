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
    public function testLoginFunction()
    {

        $user = User::all()->shuffle()->first();

        $payload = [
            'username' => $user->username,
            'password' => $user->password
        ];

        $payload = [
            'username' => 'admin',
            'password' => 'admin'
        ];

        $response = $this->call('POST', '/auth/login', $payload);

        //Log::info($this->getObjectContent($response->getContent()));

        $this->assertEquals(200, $response->status());
    }

    public function getObjectContent($object) {
        ob_start();
        var_dump($object);
        $contents = ob_get_contents();
        ob_end_clean();
        return $contents;
    }
}
