<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 31/12/2017
 * Time: 11:01
 */
namespace App\Repository\Answer;
use App\Models\Answer;

class AnswerRepository implements AnswerInterface
{
    private $answer;

    public function __construct(Answer $answer)
    {
        $this->answer = $answer;
    }

    public function create(Array $attribute)
    {
        return $this->answer->create($attribute);
    }
    public function getOrCreate($param)
    {
        $result = $this->answer->where('id', $param)
                                ->orWhere('content', $param)->first();

        if (!isset($result)){
            $result = $this->create([
                'content' => $param,
            ]);
        }

        return $result->id;
    }

    public function update(Array $attribute, $id)
    {
        return $this->get($id)->update($attribute);
    }

    public function paginate($size, $keyword)
    {
        $query = $this->answer;
        if(isset($keyword)) {
            $query = $query->where('content', 'like', "%$keyword%")
                            ->OrWhere('id', 'like', "%$keyword%");
        }
        return $query->paginate($size);
    }
    public function get($id)
    {
        return $this->answer->findOrFail($id);
    }
}