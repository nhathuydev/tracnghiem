<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AuthRequest;
use App\Repository\User\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use League\OAuth2\Server\Exception\OAuthServerException;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }
    public function login(AuthRequest $request)
    {
            if ($request->has('access_token')) {
                $u = Socialite::driver($request->driver)->userFromToken($request->access_token);
                $user = $this->user->get($u->email);
                if (!$user) {
                    $user = $this->user->create([
                        'name' => $u->name,
                        'email' => $u->email,
                        'password' => '',
                        'avatarUrl' => $u->avatar,
                        'provider_name' => $request->driver,
                        'provider_token' => $request->access_token,
                    ]);
                } else {
                    $this->user->update([
                        'provider_name' => $request->driver,
                        'provider_token' => $request->access_token,
                    ], $user->id);
                }
                $user->token = $user->createToken('app')->accessToken;
                return response()->success($user);
            } else {
                if (Auth::attempt([
                    'email' => $request->email,
                    'password' => $request->password,
                ])) {
                    $user = $this->user->get($request->email);
                    $user->token = $user->createToken('app')->accessToken;
                    return response()->success($user);
                } else {
                    return response()->error('', 401);
                }
            }
    }

    public function register(AuthRequest $request)
    {
        $user =  $this->user->create($request->toArray());
        $user->token = $user->createToken('app')->accessToken;
        return response()->success($user);
//        return $this->login($request);
    }

    public function redirectToProvider()
    {
        return Socialite::driver('github')->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        $user = Socialite::driver('github')->user();

        return response()->success($user);
        // $user->token;
    }
}
