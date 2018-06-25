<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class AnswerSheet extends Model
{
    protected $table = 'answer_sheet';
    protected $fillable = ['user_id', 'name', 'status', 'time', 'countCorrect', 'point_ladder', 'point',];
    protected $dateFormat = 'U';

    public function answerSheetDetail()
    {
        return $this->hasMany(AnswerSheetDetail::class, 'answer_sheet_id');
    }

    public function user() {
        return $this->belongsTo(User::class)->addSelect(['id', 'name']);
    }
}
