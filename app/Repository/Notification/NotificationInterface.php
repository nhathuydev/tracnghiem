<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 4/23/18
 * Time: 8:43 AM
 */

namespace App\Repository\Notification;


interface NotificationInterface
{
    public function create(Array $payload);
    public function markAsRead($nid);
}