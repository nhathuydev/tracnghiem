<?php
Route::domain(env('APP_API_URL)'))->namespace('Api')->group(function () {
    Route::get('/test', 'TestController@test');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login}', 'AuthController@login')->name('auth.login');
        Route::post('login/{driver}', 'AuthController@handleProviderCallback')->name('auth.loginSocial');
        Route::post('register', 'AuthController@register')->name('auth.register');
    });

    Route::group(['middleware' => 'api', 'prefix' => 'user'], function () {
        Route::get('collection', 'CollectionController@getCollectionForUser');
        Route::get('collection/{id}', 'CollectionController@getCollectionDetailForUser');
//        Route::get('tag/{id}/collections', 'TagController@getCollectionByTagForUser');
        Route::get('tag/{id}/collections', 'TagController@show');
        Route::get('tags', 'TagController@getTagsForUser');
        Route::get('search', 'SearchController@searchAll');

        // answer sheet
        Route::group(['middleware' => 'apiUser'], function () {
            Route::post('answer-sheet/{id}', 'AnswerSheetController@generate');
            Route::post('answer-question', 'AnswerSheetController@answerOneQuestion');

            Route::get('answer-sheet/{id}', 'AnswerSheetController@detail');
            Route::get('answer-sheets', 'AnswerSheetController@getList');
            Route::post('answer-sheet-update-status/{id}', 'AnswerSheetController@updateStatus');
        });

//        Route::get('answer-sheet-result/{id}', 'AnswerSheetController@getResult');
//         Route::post('response-answer-sheet', 'AnswerSheetController@updateAnswerSheet'); removed
    });
    // api for admin
    Route::group(['middleware' => 'apiAdmin'], function () {

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


//# 127.0.0.1	tnm.dev
//# 127.0.0.1	admin.tnm.dev
//# 127.0.0.1	api.tnm.dev
