<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 4/23/18
 * Time: 8:45 AM
 */

namespace App\Repository\Notification;


use App\Models\Notification;
use Illuminate\Support\Facades\Redis;

class NotificationRepository implements NotificationInterface
{
    private $notificatiton;

    public function __construct(Notification $notification)
    {
        $this->notificatiton = $notification;
    }

    public function create(Array $payload)
    {
        $payload['to'] = ['u.'. $payload['user_id']];
        Redis::publish('quiz-app', json_encode($payload));
        return $this->notificatiton->create($payload);
    }

    public function markAsRead($nid)
    {
        return $this->notificatiton->where('id', $nid)->update(['read' => true]);
    }

    public function list()
    {
        $uid = auth()->guard('api')->id();

        $result = $this->notificatiton
            ->where('user_id', $uid)
            ->orderBy('read')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->paginate();

        return $result;
    }
}