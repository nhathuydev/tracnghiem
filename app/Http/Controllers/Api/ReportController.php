<?php

namespace App\Http\Controllers\Api;

use App\Repository\Answer\AnswerRepository;
use App\Repository\Collection\CollectionRepository;
use App\Repository\Question\QuestionRepository;
use App\Repository\Tag\TagRepository;
use App\Repository\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Redis;

class ReportController extends Controller
{
    private $collection, $question, $answer, $tag, $user;

    public function __construct(CollectionRepository $collectionRepository,
                                QuestionRepository $questionRepository,
                                AnswerRepository $answerRepository,
                                UserRepository $userRepository,
                                TagRepository $tagRepository)
    {
        $this->collection = $collectionRepository;
        $this->question = $questionRepository;
        $this->answer = $answerRepository;
        $this->tag = $tagRepository;
        $this->user = $userRepository;
    }

    public function all()
    {
        $currentOnline = Redis::get('currentConnection');
        $totalConnection = Redis::get('totalConnection');
        return response()->success([
            'collection' => $this->collection->count(),
            'question' => $this->question->count(),
            'answer' => $this->answer->count(),
            'tag' => $this->tag->count(),
            'user' => $this->user->count(),
            'currentOnline' => intval($currentOnline),
            'totalConnection' => $totalConnection,
        ]);
    }
}
