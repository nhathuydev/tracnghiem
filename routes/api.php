<?php

Route::group([], function () {
    Route::get('/test', 'TestController@test');
});
Route::domain(env('APP_API_URL)'))->namespace('Api')->group(function () {


    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('auth.login');
//        Route::post('facebook', 'AuthController@loginFacebook')->name('auth.loginFacebook');
//        Route::get('facebook', 'AuthController@loginFacebook')->name('auth.loginFacebook');
        Route::post('register', 'AuthController@register')->name('auth.register');
    });

    Route::group(['middleware' => 'api', 'prefix' => 'user'], function () {
        Route::get('collection', 'CollectionController@getCollectionForUser');
        Route::get('collection/{id}', 'CollectionController@getCollectionDetailForUser');
        Route::get('tag/{id}/collections', 'TagController@getCollectionByTagForUser');
        Route::get('tags', 'TagController@getTagsForUser');
        Route::get('search', 'SearchController@searchAll');
//        Route::post('collection', 'CollectionController@generateCollectionForUser');
        Route::post('answer-sheet/{id}', 'AnswerSheetController@generate');
        Route::get('answer-sheet/{id}', 'AnswerSheetController@detail');
        Route::post('answer-sheet-update-status/{id}', 'AnswerSheetController@updateStatus');

    });
    // api for admin
    Route::group(['middleware' => 'apiAdmin'], function () {
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
