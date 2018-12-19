<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:45
 */

namespace Basesvr\SimpleUser\Https\Controllers;

use Basesvr\SimpleUser\Enums\CoinsEnum;
use Basesvr\SimpleUser\UserCoins;
use Illuminate\Http\Request;
use Basesvr\SimpleCommon\Utils\ReturnJson;
use Basesvr\SimpleUser\User;


class UserController extends Controller
{

    /**
     * @var User
     */
    protected $_userService;

    /**
     * VideoController constructor.
     * @param User $user
     */
    public function __construct()
    {
        $this->_userService = User::getInstance();
    }

    /**
     *
     * historyLists
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function historyLists (Request $request)
    {

        $data['page'] = $request->input('page',1);
        $data['limit'] = $request->input('limit',15);
        $ret = $this->_userService->historyLists(['user_id' => auth()->guard('api')->user()->id],[],['*'],$data['page'],$data['limit']);
        return ReturnJson::success($ret);
    }

    /**
     *
     * collectLists
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function collectLists (Request $request)
    {
        $data['page'] = $request->input('page',1);
        $data['limit'] = $request->input('limit',15);
        $ret = $this->_userService->collectLists(['user_id' => auth()->guard('api')->user()->id],[],['*'],$data['page'],$data['limit']);
        return ReturnJson::success($ret);
    }

    /**
     *
     * addCollect
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCollect (Request $request)
    {
        $this->validate($request, [
            'global_type' => 'required|string',
            'video_id' => 'required|int|exists:v_video,id',
        ]);
        $ret = $this->_userService->addCollect(['user_id' => auth()->guard('api')->user()->id,'video_id' => $request->input('video_id'),'global_type'=>$request->input('global_type')]);
        return ReturnJson::success($ret);
    }


    /**
     * cancel collect
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function cancelCollect(Request $request){

        $this->validate($request,[
           'video_id' => 'required|array'
        ]);
        $res = $this->_userService->cancelCollect(['user_id'=>auth()->user()->id,'video_id'=>$request->input('video_id')]);
        return ReturnJson::success($res);

    }
    /**
     *
     * taskDailySignIn
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskDailySignIn (Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $ret = UserCoins::getInstance()->addHistory($user_id, CoinsEnum::EVENT_DAILY_SIGN_IN);
        return ReturnJson::success($ret);
    }

    /**
     *
     * taskLookVideoSatisfy
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskLookVideoSatisfy (Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $ret = UserCoins::getInstance()->addHistory($user_id, CoinsEnum::EVENT_LOOKED_VIDEO_SATISFY);
        return ReturnJson::success($ret);
    }

    /**
     *
     * taskClickAd
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function taskClickAd (Request $request)
    {
        $user_id = auth()->guard('api')->user()->id;
        $ret = UserCoins::getInstance()->addHistory($user_id, CoinsEnum::EVENT_CLICK_AD);
        return ReturnJson::success($ret);
    }


    /**
     *
     * exchange
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange(Request $request)
    {
        $this->validate($request, [
            'event' => 'required|string',
        ]);
        $user_id = auth()->guard('api')->user()->id;
        $ret = UserCoins::getInstance()->addHistory($user_id, $request->input('event'));
        return ReturnJson::success($ret);
    }





}