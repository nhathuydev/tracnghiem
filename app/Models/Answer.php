<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    protected $fillable = ['content', 'contentType'];
    protected $dateFormat = 'U';

    protected $casts = [
        'isCorrect' => 'boolean',
    ];
}
