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

Auth::routes(['register' => false]);

Route::get('admin', 'CandidateController@index')->name('index')->middleware('auth');
Route::get('datatable', 'CandidateController@datatable')->name('datatable')->middleware('auth');
Route::get('/', 'CandidateController@create')->name('create');
Route::post('/', 'CandidateController@store')->name('store');
Route::get('{candidate}', 'CandidateController@show')->name('show');
Route::get('{candidate}/approve', 'CandidateController@approve')->name('approve');
Route::get('{candidate}/reject', 'CandidateController@reject')->name('reject');
