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
        // se crean usuarios fake
        // factory(User::class, 10)->create();

        // Se crea usuario admin.
        $user = new User;
        $user->username = 'admin';
        $user->password = Hash::make('admin');
        $user->photo = '/user/default_user.png';
        $user->save();

        // // se crean usuarios fake
        // $users = factory(App\User::class, 10)->create();
        
        // // se crean videos fake
        // $users->each(function ($user) {
        //     factory(App\Video::class)->create(['user_id' => $user->id]);
        // });
    }
}
