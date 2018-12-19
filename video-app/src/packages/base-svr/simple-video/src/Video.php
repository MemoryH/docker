<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:14
 */

namespace Basesvr\SimpleVideo;

use App\Models\ActorModel;
use App\Models\AnalyzeVideoAuthorModel;
use App\Models\AnalyzeVideoTypeModel;
use App\Models\UserCollectModel;
use App\Models\VideoAppraiseModel;
use App\Models\VideoAuthorModel;
use App\Models\VideoModel;
use App\Models\VideoOpinionModel;
use App\Models\VideoResourceModel;
use App\Models\VideoTypeModel;
use Basesvr\SimpleUser\Enums\EmpiricalEnum;
use Basesvr\SimpleVideo\Enums\IconsEnum;
use Basesvr\SimpleUser\Events\UserVideoViewEvent;
use Basesvr\SimpleVideo\Events\UserHistoryEvent;
use Basesvr\SimpleVideo\Events\UserVideoInfoEvent;
use Illuminate\Support\Facades\DB;

class Video extends Base
{

    /**
     * @var VideoModel
     */
    protected $_videoModel;

    /**
     * Video constructor.
     * @param VideoModel $videoModel
     */
    public function __construct(VideoModel $videoModel)
    {
        $this->_videoModel = $videoModel;
    }

    /**
     *
     * lists
     *
     * @param array $search
     * @param array $orders
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws \Exception
     */
    public function lists(array $search, array $orders = ['created_at', 'desc'], $columns = ['*'], int $page = 1, int $limit = 15)
    {

        if (!empty($search['global_type'])) {
            $this->checkGlobalType($search['global_type']);
        }

        $videoTable = VideoModel::getInstance()->getTable();
        $videoTypeTable = VideoTypeModel::getInstance()->getTable();
        $videoResourceTable = VideoResourceModel::getInstance()->getTable();
        $videoAuthorTable = VideoAuthorModel::getInstance()->getTable();

        $columns = "$videoTable.id,$videoTable.title,$videoTable.cover_path,$videoTable.global_type,$videoTable.dist_id,$videoTable.epoch_id,$videoTable.director,$videoTable.play_count,$videoTable.created_at,$videoTable.label_status";

        $query = $this->_videoModel::with(['type']);

        if (!empty($search['global_type'])) $query = $query->where([$videoTable . '.global_type' => $search['global_type']]);

        if (!empty($search['dist_id'])) $query = $query->where([$videoTable . '.dist_id' => $search['dist_id']]);

        if (!empty($search['epoch_id'])) $query = $query->where([$videoTable . '.epoch_id' => $search['epoch_id']]);

        if (!empty($search['type_id'])) {
            $query = $query->rightJoin("$videoTypeTable as vt", function ($sub) use ($videoTable, $videoTypeTable, $search) {
                $sub->on("vt.video_id", '=', "$videoTable.id")
                    ->where("vt.type_id", $search['type_id'])
                    ->where("vt.global_type", $search['global_type']);
            });
            $columns = $columns . ",vt.type_id";
        }

        if (!empty($search['resource_id'])) {
            $query = $query->rightJoin("$videoResourceTable as vr", function ($sub) use ($videoTable, $videoResourceTable, $search) {
                $sub->on("vr.video_id", '=', "$videoTable.id")
                    ->where("vr.resource_id", $search['resource_id'])
                    ->where("vr.global_type", $search['global_type']);
            });
        }

        if (!empty($search['video_title'])) {
            $query = $query->where("$videoTable.title", 'like', '%' . $search['video_title'] . '%');
        }

        if (!empty($search['actor_id'])){
            $query = $query->join("$videoAuthorTable as va", function ($sub) use ($videoTable, $videoAuthorTable, $search){
                $sub->on("va.video_id", '=', "$videoTable.id")
                    ->where("va.actor_id", $search['actor_id']);
            });
        }

        $query = $query->select(DB::raw("$columns,left($videoTable.intro,20) as intro"));

        $query = $this->order($orders, $query);

        return $query->paginate($limit, $columns, 'page', $page);
    }

