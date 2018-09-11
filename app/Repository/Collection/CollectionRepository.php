<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 05/01/2018
 * Time: 15:46
 */

namespace App\Repository\Collection;


use App\Models\AnswerSheet;
use App\Models\Bookmark;
use App\Models\Collection;
use App\Models\CollectionUser;
use App\Repository\AnswerSheet\AnswerSheetRepository;
use App\Repository\Question\QuestionRepository;
use App\Repository\Tag\TagRepository;
use function Composer\Autoload\includeFile;
use Illuminate\Support\Facades\Auth;
use Image;

class CollectionRepository implements CollectionInterface
{
    private $collection, $question, $tag, $bookmark, $collectionUser, $answersheet;

    public function __construct(Collection $collection, QuestionRepository $questionRepository, TagRepository $tagRepository, Bookmark $bookmark, CollectionUser $collectionUser)
    {
        $this->collection = $collection;
        $this->question = $questionRepository;
        $this->tag = $tagRepository;
        $this->bookmark = $bookmark;
        $this->collectionUser = $collectionUser;
//        $this->answersheet = $answerSheetRepository;
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
        return $this->get($id, false)->update($attribute);
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
            ->with(['user', 'tags'])->withCount(['questions'])->paginate($size);
    }

    public function collectionOfUser($size)
    {
        return $this->collection->where('user_id', Auth::guard('api')->id())
            ->with(['user', 'tags'])->withCount(['questions'])->paginate($size);
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

        $result = $result->with(['tags', 'user'])
            ->withCount('questions')
            ->where('id', $id)
            ->orWhere('slug', $id)
            ->firstOrFail();

        $user = auth()->guard('api')->user();

        if ($user === null || !$user->isAdmin) {
            $uid = $user && $user->id || 0;
            $result->join = ($flag = $this->collectionUser->where('user_id', $uid)->where('collection_id', $result->id)->first()) && $flag->turn > 0 || $result->point===0;
            $result->availableTurn = $flag && $flag->turn > 0 ? $flag->turn : 0;
        }

        return $result;

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

    public function bookmark($collection_id, $action=1)
    {
        // action: 0-remove; 1-add
        $uid = auth()->guard('api')->id();
        if (!$uid) return null;

        if (intval($action) === 1) {
            $this->bookmark->create([
                'user_id' => $uid,
                'collection_id' => intval($collection_id),
            ]);
        } else {
            $this->bookmark
                ->where('collection_id', $collection_id)
                ->where('user_id', $uid)
                ->delete();
        }
        return $this->getBookmark();
    }

    public function getBookmark()
    {
        $uid = auth()->guard('api')->id();
        if (!$uid) return [];

        $result = $this->bookmark
            ->with('collections')
            ->where('user_id', $uid)
            ->get();

        $formatResult = [];
        foreach ($result as $bookmarkItem) {
            // $formatResult[] = $bookmarkItem->collection_id;
            $formatResult[] = $bookmarkItem;
        }
        return array_unique($formatResult);
    }

    public function buyCollection($collection_id)
    {
        $collection = $this->get($collection_id);

        if (!!!$collection || !$collection->isPublish) return abort(ERROR_ANSWER_SHEET_INVALID_STATUS, 'invalid collection');

        $user = Auth::guard('api')->user();

        $flag = $user->point - $collection->point;

        if ($flag < 0) abort(ERROR_NOT_ENOUGH_POINT, 'not enough point');

        $cu = $this->collectionUser->where('user_id', $user->id)->where('collection_id', $collection_id)->first();

        $user->update(['point' => $flag]);
        if (!!!$cu) {
            return $this->collectionUser->create([
                'collection_id' => $collection->id,
                'user_id'       => $user->id,
                'turn'          => $collection->turn,
            ]);
        } else {
            $cu->update([
                'turn' => $cu->turn + $collection->turn,
            ]);

            return $cu;
        }
    }

    public function topUser($cid)
    {
        $query = AnswerSheet::where('collection_id', $cid)
            ->where('status', ANSWER_SHEET_DONE)
            ->with(['user'])
            ->take(10)
            ->orderBy('countCorrect', 'desc')
            ->get(['countCorrect', 'user_id', 'created_at', 'updated_at']);

        return $query;
    }
}