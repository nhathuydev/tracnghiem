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
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class NotificationRepository implements NotificationInterface
{
    private $notificatiton;

    public function __construct(Notification $notification)
    {
        $this->notificatiton = $notification;
    }

    public function create(Array $payload)
    {
        // $payload['to'] = ['u.'. $payload['user_id']];
        // Redis::publish('quiz-app', json_encode($payload));

        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive(60*20);

        $notificationBuilder = new PayloadNotificationBuilder('my title');
        $notificationBuilder->setBody('Hello world')
            ->setSound('default');

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData(['a_data' => 'my_data']);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $token = "a_registration_from_your_database";

        $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

//        $downstreamResponse->numberSuccess();
//        $downstreamResponse->numberFailure();
//        $downstreamResponse->numberModification();
//
////return Array - you must remove all this tokens in your database
//        $downstreamResponse->tokensToDelete();
//
////return Array (key : oldToken, value : new token - you must change the token in your database )
//        $downstreamResponse->tokensToModify();
//
////return Array - you should try to resend the message to the tokens in the array
//        $downstreamResponse->tokensToRetry();

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