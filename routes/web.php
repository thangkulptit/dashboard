<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::group(['namespace'=>'Auth'], function(){
//     Route::get('/login', 'LoginController@getLogin');
//     Route::post('/login', 'LoginController@postLogin');
// });

Route::group(['namespace'=>'Auth'], function(){
    Route::group(['prefix'=>'login','middleware'=>'CheckLoggedIn'], function(){
        Route::get('/', 'LoginController@getLogin');
        Route::post('/', 'LoginController@postLogin');
    });
});

Route::group(['namespace'=>'Admin'], function(){
    Route::get('logout', 'HomeController@getLogout');

    Route::group(['prefix'=>'admin', 'middleware'=>'CheckLoggedOut'], function(){
        Route::get('/home', 'HomeController@getHome');
        Route::get('/tool', 'ToolController@getView');
        Route::post('/tool/add', 'ToolController@createKey');
        Route::post('/tool/{id}', 'ToolController@update');
        Route::get('/tool/delete/{id}', 'ToolController@remove');

        Route::get('/game/delete/{id}', 'ToolController@removeGame');
        Route::get('/game', 'ToolController@getViewGame');
        Route::post('/game/add', 'ToolController@addGame');
    });
});