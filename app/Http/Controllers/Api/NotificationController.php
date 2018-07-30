<?php

namespace App\Http\Controllers\Api;

use App\Repository\Notification\NotificationRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    private $notification;

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->notification = $notificationRepository;
    }

    public function list()
    {
        return response()->success($this->notification->list());
    }

    public function markRead(Request $request)
    {
        return response()->success($this->notification->markAsRead($request->nid));
    }
}
