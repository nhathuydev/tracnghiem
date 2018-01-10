<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'description', 'image', 'time', 'isPublish'];
    protected $dateFormat = 'U';
    protected $casts = [
        'isPublish' => 'boolean',
    ];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function questions()
    {
        return $this->belongsToMany(Question::class);
    }

    public function getImageAttribute($value)
    {
        if (!isset($value)) return null;
        return url($value);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
