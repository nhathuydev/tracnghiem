<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $fillable = ['name', 'description', 'image', 'time', 'isPublish', 'random_question_count', 'point_ladder', 'user_id', 'point',
        'turn',
    ];

    protected $hidden = ['user_id'];

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
        return $this->belongsToMany(Tag::class)->addSelect(['name', 'color', 'slug']);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->addSelect(['id', 'name', 'avatar', 'bio']);
    }

//    public function bookmark()
//    {
//        return $this->belongsToMany(Bookmark::class, 'bookmark', 'collection_id');
//    }
}
