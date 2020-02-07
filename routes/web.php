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

Route::get('/', function () {
    return view('welcome');
//    return phpinfo();

});
Route::any('/test/pay','TestController@alipay');

Route::post('/api/test','Api\TestController@test');
Route::post('/api/user/reg','Api\TestController@reg');
Route::post('/api/user/login','Api\TestController@login');
Route::get('/api/user/list','Api\TestController@userList')->middleware('filter');
Route::get('/test/brush','Api\TestController@brush')->middleware('filter','chenkToken');
Auth::routes();
Route::get('/test/md5','Api\TestController@md5');     //get签名
Route::get('/test/md52','Api\TestController@md52');  //post签名
Route::get('/test/sign3', 'Api\TestController@sign3');  //私钥签名

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sign1', 'TestController@sign1');
Route::get('/sign2', 'TestController@sign2');

