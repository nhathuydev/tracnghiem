<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FeatureCollection extends Model
{
    protected $table = 'feature_collections';
    protected $fillable = ['collection_id', 'type', 'index'];
    public $timestamps = false;

    public function collection()
    {
        return $this->belongsTo(Collection::class);
    }
}
