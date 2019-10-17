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

Auth::routes(['verify' => true]);

Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');
Route::post('/set_locale', 'SettingsController@set_locale');
Route::post('/verify/email/{id}', 'AuthController@verify');
Route::post('/auth/restore', 'AuthController@sendPasswordResetLink');
Route::put('/auth/reset', 'AuthController@resetPassword');

Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'AuthController@refresh');
});

Auth::routes();

Route::group(['middleware' => 'jwt.auth'], function() {
    Route::get('/dict/masters/get', 'DictsController@masters');
    Route::get('/dict/new_masters/get', 'DictsController@new_masters');
    Route::get('/dict/task_types/get', 'DictsController@task_types');
    Route::get('/dict/multiple/get', 'DictsController@get_multiple');
    Route::get('/dict/new_tasks/get', 'DictsController@new_tasks');
    Route::get('/dict/active_tasks/get', 'DictsController@active_tasks');
    Route::get('/dict/completed_tasks/get', 'DictsController@completed_tasks');
    Route::get('/dict/new_tasks/get/all', 'DictsController@new_tasks_all');
    Route::get('/dict/active_tasks/get/all', 'DictsController@active_tasks_all');
    Route::get('/dict/completed_tasks/get/all', 'DictsController@completed_tasks_all');
    Route::get('/dict/profile/get', 'DictsController@profile');

    Route::delete('/masters/delete/{id}', 'SettingsController@delete_master');
    Route::put('/masters/approve/{id}', 'SettingsController@approve_master');
    Route::put('/masters/disapprove/{id}', 'SettingsController@disapprove_master');
    Route::post('/multiple/approve', 'SettingsController@approve_multiple');
    Route::post('/types/add', 'SettingsController@add_type');
    Route::delete('/types/delete/{id}', 'SettingsController@delete_type');

    Route::post('/task/add', 'TaskController@add_task');
    Route::put('/task/to_work/{id}', 'TaskController@to_work');
    Route::put('/task/done/{id}', 'TaskController@done');
    Route::delete('/task/delete/{id}', 'TaskController@delete_task');
    Route::get('/task/count/get', 'TaskController@count');
    Route::put('/task/master/change/{id}', 'TaskController@change_master');

    Route::put('/profile/edit', 'UserController@edit_user');
    Route::put('/profile/password/edit', 'UserController@edit_password');
    Route::get('/profile/is_admin', 'UserController@is_admin');
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
