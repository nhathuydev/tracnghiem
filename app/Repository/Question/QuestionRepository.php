<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 31/12/2017
 * Time: 11:53
 */

namespace App\Repository\Question;


use App\Models\Question;
use App\Repository\Answer\AnswerRepository;
use Illuminate\Http\Request;
use function PHPSTORM_META\type;

class QuestionRepository implements QuestionInterface
{
    private $question;
    private $answer;

    public function __construct(Question $question, AnswerRepository $answerRepository)
    {
        $this->question = $question;
        $this->answer = $answerRepository;
    }

    public function create(Array $attribute)
    {
        $result = $this->question->create($attribute);
        $answers = ($attribute['answers']);
        if(isset($answers)) {
            $ans = [];
            foreach ($answers as $answer) {
                $ans[] = $this->answer->getOrCreate($answer);
            }
//            $result->answers->
        }
        return $result;
    }

    public function update(Array $attribute, $id)
    {
        return $this->get($id)->update($attribute);
    }

    public function paginate(Request $request)
    {
        $query = $this->question;

        if (isset($request->keyword)) {
            $query = $query->where('content', 'like',  "%$request->keyword%")
                            ->orWhere('id', '=', "%$request->keyword%");
        }
        return $query->paginate();
    }

    public function get($id)
    {
        return $this->question->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->question->destroy($id);
    }
}