<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = ['content', 'explain', 'multiChoice', 'extraContent', 'extraContentType'];
    protected $appends = ['extraContent'];
    protected $dateFormat = 'U';

    public function answers()
    {
        return $this->belongsToMany(Answer::class)->withPivot('isCorrect');
    }
    public function answersWithoutCorrect()
    {
        return $this->belongsToMany(Answer::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getExtraContentAttribute()
    {
        return isset($this->attributes['extraContent']) ? url($this->attributes['extraContent']) : null;
    }
}
