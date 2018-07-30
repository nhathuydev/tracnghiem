<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddPointRequest extends Model
{
    protected $table = 'add_point_requests';
    protected $fillable = ['user_id', 'note', 'point', 'isSuccess'];
    protected $dateFormat = 'U';
    protected $casts = [
      'isSuccess' => 'bool',
    ];

}
