<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['user_id', 'title', 'message', 'read', 'type', 'data'];
    protected $dateFormat = 'U';
    protected $casts = [
        'read' => 'bool',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUpdatedAtColumn()
    {
        return null;
    }
}
