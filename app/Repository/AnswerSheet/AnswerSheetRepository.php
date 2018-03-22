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


        $payload = $collection->toArray();
        $payload['user_id'] = 1;
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
}