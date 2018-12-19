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

Route::get('/video/globaltype','SimpleVideo\Https\Controllers\VideoController@getGlobalType');

Route::get('/video/dict','SimpleVideo\Https\Controllers\VideoController@getDict');

Route::get('/video/lists','SimpleVideo\Https\Controllers\VideoController@getLists');

Route::get('/video/guess/like','SimpleVideo\Https\Controllers\VideoController@guessYouLike');

Route::get('/video/comment/lists','SimpleVideo\Https\Controllers\VideoCommentController@lists');

Route::get('/video/index/recommend','SimpleVideo\Https\Controllers\VideoController@getIndexRecommendContent');

Route::get('/video/index/globaltype','SimpleVideo\Https\Controllers\VideoController@getIndexGlobalTypeContent');

Route::get('/video/info','SimpleVideo\Https\Controllers\VideoController@getInfo');

Route::get('/video/special/actor/list','SimpleVideo\Https\Controllers\SpecialController@actorLists');

Route::get('/video/special/type/list','SimpleVideo\Https\Controllers\SpecialController@typeLists');

Route::get('/video/special/actor/info','SimpleVideo\Https\Controllers\SpecialController@actorInfo');

Route::group(['middleware' => 'auth:api'], function () {

    Route::post('/video/user/appraise/add','SimpleVideo\Https\Controllers\VideoController@addAppraise');

    Route::post('/video/user/comment/add','SimpleVideo\Https\Controllers\VideoCommentController@add');

    Route::post('/video/opinion/add','SimpleVideo\Https\Controllers\VideoController@addOpinion');

    Route::get('/video/user/history/list','SimpleUser\Https\Controllers\UserController@historyLists');

    Route::get('/video/user/collect/list','SimpleUser\Https\Controllers\UserController@collectLists');

    Route::post('/video/user/collect/add','SimpleUser\Https\Controllers\UserController@addCollect');

    Route::post('/video/user/collect/cancel','SimpleUser\Https\Controllers\UserController@cancelCollect');

    /** for user **/

    Route::get('/user/task/dailysignin','SimpleUser\Https\Controllers\UserController@taskDailySignIn');

    Route::get('/user/task/lookvideosatisfy','SimpleUser\Https\Controllers\UserController@taskLookVideoSatisfy');

    Route::get('/user/task/clickad','SimpleUser\Https\Controllers\UserController@taskClickAd');

    Route::post('/user/equity/exchange','SimpleUser\Https\Controllers\UserController@exchange');

});