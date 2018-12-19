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
use Basesvr\SimpleVideo\VideoComment;
use Basesvr\SimpleVideo\Dict;


class VideoCommentController extends Controller
{

    /**
     * @var VideoComment
     */
    protected $_videoCommentService;

    /**
     * VideoCommentController constructor.
     * @param VideoComment $videoComment
     */
    public function __construct(VideoComment $videoComment)
    {
        $this->_videoCommentService = $videoComment;
    }

    public function add (Request $request)
    {
        $this->validate($request, [
            'global_type' => 'required|string',
            'video_id' => 'required|int|exists:v_video,id',
            'content' => 'required|string'
        ]);
        $request->input('video_id') && $data['video_id'] = $request->input('video_id');
        $request->input('global_type') && $data['global_type'] = $request->input('global_type');
        $request->input('content') && $data['content'] = $request->input('content');

        return ReturnJson::success($this->_videoCommentService->add($data));
    }

    /**
     *
     * lists
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function lists(Request $request){

        $this->validate($request,[
            'video_id'=>'required|int|exists:v_video,id'
        ]);

        $request->input('video_id') && $search['video_id'] = $request->input('video_id');
        $page = $request->input('page', 1);
        $limit = $request->input('limit', 20);

        $res = $this->_videoCommentService->lists($search, $order ?? ['created_at','desc'], [],$page,$limit);
        return ReturnJson::success($res);
    }

}