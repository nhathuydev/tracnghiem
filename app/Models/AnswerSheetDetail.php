<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerSheetDetail extends Model
{
    protected $table = 'answer_sheet_detail';
    protected $fillable = ['question_id'];

    protected $dateFormat = 'U';

    public function question()
    {
        return $this->hasOne(Question::class, 'id', 'question_id');
    }
}
