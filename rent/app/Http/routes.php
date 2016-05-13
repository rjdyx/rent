<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('option.editConfig',['tmp' => '1']);
});


/*
*接收后台请求
*/
Route::group(['prefix' => 'admin','namespace' => 'Admin'], function() {// 匹配 "/admin/*" URL,控制器在 "App\Http\Controllers\Admin" 命名空间下

	//新增区域
	Route::post('addArea', 'EditConfigController@addArea');
	
});





Route::auth();

Route::get('/home', 'HomeController@index');
