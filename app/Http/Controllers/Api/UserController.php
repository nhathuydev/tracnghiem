<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserRequest;
use App\Repository\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $user;

    public function __construct(UserRepository $userRepository)
    {
        $this->user = $userRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response()->success($this->user->paginate($request->size));
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
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'isAdmin' => 'bool',
        ]);

        $currentUserId = Auth::guard('api')->id();
        if ($request->isAdmin && $currentUserId !== 1) {
            abort(500);
        }

        return response()->success($this->user->create($request->toArray()));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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

    public function search(Request $request)
    {
        return response()->success($this->user->search($request->keyword));
    }

    public function updateProfile(Request $request)
    {
        $uid = auth()->guard('api')->id();
        return response()->success($this->user->update($request->toArray(), $uid));
    }

    public function getProfile()
    {
        return response()->success($this->user->get(auth()->guard('api')->id()));
    }

    public function addPoint(Request $request)
    {
        return response()->success($this->user->updateCoin($request->point, $request->users, $request->title, $request->message));
    }
}
