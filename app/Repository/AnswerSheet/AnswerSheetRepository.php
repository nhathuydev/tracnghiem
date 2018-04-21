<?php
/**
 * Created by PhpStorm.
 * User: mypop
 * Date: 3/21/18
 * Time: 10:28 PM
 */

namespace App\Repository\AnswerSheet;


use App\Jobs\AnswerSheetJob;
use App\Models\AnswerSheet;
use App\Models\AnswerSheetDetail;
use App\Repository\Collection\CollectionRepository;
use Illuminate\Http\Request;

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
        $payload['user_id'] = auth()->guard('api')->id();
        $answerSheet = $this->answersheet->create($payload);
        $answerSheetDetails = [];

        $collectionArrayable = $collection->questions->toArray();

        if ($collection->random_question_count > 0 && $collection->random_question_count<count($collectionArrayable)) {
            $selectedQuestionToAS = array_random($collectionArrayable, $collection->random_question_count);

        } else {
            $selectedQuestionToAS = $collectionArrayable;
        }
        foreach ($selectedQuestionToAS as $item) {
            $answerSheetDetails[]['question_id'] = $item['id'];
        }

        $answerSheet->question = $answerSheet->answerSheetDetail()->createMany($answerSheetDetails);
//
        if ($answerSheet->time > 0) {
            AnswerSheetJob::dispatch($answerSheet)->delay(now()->addSeconds($answerSheet->time + 60));
        }

        return $this->get($answerSheet->id);
    }

    public function updateStatus($id, $status)
    {
        $answerSheet = $answerSheet = $this->answersheet->findOrFail($id);
        if ($answerSheet->status === 0 && $status >= 0 && $status <=2) {
            $countCorrect = 0;
            if ($answerSheet->status === 0) {
                $countCorrect = $this->calculate($id);
            }
            return $answerSheet = $this->answersheet->findOrFail($id)->update([
                'status' => $status,
                'countCorrect' => $countCorrect,
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

    public function getHistoryByUser(Array $request)
    {
        $uid = auth()->guard('api')->user()->id;
        return $this->answersheet->where('user_id', $uid)->withCount('answerSheetDetail')
            ->orderBy('updated_at', 'desc')
            ->paginate(isset($request['size']) ? $request['size'] : 15);
    }

    public function answerQuestionInAnswerSheet($aid, $qid, $answers)
    {
        $result = $this->answersheet->findOrFail($aid);

        if ($result->status !== 0) return abort(ERROR_ANSWER_SHEET_INVALID_STATUS);

        return $this->answersheetDetail
            ->where('answer_sheet_id', $aid)
            ->where('question_id', $qid)
            ->update([
                'answers' => \GuzzleHttp\json_encode($answers, true),
            ]);
    }
}