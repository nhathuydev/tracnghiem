<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 3/21/18
 * Time: 10:28 PM
 */

namespace App\Repository\AnswerSheet;


use App\Models\AnswerSheet;
use App\Models\AnswerSheetDetail;
use App\Repository\Collection\CollectionRepository;

class AnswerSheetRepository implements AnswerSheetInterface
{

    private $answersheet, $collection, $answersheetDetail;

    public function __construct(AnswerSheet $answerSheet, CollectionRepository $collectionRepository, AnswerSheetDetail $answerSheetDetail)
    {
        $this->answersheet = $answerSheet;
        $this->answersheetDetail = $answerSheetDetail;
        $this->collection = $collectionRepository;
    }

    public function get($id)
    {
        return $this->answersheet->with(['answerSheetDetail' => function($q) {
            $q->with(['question' => function($a) {
                $a->with('answersWithoutCorrect');
            }]);
        }])->findOrFail($id);
    }

    public function createByCollectionId($collectionId)
    {
        $collection = $this->collection->get($collectionId);
        if ($collection === null || $collection->isPublish === false) {
            return null;
        }

//        dd(auth()->guard())
        $payload = $collection->toArray();
        $payload['user_id'] = auth()->guard('api')->id();
        $answerSheet = $this->answersheet->create($payload);
        $answerSheetDetails = [];
        foreach ($collection->questions->toArray() as $item) {
            $answerSheetDetails[]['question_id'] = $item['id'];
        }
//
        $answerSheet->question = $answerSheet->answerSheetDetail()->createMany($answerSheetDetails);
        return $this->get($answerSheet->id);
    }

    public function updateStatus($id, $status)
    {
        $answerSheet = $answerSheet = $this->answersheet->findOrFail($id);
        if ($answerSheet->status === 0 && $status >= 0 && $status <=2) {
            return $answerSheet = $this->answersheet->findOrFail($id)->update([
                'status' => $status,
            ]);
        }
        return false;
    }

    public function generateResult($aid, Array $answers)
    {
        $result = $this->answersheet->findOrFail($aid);

        if ($result->status !== 0) return null;

        // update answer sheet detail
        foreach ($answers as $item) {
            $this->answersheetDetail
                ->where('answer_sheet_id', $aid)
                ->where('question_id', $item['qid'])
                ->update([
                    'answers' => \GuzzleHttp\json_encode($item['as'], true),
                ]);
        }

        $countCorrect = $this->calculate($aid);
        $result->update([
            'countCorrect' => $countCorrect,
            'status'        => 1,
        ]);
        return $result;
    }

    private function calculate($aid) {
        $ass = $this->answersheetDetail
            ->where('answer_sheet_id', $aid)
            ->with(['question' => function($q) {
                $q->with('answers');
            }])
            ->get();

        $count = 0;
        foreach ($ass as $item) {
            $userAnsewers = $item->answers;

            if ($userAnsewers === null) {
                continue;
            }

            $correctAnswers = array_filter($item->question->answers->toArray(), function ($var) {
                return ($var['pivot']['isCorrect'] === 1);
            });

            $flagCorrect = true;
            foreach ($correctAnswers as $ca) {
                if (!in_array($ca['id'], $userAnsewers)) {
                    $flagCorrect = false;
                }
            }
            if ($flagCorrect) $count++;
        }
        return $count;
    }

    public function getAnswerSheetDetailById($aid)
    {

    }

}