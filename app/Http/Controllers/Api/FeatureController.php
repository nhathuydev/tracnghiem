<?php

namespace App\Http\Controllers\Api;

use App\Repository\FeatureCollection\FeatureCollectionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FeatureController extends Controller
{
    private $fc;

    public function __construct(FeatureCollectionRepository $featureCollectionRepository)
    {
        $this->fc = $featureCollectionRepository;
    }

    public function add(Request $request)
    {
        return response()->success($this->fc->add($request->toArray()));
    }

    public function remove(Request $request)
    {
        return response()->success($this->fc->remove($request->id));
    }

    public function list(Request $request)
    {
        return response()->success($this->fc->getByType($request->type));
    }
}
