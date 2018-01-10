<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 10/01/2018
 * Time: 14:55
 */

namespace App\Repository\User;


use App\User;
use Illuminate\Http\Request;

class UserRepository implements UserInterface
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function create(Array $attribute)
    {
        return $this->user->create($attribute);
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
        // TODO: Implement update() method.
    }

    public function paginate(Request $request)
    {
        // TODO: Implement paginate() method.
    }

    public function get($id)
    {
        return $this->user->where('id', $id)
                            ->orWhere('email', $id)
                            ->first();
    }

    public function delete($id)
    {
        // TODO: Implement delete() method.
    }

    public function count()
    {
        // TODO: Implement count() method.
    }


}