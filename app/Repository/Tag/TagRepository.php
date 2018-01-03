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
        return $this->tag->create($attribute);
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
                ->orWhere('id', '=', "%$request->keyword%");
        }
        return $query->paginate();
    }

    public function get($id)
    {
        return $this->tag->findOrFail($id);
    }

    public function delete($id)
    {
        return $this->tag->destroy($id);
    }

}