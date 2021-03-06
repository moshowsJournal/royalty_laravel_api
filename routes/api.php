<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/users/register','UsersController@register');
Route::post('/users/login','UsersController@login');
Route::post('/users/reset_password','UsersController@reset_password');
Route::post('/users/verify_forgot_password_pin','UsersController@verify_forgot_password_pin');
Route::post('/users/forgot_password_email','UsersController@forgot_password_email');
Route::post('/users/verify_user_email','UsersController@verify_user_email');
Route::middleware('auth:api')->get('/users/get_personal_chats','UsersController@get_personal_chats');
Route::middleware('auth:api')->post('/users/save_personal_chats','UsersController@save_personal_chats');
Route::middleware('auth:api')->post('/users/save_group_chat','UsersController@save_group_chat');
Route::middleware('auth:api')->get('/users/get_group_chats','UsersController@get_group_chats');
Route::middleware('auth:api')->post('/users/logout','UsersController@logout');
Route::middleware('auth:api')->get('/users/get_profile_photo','UsersController@get_profile_photo');
Route::middleware('auth:api')->get('/users/get_chat_list','UsersController@get_chat_list');
Route::post('/users/create_church_group','UsersController@create_church_group');
Route::post('/users/add_group_members','UsersController@add_group_members');
Route::middleware('auth:api')->get('/users/get_avaliable_members_and_groups','UsersController@get_avaliable_members_and_groups');
Route::middleware(['cors','auth:api'])->get('/users/get_events','UsersController@get_events');
Route::post('/users/add_events','UsersController@add_events');
Route::middleware('cors')->post('/admins/login','UsersController@login');

