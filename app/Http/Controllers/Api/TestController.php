<?php

namespace App\Http\Controllers\Api;

use App\Events\TestEvent;
use App\Jobs\TestJob;
use App\Repository\Question\QuestionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Queue\Jobs\Job;
use Illuminate\Support\Facades\DB;
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
        // dd(122);
//        $items = DB::table('question_tag')->where('tag_id', 7)->get();
//
//        foreach ($items as $item) {
//            DB::table('collection_question')->insert([
//                'collection_id' => 11,
//                'question_id' => $item->question_id,
//            ]);
//        }
//        event(new TestEvent(11));
//        broadcast(new TestEvent(111));
//        TestJob::dispatch();
//        \Redis::publish('quiz-app', json_encode([
//            'type' => 0,
//            'data' => [
//                'name' => 'HuyHhuy'
//            ]
//        ]));
    }

    public function postTest(Request $request)
    {
        Log::info($request->toArray());
    }

}
