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
use App\Repository\Tag\TagRepository;
use Illuminate\Http\Request;
use Image;

class QuestionRepository implements QuestionInterface
{
    private $question;
    private $answer;
    private $tag;

    public function __construct(Question $question, AnswerRepository $answerRepository, TagRepository $tagRepository)
    {
        $this->question = $question;
        $this->answer = $answerRepository;
        $this->tag = $tagRepository;
    }

    public function create(Array $attribute)
    {
        $image = isset($attribute['image']) ? $attribute['image'] : false;
        if ($image) {
            $imageUrl = "question-" . time() . ".jpg";
            Image::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imageUrl);
            $attribute['extraContent'] = $imageUrl;
            $attribute['extraContentType'] = 1; // null: nothing, 1: image, 2: video
        }

        $result = $this->question->create($attribute);
        $answers = isset($attribute['answers']) ? $attribute['answers'] : false;
        $tags = isset($attribute['tags']) ? $attribute['tags'] : false;

        if($tags) {
            $t = [];
            foreach ($tags as $tag) {
                $t[] = $this->tag->getOrCreate($tag);
            }
            $result->tags()->syncWithoutDetaching($t);
        }
        if($answers) {
            $ans = [];
            foreach ($answers as $answer) {
                $ans[$this->answer->getOrCreate($answer['id'])] = ['isCorrect' => $answer['isCorrect']];
            }
            $result->answers()->syncWithoutDetaching($ans);
        }

        return $result;
    }

    public function update(Array $attribute, $id)
    {
        $image = isset($attribute['image']) ? $attribute['image'] : false;
        if ($image) {
            $imageUrl = "question-" . time() . ".jpg";
            Image::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imageUrl);
            $attribute['extraContent'] = $imageUrl;
            $attribute['extraContentType'] = 1; // null: nothing, 1: image, 2: video
        }
        return $this->get($id)->update($attribute);
    }

    public function paginate(Request $request)
    {
        $query = $this->question;

        if (isset($request->keyword)) {
            $query = $query->where('content', 'like',  "%$request->keyword%")
                            ->orWhere('id', '=', "%$request->keyword%");
        }
        return $query->with('tags')
            ->withCount('answers')
            ->orderBy('id', 'desc')
            ->paginate($request->size);
    }

    public function get($id)
    {
        return $this->question->with('answers')->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->question->destroy($id);
    }

    public function count()
    {
        return $this->question->count();
    }
}