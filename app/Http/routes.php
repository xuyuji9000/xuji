<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Weixin
Route::any('confirm', 'WeixinController@confirm');

Route::get('weixin/test', 'WeixinController@test');
Route::post('weixin/getimp', 'WeixinController@getImpData');

// Baidu
Route::get('baidu/local', 'BaiduController@local');

// Blade
Route::get('blade/test', 'BladeController@test');
Route::get('blade', 'BladeController@index');


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
