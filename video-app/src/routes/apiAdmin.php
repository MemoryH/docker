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


Route::get('/video-list','Admin\VideoController@getList');
Route::get('/broadcast/list','Admin\VideoController@getVideoBroadcastList');
Route::post('/broadcast/set','Admin\VideoController@setVideoBroadcast');
Route::post('/broadcast/edit','Admin\VideoController@editVideoBroadcast');
Route::post('/broadcast/del','Admin\VideoController@delVideoBroadcast');
