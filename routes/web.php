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
});
Route::any('/test/pay','TestController@alipay');

Route::post('/api/test','Api\TestController@test');
Route::post('/api/user/reg','Api\TestController@reg');
Route::post('/api/user/login','Api\TestController@login');
Route::get('/api/user/list','Api\TestController@userList')->middleware('filter');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/sign1', 'TestController@sign1');
Route::get('/sign2', 'TestController@sign2');
