<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\CollectionRequest;
use App\Repository\Collection\CollectionRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    private $collection;

    public function __construct(CollectionRepository $repository)
    {
        $this->collection = $repository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->success($this->collection->paginate($request->size, $request->keyword));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CollectionRequest $request)
    {
        return response()->success($this->collection->create($request->toArray()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return response()->success($this->collection->get($id, true));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CollectionRequest $request, $id)
    {
        return response()->success($this->collection->update($request->toArray(), $id));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function publish(CollectionRequest $request)
    {
        return response()->success($this->collection->publish($request->ids, $request->publish));
    }

    public function questionAttach(CollectionRequest $request)
    {
        return response()->success(
            $this->collection->attachQuestion($request->collection_id, $request->question_ids, $request->attach)
        );
    }

    public function questionCreate(CollectionRequest $request)
    {
        return response()->success(
            $this->collection->createQuestion($request->collection_id, $request->toArray())
        );
    }

    public function getCollectionForUser(Request $request)
    {
        return response()->success($this->collection->paginate($request->size, $request->keyword, true));
    }

    public function getCollectionDetailForUser($id)
    {
        return response()->success($this->collection->get($id, false));
    }

    public function generateCollectionForUser(Request $request)
    {
        return response()->success($this->collection->generateForUser($request->id));
    }

    public function search(Request $request)
    {
        return response()->success($this->collection->search($request->keyword));
    }

    public function bookmark($collection_id)
    {
        return response()->success($this->collection->bookmark($this->collection));
    }

    public function getBookmark()
    {
        return response()->success($this->collection->getBookmark());
    }
}
