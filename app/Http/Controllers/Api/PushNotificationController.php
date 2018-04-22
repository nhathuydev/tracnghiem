<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class PushNotificationController extends Controller
{
    public function push(Request $request)
    {
        $users = [];
        foreach ($request->users as $u) {
            $users[] = 'u.' . $u;
        }
        Redis::publish('quiz-app', json_encode(array(
            'type' => $request->type,
            'data' => $request->data,
            'to' => $users,
            'message' => $request->message,
        )));
    }
}
