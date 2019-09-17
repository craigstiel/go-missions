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

//Route::get('/{any}', 'TaskController@index')->where('any', '.*');

Auth::routes(['verify' => true]);

Route::get('/', 'TaskController@index')->name('home');
Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');
Route::group(['middleware' => 'jwt.auth'], function(){
    Route::get('auth/user', 'AuthController@user');
    Route::post('auth/logout', 'AuthController@logout');
});
Route::group(['middleware' => 'jwt.refresh'], function(){
    Route::get('auth/refresh', 'AuthController@refresh');
});

Auth::routes();

Route::get('/dict/masters/get', 'DictsController@masters');
Route::get('/dict/new_masters/get', 'DictsController@new_masters');
Route::get('/dict/task_types/get', 'DictsController@task_types');
Route::get('/dict/multiple/get', 'DictsController@get_multiple');

Route::delete('/masters/delete/{id}', 'SettingsController@delete_master');
Route::put('/masters/approve/{id}', 'SettingsController@approve_master');
Route::put('/masters/disapprove/{id}', 'SettingsController@disapprove_master');
Route::post('/multiple/approve', 'SettingsController@approve_multiple');
Route::post('/types/add', 'SettingsController@add_type');
Route::delete('/types/delete/{id}', 'SettingsController@delete_type');
