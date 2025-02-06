<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Support\Facades\Hash;
use App\Video;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = factory(User::class)->create([
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'photo' => '/demo/user/default_user.png',
        ]);

        // se crea un video para el usuario admin
        $video = factory(Video::class)->create([
            'user_id' => $user->id
        ]);
    }
}
