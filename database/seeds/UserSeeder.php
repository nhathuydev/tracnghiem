<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name' => 'Super User',
            'email'=> 'superuser@gmail.com',
            'password' => ('Aa123456$'),
            'isAdmin' => true,
            'isActive' => true,
        ]);

        \App\User::create([
            'name' => 'Normal User',
            'email'=> 'normaluser@gmail.com',
            'password' => ('Aa123456$'),
            'isAdmin' => false,
            'isActive' => false,
        ]);
    }
}
