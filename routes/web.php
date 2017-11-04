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

Route::auth();

Route::resource('/','UserController');

Route::resource('admin','Admin\UsersController');
Route::resource('admin/users','Admin\UsersController');

Route::resource('user','UserController');

Route::get('album/download', 'AlbumController@download');
Route::get('album/removeList', 'AlbumController@removeList');
Route::resource('album','AlbumController');

Route::post('/image/uploadFiles', 'ImageController@uploadFiles');
Route::get('image/download', 'ImageController@download');
Route::get('/image/remove', 'ImageController@remove');
Route::resource('image', 'ImageController');

Route::resource('comment', 'CommentController');
Route::post('/comment/getNewComments', 'CommentController@getNewComments');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
