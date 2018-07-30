<?php

namespace App\Http\Controllers\Api;

use App\Repository\Notification\NotificationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class PushNotificationController extends Controller
{
    private $notification;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notification = $notificationRepository;
    }
    public function push(Request $request)
    {
//        $users = [];
//        foreach ($request->users as $u) {
//            $users[] = 'u.' . $u;
//        }
        $payload = array(
            'type' => $request->type,
            'type' => null,
            'data' => $request->data,
            'user_id' => $request->users[0], // todo: Huy
            'title' => $request->title,
            'message' => $request->message,
        );

        return response()->success($this->notification->create($payload));
    }
}
