<?php

namespace App\Http\Controllers\Api;

use App\Jobs\TestJob;
use App\Repository\Question\QuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    private $question;

    public function __construct(QuestionRepository $questionRepository)
    {
        $this->question = $questionRepository;
    }
    public function test(Request $request)
    {
//        $result = $this->question->get(3)->answers;
//        $result = $this->question->get(3);
        Log::info('Dispatch job start at: ' . now()->timestamp);
        dd(TestJob::dispatch(['meme' => 1])->delay(now()->addSeconds(10)));
        return response()->success(auth()->guard('api')->user());
    }

}
