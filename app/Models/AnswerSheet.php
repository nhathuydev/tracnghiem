<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnswerSheet extends Model
{
    protected $table = 'answer_sheet';
    protected $fillable = ['user_id', 'name', 'status', 'time', 'countCorrect', 'point_ladder'];
    protected $dateFormat = 'U';

    public function answerSheetDetail()
    {
        return $this->hasMany(AnswerSheetDetail::class, 'answer_sheet_id');
    }

}
