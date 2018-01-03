<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['content', 'extraContent', 'contentType'];
    protected $dateFormat = 'U';

    public function answers()
    {
        return $this->belongsToMany(Answer::class);
    }
}
