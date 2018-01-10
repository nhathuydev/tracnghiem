<?php

namespace App\Http\Controllers\Api;

use App\Repository\Answer\AnswerRepository;
use App\Repository\Collection\CollectionRepository;
use App\Repository\Question\QuestionRepository;
use App\Repository\Tag\TagRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    private $collection, $question, $answer, $tag;

    public function __construct(CollectionRepository $collectionRepository,
                                QuestionRepository $questionRepository,
                                AnswerRepository $answerRepository,
                                TagRepository $tagRepository)
    {
        $this->collection = $collectionRepository;
        $this->question = $questionRepository;
        $this->answer = $answerRepository;
        $this->tag = $tagRepository;
    }

    public function all()
    {
        return response()->success([
            'collection' => $this->collection->count(),
            'question' => $this->question->count(),
            'answer' => $this->answer->count(),
            'tag' => $this->tag->count(),
        ]);
    }
}
