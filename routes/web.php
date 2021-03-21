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
Route::get('/add_events','AdminsController@add_events');
Route::post('/add_events','AdminsController@add_events');
Route::get('/add_events/{event_id}','AdminsController@add_events');
Route::post('/add_events/{event_id}','AdminsController@add_events');
Route::get('/login','PagesController@login')->name('login');
Route::post('/login','PagesController@login');
Route::get('/logout','AdminsController@logout');
Route::get('/jobs','AdminsController@jobs');
Route::post('/jobs','AdminsController@jobs');
Route::post('/jobs/{id}','AdminsController@jobs');
Route::get('/jobs/{id}','AdminsController@jobs');
//Route::post('/login','PagesController@signup');
