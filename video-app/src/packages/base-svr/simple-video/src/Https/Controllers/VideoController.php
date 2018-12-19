<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:45
 */

namespace Basesvr\SimpleVideo\Https\Controllers;

use Illuminate\Http\Request;
use Basesvr\SimpleCommon\Utils\ReturnJson;
use Basesvr\SimpleVideo\Video;
use Basesvr\SimpleVideo\Dict;
use Basesvr\SimpleVideo\VideoAd;


class VideoController extends Controller
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
     * getLists
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getLists (Request $request)
    {
        $this->validate($request, [
            'global_type' => 'string',
            'type_id' => 'string',
            'resource_id' => 'int|exists:v_resource,id',
            'play_count' => 'string|in:desc,asc',
            'exponent_bd' => 'string|in:desc,asc',
            'created_at' => 'string|in:desc,asc',
            'actor_id' => 'int|exists:v_actor,id'
        ]);

        $request->input('global_type') && $search['global_type'] = $request->input('global_type');
        $request->input('type_id') && $search['type_id'] = $request->input('type_id');
        $request->input('resource_id') && $search['resource_id'] = $request->input('resource_id');
        $request->input('video_title') && $search['video_title'] = $request->input('video_title');
        $request->input('actor_id') && $search['actor_id'] = $request->input('actor_id');

        $request->input('play_count') && $order[] = ['play_count', $request->input('play_count')];
        $request->input('exponent_bd') && $order[] = ['exponent_bd', $request->input('exponent_bd')];
        $request->input('created_at') && $order[] = ['created_at', $request->input('created_at')];

        $page = $request->input('page', 1);
        $limit = $request->input('limit', 15);

        $res = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['created_at', 'desc'],
            ['*'],
            $page,
            $limit
        );
        return ReturnJson::success($res);
    }

    /**
     *
     * getInfo
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getInfo (Request $request)
    {
        $this->validate($request, [
            'video_id' => 'required|int|exists:v_video,id',
        ]);
        $res = $this->_videoService->info($request->video_id, []);
        return ReturnJson::success($res);
    }

    /**
     *
     * addAppraise
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function addAppraise (Request $request)
    {

        $this->validate($request, [
            'action' => 'required|string|in:recommend,negative',
            'video_id' => 'required|int|exists:v_video,id',
        ]);

        $res = $this->_videoService->addAppraise(['action' => $request->action, 'video_id' => $request->video_id]);

        return ReturnJson::success($res);
    }

    /**
     *
     * getDict
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDict (Request $request)
    {

        $this->validate($request, [
            'global_type' => 'required|string',
        ]);

        return ReturnJson::success([
            'type' => Dict::handleData(Dict::getCommon($request->global_type, 'type')),
            'dist' => Dict::handleData(Dict::getCommon($request->global_type, 'dist')),
            'epoch' => Dict::handleData(Dict::getCommon('video', 'global_epoch'))
        ]);

    }

    /**
     *
     * getGlobalType
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getGlobalType ()
    {
        $ret = Dict::handleData(Dict::getGlobalType());
        array_unshift($ret,array('key'=>'recommend','name'=>'推荐'));
        return ReturnJson::success($ret ?? []);
    }

    /**
     *
     * getIndexRecommend
     *
     * 1. banner
     * 2. 9宫格
     * 3. 热播排行
     * 4. 经典推荐
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndexRecommendContent (Request $request)
    {
        $this->validate($request, [
            'resource_id' => 'int|exists:v_resource,id'
        ]);
        $request->input('resource_id') && $search['resource_id'] = $request->input('resource_id');

        $result['ad_carousel'] = VideoAd::getInstance()->adLists('ad_carousel');
        $result['sudoku'] = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['created_at', 'desc'],
            ['*'],
            1,
            9
        );
        $result['rebo'] = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['exponent_bd', 'desc'],
            ['*'],
            1,
            9
        );
        $result['classics'] = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['play_count_real', 'desc'],
            ['*'],
            1,
            21
        );
        return ReturnJson::success($result);
    }

    /**
     *
     * getIndexGlobalTypeContent
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getIndexGlobalTypeContent (Request $request)
    {
        $this->validate($request, [
            'global_type' => 'required|string',
            'type_id' => 'string',
            'resource_id' => 'int|exists:v_resource,id',
            'play_count' => 'string|in:desc,asc',
            'exponent_bd' => 'string|in:desc,asc',

        ]);
        $request->input('resource_id') && $search['resource_id'] = $request->input('resource_id');
        $request->input('global_type') && $search['global_type'] = $request->input('global_type');

        $result['ad_carousel'] = VideoAd::getInstance()->adLists('ad_carousel_type', isset($search['global_type']) ? ['global_type' => $search['global_type']] : []);
        $result['sudoku'] = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['created_at', 'desc'],
            ['*'],
            1,
            9
        );
        $result['rebo'] = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['exponent_bd', 'desc'],
            ['*'],
            1,
            9
        );
        $result['classics'] = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['exponent_bd', 'desc'],
            ['*'],
            1,
            21
        );
        return ReturnJson::success($result);
    }

    /**
     *
     * addOpinion
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addOpinion (Request $request)
    {
        $this->validate($request, [
            'global_type' => 'required|string',
            'video_id' => 'required|int|exists:v_video,id',
            'message' => 'required|string',
        ]);
        $request->input('video_id') && $data['video_id'] = $request->input('video_id');
        $request->input('global_type') && $data['global_type'] = $request->input('global_type');
        $request->input('message') && $data['message'] = $request->input('message');

        return ReturnJson::success($this->_videoService->addOpinion($data));
    }


    /**
     *
     * 猜你喜欢
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function guessYouLike(Request $request){
        $this->validate($request,[
           'video_id'=>'required|string'
        ]);
        $request->input('video_id') && $data['video_id'] = $request->input('video_id');
        $result = $this->_videoService->guessYouLike($data['video_id']);

        $res = $this->_videoService->lists($result,['exponent_bd','desc']);
        return ReturnJson::success($res);
    }


}