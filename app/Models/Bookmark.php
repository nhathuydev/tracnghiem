<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bookmark extends Model
{
    protected $table = 'bookmark';
    protected $fillable = ['user_id', 'collection_id'];
    public $timestamps = false;

    public function collections()
    {
        return $this->belongsTo(Collection::class, 'collection_id')->addSelect(['id']);
    }

}
