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
use App\Models\CollectionUser;
use App\Repository\Collection\CollectionRepository;
use function Couchbase\defaultDecoder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

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
        $uid = auth()->guard('api')->id();
        $cu = CollectionUser::where('collection_id', $collection->id)->where('user_id', $uid)->first();


        if ($collection === null || $collection->isPublish === false) {
            return abort(ERROR_ANSWER_SHEET_INVALID_STATUS);
        } elseif ($collection->point > 0 && $cu->turn<=0) {
            return abort(ERROR_NOT_ENOUGH_TURN);
        }


        $payload = $collection->toArray();
        $payload['user_id'] = $uid;
        $payload['collection_id'] = $collection->id;
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

        // subtract turn
        if ($collection->point > 0) {
            if ($cu->turn - 1 === 0) {
                $cu->delete();
            } else {
                $cu->update(['turn' => $cu->turn-1]);
            }
        }

        if ($answerSheet->time > 0) {
            AnswerSheetJob::dispatch($answerSheet)
                ->delay(now()->addSeconds($answerSheet->time + 5));
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

    public function recentActivity()
    {
        $result = $this->answersheet
            ->with(['user'])
            ->take(10)
            ->orderByDesc('created_at')
            ->get();

        return $result;
    }
}