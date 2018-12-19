<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/10
 * Time: 4:18 PM
 */

namespace Basesvr\SimpleVideo\Https\Controllers;

use Illuminate\Http\Request;
use Basesvr\SimpleCommon\Utils\ReturnJson;
use Basesvr\SimpleVideo\Video;
use Basesvr\SimpleVideo\Dict;

class SpecialController extends Controller
{

    /**
     * @var Video
     */
    protected $_videoService;

    /**
     * VideoController constructor.
     * @param Video $video
     */
    public function __construct(Video $video)
    {
        $this->_videoService = $video;
    }

    /**
     *
     * 获取视频分类列表
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function typeLists (Request $request)
    {
        $page = $request->input('page',1);
        $limit = $request->input('limit',8);
        $ret = $this->_videoService->typeVideoLists(['id','global_type','type_id','video_count'],$page,$limit);
        return ReturnJson::success($ret);
    }

    /**
     *
     * 获取演员列表
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function actorLists (Request $request)
    {
        // todo 后面优化
        $ret = $this->_videoService->actorLists();
        return ReturnJson::success($ret);
    }

    /**
     *
     * actorInfo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function actorInfo(Request $request){
        $this->validate($request,[
           'actor_id'=>'required|int'
        ]);
        $res = $this->_videoService->actorInfo($request->input('actor_id'));
        return ReturnJson::success($res);
    }



}