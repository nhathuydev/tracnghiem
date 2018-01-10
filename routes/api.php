<?php

Route::domain(env('APP_API_URL)'))->namespace('Api')->group(function () {
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('register', 'AuthController@register')->name('auth.register');
    });

    Route::group(['middleware' => 'api'], function () {
//        Route::
    });
    // api for admin
    Route::group([], function () {
        Route::get('/test', 'TestController@test');

        Route::group(['prefix' => 'report'], function () {
            Route::get('all', 'ReportController@all');
        });
        Route::resource('answer', 'AnswerController');
        Route::post('question/answer-attach', 'QuestionController@answerAttach')->name('question.answerAttach');
        Route::resource('question', 'QuestionController');
        Route::resource('tag', 'TagController');
        Route::post('collection/publish', 'CollectionController@publish')->name('collection.publish');
        Route::post('collection/question-create', 'CollectionController@questionCreate')->name('collection.questionCreate');
        Route::post('collection/question-attach', 'CollectionController@questionAttach')->name('collection.questionAttach');
        Route::resource('collection', 'CollectionController');
    });
});
/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/


//# 127.0.0.1	tnm.dev
//# 127.0.0.1	admin.tnm.dev
//# 127.0.0.1	api.tnm.dev
