<?php

use Illuminate\Http\Request;
Route::domain('api.45.32.127.139')->namespace('Api')->group(function () {
    Route::get('/test', 'TestController');
    Route::resource('answer', 'AnswerController');
    Route::resource('question', 'QuestionController');
    Route::resource('tag', 'TagController');
});
/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


//# 127.0.0.1	tnm.dev
//# 127.0.0.1	admin.tnm.dev
//# 127.0.0.1	api.tnm.dev
