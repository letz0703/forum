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

Auth::routes();

Route::view('scan','scan');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/threads','ThreadController@index')->name('threads');
Route::get('/threads/create','ThreadController@create');
Route::get('/threads/search','SearchController@show');
Route::get('/threads/{channel}/{thread}','ThreadController@show');
Route::patch('/threads/{channel}/{thread}','ThreadController@update');
Route::delete('/threads/{channel}/{thread}','ThreadController@destroy');
Route::post('thread-lock/{thread}','ThreadLockController@store')->name('thread-lock.store')->middleware('admin');
Route::delete('thread-lock/{thread}','ThreadLockController@destroy')->name('thread-lock.destroy')->middleware('admin');

Route::post('/threads','ThreadController@store')->middleware('email-confirmed');
Route::post('/threads/{channel}/{thread}/replies','ReplyController@store');
Route::get('/threads/{channel}/{thread}/replies','ReplyController@index');
Route::post('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionController@store')->middleware('auth');
Route::delete('/threads/{channel}/{thread}/subscriptions','ThreadSubscriptionController@destroy');
Route::get('/threads/{channel}','ThreadController@index');
Route::post('/replies/{reply}/favorites','FavoriteController@store');
Route::delete('/replies/{reply}/favorites','FavoriteController@destroy');

Route::post('/replies/{reply}/best','BestReplyController@store')->name('best-reply.store');

Route::get('/profiles/{user}','ProfileController@show')->name('profile');
Route::get('/profiles/{user}/notifications','ThreadNotificationController@index');
Route::delete('/profiles/{user}/notifications/{notification}','ThreadNotificationController@destroy');
Route::delete('/replies/{reply}','ReplyController@destroy')->name('reply.destroy');
Route::patch('/replies/{reply}','ReplyController@update');
Route::get('/api/users','Api\UserController@index');
Route::post('/api/users/{user}/avatar','Api\UserAvatarController@store')->middleware('auth')->name('avatar');
Route::get('/register/confirm','Auth\RegisterConfirmationController@index')->name('register.confirm');
//Route::resource('threads','ThreadController');
