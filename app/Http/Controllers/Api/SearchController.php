<?php

namespace App\Http\Controllers\Api;

use App\Repository\Collection\CollectionRepository;
use App\Repository\Tag\TagRepository;
use Elasticsearch\ClientBuilder;
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
        foreach ($tags->data as $item) {
            $item->type = 1; // 0-collection 1-tag
        }

        $collections->name = 'Đề Thi';
        $collections->data = $this->collection->search($keyword);
        foreach ($collections->data as $item) {
            $item->type = 0;
        }

        $data[] = $collections;
        $data[] = $tags;
        return response()->success($data);
    }
}
