<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name', 'description', 'color'];
    protected $dateFormat = 'U';
    protected $hidden = ['pivot'];

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = str_slug($value);
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }
}
