<?php

namespace App\Http\Controllers\Api;

use App\Repository\AnswerSheet\AnswerSheetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ActivityController extends Controller
{
    private $answerSheet;

    public function __construct(AnswerSheetRepository $answerSheetRepository)
    {
        $this->answerSheet = $answerSheetRepository;
    }
    public function index()
    {
        return response()->success($this->answerSheet->recentActivity());
    }
}
