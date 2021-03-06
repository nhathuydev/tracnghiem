<?php
Route::domain(env('APP_API_URL)'))->namespace('Api')->group(function () {
    Route::get('/test', 'TestController@test');
    Route::post('/test', 'TestController@postTest');

    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'AuthController@login')->name('auth.login');
        Route::post('login/{driver}', 'AuthController@handleProviderCallback')->name('auth.loginSocial');
        Route::post('register', 'AuthController@register')->name('auth.register');
    });

    Route::group(['middleware' => 'api', 'prefix' => 'user'], function () {
        Route::group(['middleware' => 'apiUser'], function () {
            Route::post('answer-sheet/{id}', 'AnswerSheetController@generate');
            Route::post('answer-question', 'AnswerSheetController@answerOneQuestion');

            Route::get('answer-sheet/{id}', 'AnswerSheetController@detail');
            Route::get('answer-sheets', 'AnswerSheetController@getList');
            Route::post('answer-sheet-update-status/{id}', 'AnswerSheetController@updateStatus');

            Route::get('notifications', 'NotificationController@list');
            Route::post('notifications', 'NotificationController@markRead');

            Route::get('profile', 'UserController@getProfile');
            Route::post('profile/update', 'UserController@updateProfile');

            // buy collection
            Route::post('collection/buy', 'CollectionController@buyCollection');

            Route::post('collection/{id}/bookmark', 'CollectionController@bookmark');
            Route::get('bookmark', 'CollectionController@getBookmark');

            // Route::post('checkout', 'CheckoutController@testCheckout');
            Route::post('requestAddPoint', 'CheckoutController@requestAddPoint');
            Route::post('checkRequestAddPoint', 'CheckoutController@checkRequestAddPoint');

            Route::get('my-collection', 'CollectionController@getCollectionOfUser');
        });

        Route::get('collection', 'CollectionController@getCollectionForUser');
        Route::get('collection/{id}', 'CollectionController@getCollectionDetailForUser');
        Route::get('collection/{id}/top-user', 'CollectionController@getCollectionTopUser');
//        Route::get('tag/{id}/collections', 'TagController@getCollectionByTagForUser');
        Route::get('tag/{id}/collections', 'TagController@show');
        Route::get('tags', 'TagController@getTagsForUser');
        Route::get('search', 'SearchController@searchAll');
        Route::get('slider-collection', 'FeatureController@list');
        Route::get('home-collection', 'FeatureController@list');
        Route::get('activity', 'ActivityController@index');

//        Route::get('answer-sheet-result/{id}', 'AnswerSheetController@getResult');
//         Route::post('response-answer-sheet', 'AnswerSheetController@updateAnswerSheet'); removed
    });
    // api for admin
    Route::group([''], function () {
        Route::group(['prefix' => 'report'], function () {
            Route::get('all', 'ReportController@all');
        });
        Route::resource('answer', 'AnswerController');
        Route::post('question/answer-attach', 'QuestionController@answerAttach')->name('question.answerAttach');
        Route::resource('question', 'QuestionController');
        Route::resource('tag', 'TagController');
        Route::get('users/search', 'UserController@search');
        Route::post('users/ban', 'UserController@banUser');
        Route::resource('users', 'UserController');
        Route::post('collection/publish', 'CollectionController@publish')->name('collection.publish');
        Route::post('collection/question-create', 'CollectionController@questionCreate')->name('collection.questionCreate');
        Route::post('collection/question-attach', 'CollectionController@questionAttach')->name('collection.questionAttach');
        Route::get('collection/search', 'CollectionController@search');
        Route::resource('collection', 'CollectionController');

        Route::post('notification', 'PushNotificationController@push');
        Route::post('point/send', 'UserController@addPoint');

        // feature collection
        Route::get('feature-collection', 'FeatureController@list');
        Route::post('feature-collection/add', 'FeatureController@add');
        Route::post('feature-collection/remove', 'FeatureController@remove');

    });
});


//# 127.0.0.1	tnm.dev
//# 127.0.0.1	admin.tnm.dev
//# 127.0.0.1	api.tnm.dev
