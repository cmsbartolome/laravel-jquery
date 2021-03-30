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

Route::get('/countries', function () {
    return view('countries.index');
});

Route::get('/articles', function (){
    return view('articles.index') ;
});

Route::get('/users', function (){
    return view('users.index') ;
});

Auth::routes();

Route::post('/user-list', 'UserController@userList');
Route::post('/show-user', 'UserController@show');
Route::post('/store-user', 'UserController@store');
Route::post('/update-user', 'UserController@update');
Route::post('/delete-user', 'UserController@delete');

Route::get('/home', 'HomeController@index')->name('home');
