<?php

use Illuminate\Database\Seeder;

class PassportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Illuminate\Support\Facades\DB::table('oauth_clients')->insert([
            'id' => 1,
            'name' => 'app',
            'secret' => env('PASSPORT_CLIENT_PASSWORD'),
            'redirect' => 'http://localhost',
            'password_client' => 1,
            'personal_access_client' => 1,
            'revoked' => 0,
        ]);
        \Illuminate\Support\Facades\DB::table('oauth_personal_access_clients')->insert([
            'client_id' => 1,
        ]);
    }
}
