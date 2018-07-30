<?php

namespace App;

use App\Models\AddPointRequest;
use App\Models\Provider;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'isAdmin', 'isActive', 'isBan', 'phone', 'address', 'avatar',
        'bio', 'point',
    ];
    protected $dateFormat = 'U';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'admin',
    ];

    protected $casts = ['isAdmin' => 'bool', 'isActive' => 'bool', 'isBan' => 'bool'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function providers()
    {
        return $this->belongsToMany(Provider::class);
    }

    public function addPointRequest()
    {
        return $this->hasMany(AddPointRequest::class);
    }
}
