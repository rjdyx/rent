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
    return view('option.editConfig', ['tmp' => '1']);
});


/*
*接收配置管理的后台请求
*/
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {// 匹配 "/admin/*" URL,控制器在 "App\Http\Controllers\Admin" 命名空间下

    //进入编辑配置页面
    Route::get('editConfig', 'EditConfigController@editConfig');

    //新增区域
    Route::post('addArea', 'EditConfigController@addArea');

    //新增房址
    Route::post('addAddress', 'EditConfigController@addAddress');

    //获取所有区域
    Route::post('getAllArea', 'EditConfigController@getAllArea');

    //获取所有区域以及选择区域
    Route::post('getAllAreaAndCheckOne', 'EditConfigController@getAllAreaAndCheckOne');

    //显示所有区域和房址
    Route::get('getAllAreaAndAddress', 'EditConfigController@getAllAreaAndAddress');

    //编辑区域名
    Route::post('editArea', 'EditConfigController@editArea');

    //编辑房址
    Route::post('editAddress', 'EditConfigController@editAddress');

    //删除区域
    Route::get('deleteArea/{id}', 'EditConfigController@deleteArea');

    //删除房址
    Route::get('deleteAddress/{id}', 'EditConfigController@deleteAddress');


});


/*
*接收住户管理的后台请求
*/
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {// 匹配 "/admin/*" URL,控制器在 "App\Http\Controllers\Admin" 命名空间下

    //进入新增住户信息页面
    Route::get('AddHouseholdView', 'HouseholdManageController@AddHouseholdView');

    //进入住户信息列表
    Route::get('HouseholdListView', 'HouseholdManageController@HouseholdListView');

    //进入编辑用户信息页面
    Route::get('editHouseholdMsg/{id}', 'HouseholdManageController@editHouseholdMsg');

    //新增住户信息
    Route::post('addHousehold', 'HouseholdManageController@addHousehold');

    //根据区域获取房址
    Route::get('getAddressByArea/{id}', 'HouseholdManageController@getAddressByArea');

    //获取所有区域和第一个区域对应的房址
    Route::get('getAreaAndAddress', 'HouseholdManageController@getAreaAndAddress');

    //退房
    Route::get('checkOutRent/{id}', 'HouseholdManageController@checkOutRent');

    //新增租房
    Route::post('addSingleRent', 'HouseholdManageController@addSingleRent');

    //租房作废，不记录房租信息
    Route::get('deleteRent/{id}', 'HouseholdManageController@deleteRent');

    //保存住户基本信息的修改
    Route::post('saveChange', 'HouseholdManageController@saveChange');

    //删除住户信息
    Route::get('deleteHouseholdMsg/{id}', 'HouseholdManageController@deleteHouseholdMsg');


});


Route::auth();

Route::get('/home', 'HomeController@index');
