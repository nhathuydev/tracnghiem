<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 05/01/2018
 * Time: 15:46
 */

namespace App\Repository\Collection;


use App\Models\Collection;
use App\Repository\Question\QuestionRepository;
use App\Repository\Tag\TagRepository;
use Image;

class CollectionRepository implements CollectionInterface
{
    private $collection, $question, $tag;

    public function __construct(Collection $collection, QuestionRepository $questionRepository, TagRepository $tagRepository)
    {
        $this->collection = $collection;
        $this->question = $questionRepository;
        $this->tag = $tagRepository;
    }

    public function create(Array $attribute)
    {
        $uid = auth()->guard('api')->id();
        $attribute['user_id'] = $uid;
        $image = isset($attribute['image']) ? $attribute['image'] : false;
        if ($image) {
            $imageUrl = "collection-" . time() . ".jpg";
            Image::make($image)
                ->resize(500, null, function ($constraint) {
                    $constraint->aspectRatio();
                })
                ->save($imageUrl);
            $attribute['image'] = $imageUrl;
        }

        $result = $this->collection->create($attribute);

        $tags = isset($attribute['tags']) ? $attribute['tags'] : false;

        if($tags) {
            $t = [];
            foreach ($tags as $tag) {
                $t[] = $this->tag->getOrCreate($tag);
            }
            $result->tags()->syncWithoutDetaching($t);
        }
        return $result;
    }

    public function update(Array $attribute, $id)
    {
        $image = isset($attribute['image']) ? $attribute['image'] : false;
        if ($image) {
            $imageUrl = "collection-" . time() . ".jpg";
            Image::make($image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save($imageUrl);
//            })->storeAs('images', $imageUrl);
            $attribute['image'] = $imageUrl;
        } else {
            unset($attribute['image']);
        }
        return $this->get($id)->update($attribute);
    }

    public function paginate($size, $keyword = null, $publishOnly = false)
    {

        if (isset($keyword)) {
            switch ($keyword) {
                case 'new': {
                    $result = $this->collection->orderBy('created_at', 'desc');
                    break;
                }
                default: {
                    $result = $this->collection->orderBy('created_at', 'desc');
                }
            }
        } else {
            $result = $this->collection->orderBy('created_at', 'desc');
        }

        if ($publishOnly) {
            $result->where('isPublish', true);
        }

        return $result
            ->with(['user'])->withCount(['questions'])->paginate($size);
    }

    public function get($id, $isAdmin = false)
    {
        if ($isAdmin) {
            $result = $this->collection
                ->with(['questions' => function($q) {
                    $q->with('answers');
                }]);
        } else {
            $result = $this->collection
                ->with(['questions']);

//            TODO:
        }
        return $result->with(['tags', 'user'])
                        ->where('id', $id)
                        ->orWhere('slug', $id)
                        ->first();

    }

    public function publish($ids, $publish = true)
    {
        $isPublish = isset($publish) ? $publish : true;
        return $this->collection
            ->whereIn('id', $ids)
            ->update([
                'isPublish' => $isPublish,
            ]);
    }

    public function attachQuestion($collection_id, $question_ids, $attach = true)
    {
        $isAttach = isset($attach) ? $attach : true;
        $query = $this->get($collection_id)->questions();
        if ($isAttach) {
            return $query->syncWithoutDetaching($question_ids);
        }
        return $query->detach($question_ids);
    }

    public function createQuestion($collection_id, Array $attribute)
    {
        $question = $this->question->create($attribute);

        return $this->attachQuestion($collection_id, [$question->id]);
    }

    public function count()
    {
        return $this->collection->count();
    }

    public function search($keyword)
    {
        return $this->collection
                ->where('name', 'like', "%$keyword%")
                ->take(10)
                ->get();
    }

    public function generateForUser($collection_id)
    {
        $result = $this->collection->findOrFail($collection_id);

        if (!$result->isPublish) {
            return [];
        }
        return $result->questions;
    }
}