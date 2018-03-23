<?php
/**
 * Created by PhpStorm.
 * User: huy
 * Date: 31/12/2017
 * Time: 12:31
 */

namespace App\Repository\Tag;


use App\Models\Tag;
use Illuminate\Http\Request;

class TagRepository implements TagInterface
{
    private $tag;

    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
    }

    public function create(Array $attribute)
    {
        $background_colors = array('ff4444', 'CC0000', 'ffbb33', 'ffbb33', '00C851', '007E33', '33b5e5', '0099CC');

        $attribute['color'] = $background_colors[array_rand($background_colors)];
        return $this->tag->create($attribute);
    }
    public function getOrCreate($param)
    {
        $result = $this->tag->where('id', $param)
            ->orWhere('name', $param)->first();

        if (!isset($result)){
            $result = $this->create([
                'name' => $param,
            ]);
        }

        return $result->id;
    }
    public function update(Array $attribute, $id)
    {
        return $this->get($id)->update($attribute);
    }

    public function paginate(Request $request)
    {
        $query = $this->tag;

        if (isset($request->keyword)) {
            $query = $query->where('name', 'like',  "%$request->keyword%")
                            ->orWhere('id', '=', $request->keyword);
        }
        return $query->paginate($request->size);
    }

    public function get($id)
    {
        $tag = $this->tag
                        ->with('collections')
                        ->where('id', $id)
                        ->orWhere('slug', $id)->first();
        return $tag;
    }

    public function delete($id)
    {
        return $this->tag->destroy($id);
    }
    public function count()
    {
        return $this->tag->count();
    }

    public function search($keyword)
    {
        return $this->tag->where('name', 'like', "%$keyword%")
                            ->take(10)
                            ->get();
    }

    public function collections($tagName)
    {
        $tag = $this->tag->where('slug', $tagName)->first();

        if (!$tag) return null;
        return $tag->collections;
    }
}