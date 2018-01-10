<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AuthRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    public function login(AuthRequest $request)
    {

        $http = new Client();

        $response = $http->post(env('APP_AUTH_URL'). '/oauth/token', [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_CLIENT_PASSWORD'),
                'username' => $request->email,
                'password' => $request->password,
                'scope' => '',
            ],
        ]);

        dd($response);
        return json_decode((string) $response->getBody(), true);
    }
}
