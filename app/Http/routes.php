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

// use Storage;


// Weixin
Route::any('confirm', 'WeixinController@confirm');

Route::get('weixin/test', 'WeixinController@test');
Route::get('weixin/test2', 'WeixinController@test2');
Route::post('weixin/getimp', 'WeixinController@getImpData');

// Baidu
Route::get('baidu/local', 'BaiduController@local');

// Blade
Route::get('blade/test', 'BladeController@test');
Route::get('blade', 'BladeController@index');

// Function
Route::get('function/upload', 'FunctionController@upload');
Route::post('function/uploadImg', 'FunctionController@uploadImg');
Route::post('function/submit', 'FunctionController@submit');
Route::get('function/test', 'FunctionController@test');

// ******Demo******
Route::get("test", "TestController@index");
Route::get("test/log", "TestController@log");
// 微信授权Demo
Route::get("test/getcode", "TestController@getcode");
Route::get("test/getdetail", "TestController@getdetail");
// 微信jssdkDEMO
Route::get("test/sdk", "TestController@sdk");
Route::get("test/payment", "TestController@payment");




// images
Route::get("img/{path}", function(League\Glide\Server $server, $path){
	$server->outputImage( $path, $_GET);
});

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

Route::group(['middleware' => 'web'], function () {
    Route::auth();

    Route::get('/home', 'HomeController@index');
    Route::get('/', function () {
        return view('welcome');
    });
});
