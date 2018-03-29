<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\AnswerRequest;
use App\Repository\AnswerSheet\AnswerSheetRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AnswerSheetController extends Controller
{
    private $answerSheet;

    public function __construct(AnswerSheetRepository $answerSheetRepository)
    {
        $this->answerSheet = $answerSheetRepository;
    }
    public function generate($id)
    {
        return response()->success($this->answerSheet->createByCollectionId($id));
    }

    public function detail($id)
    {
        return response()->success($this->answerSheet->get($id));
    }

    public function updateStatus($id, Request $request)
    {
        return response()->success($this->answerSheet->updateStatus($id, $request->status));
    }

    public function getResult($id)
    {
        return response()->success($this->answerSheet->get($id));
    }
    public function updateAnswerSheet(AnswerRequest $request) // answer answer sheet's questions
    {
        return response()->success($this->answerSheet->generateResult($request->aid, $request->answers));
    }
}
