<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/10 0010
 * Time: 11:54
 */
namespace App\Http\Controllers\Admin;



use App\Http\Controllers\Admin\Utils\ReturnJson;
use App\Http\Controllers\Controller;
use Basesvr\SimpleVideo\Video;
use Basesvr\SimpleVideo\VideoAd;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    protected $_videoService;

    public function __construct(Video $video)
    {
        $this->_videoService = $video;
    }

    /**
     * 获取视频列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(Request $request){
        $this->validate($request, [
            'global_type' => 'string',
            'type_id' => 'string',
            'resource_id' => 'int|exists:v_resource,id',
            'play_count' => 'string|in:desc,asc',
            'exponent_bd' => 'string|in:desc,asc',
        ]);

        $request->input('global_type') && $search['global_type'] = $request->input('global_type');
        $request->input('type_id') && $search['type_id'] = $request->input('type_id');
        $request->input('resource_id') && $search['resource_id'] = $request->input('resource_id');
        $request->input('video_title') && $search['video_title'] = $request->input('video_title');
        $request->input('play_count') && $order[] = ['play_count', $request->input('play_count')];
        $request->input('exponent_bd') && $order[] = ['exponent_bd', $request->input('exponent_bd')];
        $request->input('page') && $page =$request->input('page');
        $request->input('limit') && $limit =$request->input('limit');

        $res = $this->_videoService->lists(
            $search ?? [],
            $order ?? ['created_at', 'desc'],
            ['*'],
            $page??1,
            $limit??10
        );
        return ReturnJson::success($res);

    }

    /**
     * 设置轮播图
     * @param Request $request
     */
    public function setVideoBroadcast(Request $request){
        $this->validate($request, [
            'video_id' => 'int',
            'key' => 'required|string',
            'type' => 'required|string|in:LINK,VIDEO',
            'cover_path'=>'required|string',
            'title'=>'required|string',
            'global_type'=>'string',
            'remark'=>'string',
            'redirect_url'=>'string'
        ]);
        $request->input('video_id') && $data['video_id'] = $request->input('video_id');
        $request->input('global_type') && $data['global_type'] = $request->input('global_type');
        $request->input('key') && $data['key'] = $request->input('key');
        $request->input('type') && $data['type'] = $request->input('type');
        $request->input('cover_path') && $data['cover_path'] = $request->input('cover_path');
        $request->input('title') && $data['title'] = $request->input('title');
        $request->input('remark') && $data['remark'] = $request->input('remark');
        $request->input('redirect_url') && $data['redirect_url'] = $request->input('redirect_url');
        return ReturnJson::success(VideoAd::getInstance()->setVideoBroadcast($data));

    }

    /**
     * 获取轮播列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getVideoBroadcastList(Request $request){
        $this->validate($request, [
        ]);
        $res[] = VideoAd::getInstance()->adLists('ad_carousel_type');
        $res[] = VideoAd::getInstance()->adLists('ad_carousel');
        return ReturnJson::success($res);
    }

    /**
     * 编辑轮播
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function editVideoBroadcast(Request $request){
        $this->validate($request, [
            'id'=>'required|int',
            'cover_path'=>'string',
            'title'=>'|string',
            'remark'=>'string',
            'redirect_url'=>'string',
            'sort'=>'string'
        ]);
        $request->input('cover_path') && $data['cover_path'] = $request->input('cover_path');
        $request->input('title') && $data['title'] = $request->input('title');
        $request->input('remark') && $data['remark'] = $request->input('remark');
        $request->input('redirect_url') && $data['redirect_url'] = $request->input('redirect_url');
        $request->input('sort') && $data['sort'] = $request->input('sort');
        $request->input('id') && $search['id'] = $request->input('id');
        return ReturnJson::success(VideoAd::getInstance()->editVideoBroadcast($search['id'],$data));

    }

    /**
     * 删除轮播项
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function delVideoBroadcast(Request $request){
        $this->validate($request,[
            'id'=>'required|int'
        ]);
        $request->input('id') && $search['id'] = $request->input('id');
        return ReturnJson::success(VideoAd::getInstance()->delVideoBroadcast($search['id']));
    }
}