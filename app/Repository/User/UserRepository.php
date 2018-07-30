<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 10/01/2018
 * Time: 14:55
 */

namespace App\Repository\User;


use App\Models\AddPointRequest;
use App\Models\Provider;
use App\Repository\Notification\NotificationInterface;
use App\User;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class UserRepository implements UserInterface
{
    private $user, $notificationRepo;

    public function __construct(User $user, NotificationInterface $notification)
    {
        $this->user = $user;
        $this->notificationRepo = $notification;
    }

    public function create(Array $attribute)
    {
        $result = $this->user->create($attribute);
        if (isset($attribute['provider_name'])) {
            $provider_id = Provider::where('name', $attribute['provider_name'])->first()->id;
             $result->providers()->syncWithoutDetaching([$provider_id => ['token' => $attribute['provider_token']]]);
//            $result->providers()->attach([$provider_id => ['token' => $attribute['provider_token']]]);
        }
        return $result;
    }

    public function getOrCreate($attribute)
    {
        $user = $this->get($attribute['email']);
        if (!isset($user)) {
            return $this->create($attribute);
        }
        return $user;
    }

    public function update(Array $attribute, $id)
    {
        $result = $this->get($id);
        if (isset($attribute['provider_name'])) {
            $provider_id = Provider::where('name', $attribute['provider_name'])->first()->id;
            $result->providers()->syncWithoutDetaching([$provider_id => ['token' => $attribute['provider_token']]]);
//            $result->providers()->attach([$provider_id => ['token' => $attribute['provider_token']]]);
        } else {
            if (isset($attribute['avatar'])) {
                $image = $attribute['avatar'];
                $imageUrl = "user-" . $id . '-' . time() . ".jpg";
                Image::make($image)
                    ->resize(500, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save($imageUrl);

                $attribute['avatar'] = $imageUrl;
            }

            $result->update($attribute);
        }
        return $result;
    }

    public function paginate($size)
    {
        return $this->user
            ->with(['providers'])
            ->paginate($size);
    }

    public function get($id, $with = null)
    {
        $result = $this->user->where('id', $id)
            ->orWhere('email', $id);
        if ($with !== null) {
            $result = $result->with($with);
        }

        $result = $result->first();

        return $result;
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function count()
    {
        return $this->user->count();
    }

    public function search($keyword)
    {
        return $this->user->where('id', $keyword)
                        ->orWhere('name', 'like', "%$keyword%")
                        ->orWhere('email', 'like', "%$keyword%")
                        ->take(10)
                        ->get();
    }

    public function updateCoin($coin, $uids = [], $notif_title=null, $notif_message=null)
    {
        $count_success = 0;
        foreach ($uids as $uid) {
            $user = $this->get($uid);
            if ($this->update(['point' => $user->point + $coin], $uid)) {
                $count_success++;
                if (isset($notif_title)) {
                    $payload = array(
                        'type' => NOTIFICATION_SEND_POINT,
                        'data' => null,
                        'user_id' => $uid,
                        'title' => $notif_title,
                        'message' => $notif_message,
                    );
                    $this->notificationRepo->create($payload);
                }
            }
        }

        return $count_success;
    }

    public function ban($userId, $ban = true)
    {
        $uid = auth()->guard('api')->id();

        if ($uid !== 1) {
            abort(500);
        }
        return $this->update(['isBan' => $ban], $userId);
    }

    public function requestAddPoint($point = 0, $note)
    {
        return auth()->guard('api')->user()
            ->addPointRequest()
            ->create([
                'point' => $point,
                'note'  => $note,
            ]);
    }

    public function checkRequestAddPoint($raid)
    {
        $requestAddPoint = AddPointRequest::findOrFail($raid);
        $uid = auth()->guard('api')->id();

        if ($requestAddPoint->isSuccess || $uid !== $requestAddPoint->user_id) {
            abort(500);
        }

        $point = $requestAddPoint->point;
        $notifTitle = 'Thanh toán thành công';
        $notifMessage= 'Chúc mừng bạn đã nạp thành công ' . $point . ' xu';
        $requestAddPoint->update(['isSuccess' => true]);
        return $this->updateCoin($point, [$uid], $notifTitle, $notifMessage);
    }
}