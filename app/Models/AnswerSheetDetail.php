<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerSheetDetail extends Model
{
    protected $table = 'answer_sheet_detail';
    protected $fillable = ['question_id', 'answers'];

    protected $dateFormat = 'U';
    protected $casts = [
        'answers' => 'array',
    ];
    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
