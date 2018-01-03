<?php

namespace App\Http\Controllers\Api;

use App\Repository\Question\QuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    private $question;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->question = $questionRepository;
    }
    public function __invoke()
    {
//        $result = $this->question->get(3)->answers;
        $result = $this->question->get(3);
        return response()->json($result);
    }

}
