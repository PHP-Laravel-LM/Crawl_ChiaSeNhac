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
Route::get('song/crawl', 'SongController@crawlSong');
Route::get('song/find', 'SongController@findSong');
Route::get('song/get/{id}', 'SongController@getSong');
Route::post('song', 'SongController@saveSong');
