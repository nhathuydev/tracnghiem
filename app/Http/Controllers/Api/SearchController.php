<?php

namespace App\Http\Controllers\Api;

use App\Repository\Collection\CollectionRepository;
use App\Repository\Tag\TagRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SearchController extends Controller
{
    private $collection, $tag;

    public function __construct(CollectionRepository $collectionRepository, TagRepository $tagRepository)
    {
        $this->collection = $collectionRepository;
        $this->tag = $tagRepository;
    }

    public function searchAll(Request $request)
    {
        $keyword = $request->keyword;

        $tags = new \stdClass();
        $collections = new \stdClass();

        $tags->name = 'Nhãn';
        $tags->data = $this->tag->search($keyword);

        $collections->name = 'Đề Thi';
        $collections->data = $this->collection->search($keyword);
        $data[] = $tags;
        $data[] = $collections;
        return response()->success($data);
    }
}
