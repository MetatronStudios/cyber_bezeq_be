<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['cors']], function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
    Route::post('password/email', 'AuthController@recover');
    Route::post('password/reset', 'AuthController@reset');
    Route::get('version', function () {
        // read the version number from a file named "counter.txt" which is located in the public folder and return it
        return response()->json(['version' => trim(file_get_contents(public_path('counter.txt')))]);
    });
    Route::get('timers', 'ConfigController@getTimers');
    Route::group(['middleware' => ['jwt.refresh2']], function () {
        Route::post('refresh', 'AuthController@refresh');
    });
    Route::group(['middleware' => ['cors', 'jwt.auth']], function () {
        Route::post('userInfo', 'AuthController@userInfo');
        Route::get('logout', 'AuthController@logout');
        Route::get('members', 'MemberController@getByIdUser');
    });

    Route::group(['middleware' => ['cors', 'jwt.auth', 'ip.filter', 'auth.admin']], function () {
        Route::prefix('config')->group(function () {
            Route::put('/{id}', 'ConfigController@update');
            Route::get('/{id}', 'ConfigController@get');
        });
        Route::prefix('user')->group(function () {
            Route::get('/', 'UserController@list');
            Route::get('/winners', 'UserController@winners');
            Route::get('/winners_export', 'UserController@winners_export');
            Route::get('/winnersMember', 'UserController@winnersMembers');
            Route::get('/finalistWinners', 'UserController@finalist_winners');
            Route::get('/finalistWinners_export', 'UserController@finalistWinners_export');

            Route::get('/export', 'UserController@export');
        });
        Route::prefix('ImportGroups')->group(function () {
            Route::get('/', 'ImportGroupsController@list');
            Route::post('/', 'ImportGroupsController@addNew');
        });
        Route::prefix('GroupedRiddles')->group(function () {
            Route::get('/', 'GroupedRiddlesController@list');
            Route::post('/', 'GroupedRiddlesController@add');
            Route::put('/{id}', 'GroupedRiddlesController@update');
            Route::get('/{id}', 'GroupedRiddlesController@get');
        });
        Route::prefix('riddle')->group(function () {
            Route::get('/', 'RiddleController@list');
            Route::get('/all', 'RiddleController@getAll');
            Route::get('/export', 'RiddleController@export');
            Route::put('/{id}', 'RiddleController@update');
            Route::get('/{id}', 'RiddleController@get');
            Route::post('/', 'RiddleController@add');
        });
        Route::prefix('solution')->group(function () {
            Route::get('/', 'SolutionController@list');
            Route::get('/export', 'SolutionController@export');
        });
        Route::prefix('fact')->group(function () {
            Route::get('/', 'FactController@list');
            Route::get('/export', 'FactController@export');
            Route::put('/{id}', 'FactController@update');
            Route::get('/{id}', 'FactController@get');
        });
        Route::prefix('reply')->group(function () {
            Route::get('/', 'ReplyController@list');
            Route::get('/export', 'ReplyController@export');
        });
    });

    Route::prefix('v1')->group(function () {
        Route::get('ticker', 'ConfigController@getTicker');
        Route::group(['middleware' => ['disabled']], function () { // works only if system is not in final stage
            //Route::get('riddle', 'RiddleController@getAllWithAnswerNotLogged');
            Route::get('groupedRiddles', 'GroupedRiddlesController@getPlayableGroupsNotLogged');
            Route::prefix('auth')->group(function () { // works only if system is not in final stage and user is logged in
                Route::group(['middleware' => ['jwt.auth']], function () {
                    Route::get('groupedRiddlesWithAns', 'GroupedRiddlesController@getPlayableGroups');
                    Route::post('solution', 'SolutionController@add');
                });
            });
        });
    });
    Route::prefix('v2')->group(function () {
        Route::group(['middleware' => ['enabled']], function () { // works only if system is in final stage
            Route::get('facts', 'FactController@getAll');
            Route::prefix('auth')->group(function () { // works only if system is in final stage and user is logged in
                Route::group(['middleware' => ['jwt.auth', 'auth.finalist']], function () { // works only if system is in final stage and user is logged in and is a finalist
                    Route::post('reply', 'ReplyController@add');
                });
            });
        });
    });
});
