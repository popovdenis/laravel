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
Route::get('album/download/{id}', 'AlbumController@download')->where('id', '[0-9]+');
Route::resource('album','AlbumController');

Route::resource('image', 'ImageController');
Route::post('/image/uploadFiles', 'ImageController@uploadFiles');
Route::post('/image/removePhotos', 'ImageController@removePhotos');

Route::resource('comment', 'CommentController');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
