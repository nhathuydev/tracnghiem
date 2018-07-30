<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CheckoutRequest;
use App\Repository\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
    // NL_MicroCheckout('55272', '4a1cd73937065238307f510f2666da2b', 'http://toimanhme.com/callback-checkout');
    private $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }

    public function requestAddPoint(Request $request)
    {

        return response()->success($this->user->requestAddPoint($request->point, $request->note));
    }

    public function checkRequestAddPoint(Request $request)
    {
         return response()->success($this->user->checkRequestAddPoint($request->requestId));
    }
}