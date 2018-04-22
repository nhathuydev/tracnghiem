<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 10/01/2018
 * Time: 14:55
 */

namespace App\Repository\User;


use App\Models\Provider;
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
        }
        return $result;
    }

    public function paginate($size)
    {
        return $this->user
            ->with(['providers'])
            ->paginate($size);
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
}