    /**
     *
     * addAppraise
     *
     * @param array $params
     * @return bool
     * @throws \Exception
     */
    public function addAppraise(array $params)
    {

        switch ($params['action']) {
            case 'recommend' :
                $data['recommend'] = 1;
                break;
            case 'negative' :
                $data['negative'] = 1;
                break;
            default :
                throw new \Exception('error appraise parms.');
        }

        $result = VideoAppraiseModel::getInstance()->where(['user_id' => auth()->user()->id, 'video_id' => $params['video_id']])->first();
        if (!$result) {

            $data = $data + ['user_id' => auth()->user()->id, 'video_id' => $params['video_id']];

            VideoAppraiseModel::getInstance()->create($data);
        } else {
            $action = $params['action'];
            $result->recommend = 0;
            $result->negative = 0;
            $result->$action = 1;
            $result->save();
        }
        return true;
    }

    /**
     *
     * info
     *
     * @param int $video_id
     * @param array $params
     * @return VideoModel
     */
    public function info(int $video_id, array $params)
    {
        event(new UserVideoViewEvent());


        $query = $this->_videoModel::with(['author', 'type', 'comment', 'appraise']);

        $query = $query->select(['*']);

        if (auth()->guard('api')->check()) {
            $user_id = auth()->guard('api')->user()->id;
            event(new UserHistoryEvent($video_id, 'created', []));
            event(new UserVideoInfoEvent($user_id, 'looking_video', ['empirical_event' => EmpiricalEnum::EVENT_LOOK_VIDEO]));
        }

        $ret = $query->where('id', $video_id)->first();
        $ret->resource = VideoResource::getInstance()->info($video_id);
        $ret->user_collect = (int)UserCollectModel::getInstance()->where(['video_id' => $video_id, 'user_id' => $user_id ?? 0])->exists() ?? 0;
        $ret->comment_sum = collect($ret->comment)->count();
        $ret->recommend_sum = collect($ret->appraise)->where('recommend', 1)->count();
        $ret->negative_sum = collect($ret->appraise)->where('negative', 1)->count();
        $user_appraise = collect($ret->appraise)->where('user_id', $user_id ?? 0)->first();
        $ret->user_recommend = $user_appraise ? $user_appraise->recommend : 0;
        $ret->user_negative = $user_appraise ? $user_appraise->negative : 0;

        return $ret;

    }

    /**
     *
     * addOpinion
     *
     * @param array $params
     * @return mixed
     */
    public function addOpinion(array $params)
    {
        $params['user_id'] = auth()->user()->id;
        $ret = VideoOpinionModel::getInstance()->create($params);
        return $ret;
    }

    /**
     *
     * typeVideoLists
     *
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function typeVideoLists(array $columns = ['*'], int $page = 1, int $limit = 15)
    {
        $result = AnalyzeVideoTypeModel::getInstance()->select($columns)->where([])->orderBy('video_count', 'desc')->paginate($limit, $columns, 'page', $page)->toArray();
        array_walk($result['data'], function (&$v) {
            $v['type_name'] = Dict::getCommonName($v['global_type'], 'type', $v['type_id']);
        });
        return $result;
    }


    /**
     * 猜你喜欢
     * @param int $video_id
     * @return mixed
     */
    public function guessYouLike(int $video_id)
    {
        $videoTypeTable = VideoTypeModel::getInstance()->getTable();
        $videoTable = VideoModel::getInstance()->getTable();
        $result = $this->_videoModel->where($videoTable . '.id', $video_id)->rightJoin("$videoTypeTable as vt", function ($obj) use ($videoTable) {
            $obj->on($videoTable . ".id", "=", "vt.video_id");
        })->select('vt.type_id', $videoTable . '.dist_id', $videoTable . '.global_type', $videoTable . '.epoch_id')->first()->toArray();
        return $result;

    }

    /**
     *
     * actorLists
     *
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function actorLists(array $columns = ['*'], int $page = 1, int $limit = 15)
    {

        $analyzeVideoTable = AnalyzeVideoAuthorModel::getInstance()->getTable();
        $actorTable = ActorModel::getInstance()->getTable();

        $result = AnalyzeVideoAuthorModel::getInstance()->select(["$analyzeVideoTable.actor_id", "a.name", "a.intro", "a.cover_path", "$analyzeVideoTable.video_count"])
            ->leftJoin("$actorTable as a", "a.id", "=", "$analyzeVideoTable.actor_id")->where([])->orderBy('video_count', 'desc')->paginate($limit, $columns, 'page', $page);

        return $result;
    }

    /**
     *
     * actorInfo
     *
     * @param int $id
     * @return mixed
     * @throws \Exception
     */
    public function actorInfo(int $id)
    {
        return ActorModel::getInstance()->where('id', $id)->first();
    }

}