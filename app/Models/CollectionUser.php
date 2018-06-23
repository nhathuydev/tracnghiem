<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CollectionUser extends Model
{
    protected $fillable = ['collection_id', 'user_id', 'turn'];
}
