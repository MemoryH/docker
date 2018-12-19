<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/6
 * Time: 2:50 PM
 */

namespace Basesvr\SimpleUser;

use App\Models\UserCollectModel;
use App\Models\UserExtendModel;
use App\Models\UserHistoryModel;
use App\Models\VideoModel;
use Basesvr\SimpleCommon\Config;
use Basesvr\SimpleCommon\Enums\ConfigEnum;
use Carbon\Carbon;
use Illuminate\Auth\Events\PasswordReset;
use App\User as UserModel;

class User extends Base
{

    /**
     * @var $_instance null
     */
    private static  $_instance = null;

    /**
     * User constructor.
     */
    private function __construct ()
    {
    }

    /**
     *
     * getInstance
     *
     * @return Singleton|null
     */
    public static function getInstance () {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * User __clone.
     */
    public function __clone ()
    {
        die('Clone is not allowed.' . E_USER_ERROR);
    }

    /**
     *
     * lists
     *
     * @param array $search
     * @param array $orders
     * @param $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function lists (array $search, array $orders, $columns, int $page, int $limit)
    {

    }

    /**
     *
     * info
     *
     * @param int $id
     * @param array $params
     * @return mixed
     */
    public function info (int $id, array $params)
    {

    }

    /**
     *
     * resetPassword
     *
     * @param array $data
     */
    public function resetPassword(array $data)
    {

        $user = UserModel::where('email', $data['email'])->first();

        $user->password = bcrypt($data['password']);

        $user->api_token = '';

        $user->save();

        event(new PasswordReset($user));

    }

    /**
     *
     * historyLists
     *
     * @param array $search
     * @param array $orders
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function historyLists (array $search, array $orders = ['created','desc'], $columns = ['*'], int $page = 1, int $limit = 15)
    {

        $videoTable = VideoModel::getInstance()->getTable();
        $historyTable = UserHistoryModel::getInstance()->getTable();
        $query = UserHistoryModel::with('author','resource');
        $query = $query->where($search)->rightJoin("$videoTable as v", function ($obj) use ($videoTable, $historyTable) {
            $obj->on("v.id",'=',"$historyTable.video_id");
        });
        $query = $this->order([$historyTable.'.created_at','desc'], $query);
        $ret = $query->paginate($limit, $columns, 'page', $page);
        return $ret;
    }

    /**
     *
     * addHistory
     *
     * @param array $data
     * @return mixed
     */
    public function addHistory (array $data)
    {
        return UserHistoryModel::getInstance()->create($data);
    }

    /**
     *
     * collectLists
     *
     * @param array $search
     * @param array $orders
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     */
    public function collectLists (array $search, array $orders = ['created_at','desc'], $columns = ['*'], int $page = 1, int $limit = 15)
    {
        $videoTable = VideoModel::getInstance()->getTable();
        $collectTable = UserCollectModel::getInstance()->getTable();
        $query = UserCollectModel::with('author','resource');
        $query = $query->where($search)->rightJoin("$videoTable as v", function ($obj) use ($videoTable, $collectTable) {
            $obj->on("v.id",'=',"$collectTable.video_id");
        });
        $query = $this->order([$collectTable.'.created_at','desc'], $query);
        $ret = $query->paginate($limit, $columns, 'page', $page);
        return $ret;
    }

    /**
     *
     * addCollect
     *
     * @param array $data
     * @return mixed
     */
    public function addCollect (array $data)
    {
        $search['user_id'] = $data['user_id'];
        $search['video_id'] = $data['video_id'];
        $res = UserCollectModel::getInstance()->where(['user_id'=>$search['user_id'],'video_id'=>$search['video_id']])->first();
        if (!$res){
            return UserCollectModel::getInstance()->create($data);
        }
        throw new \Exception('已收藏,请勿重新收藏');

    }

    /**
     * cancel collect
     * @param array $data
     * @return mixed
     */
    public function cancelCollect(array $data){
        return UserCollectModel::getInstance()->where(['user_id'=>$data['user_id']])->whereIn('video_id',$data['video_id'])->delete();
    }

    /**
     *
     * videoView
     *
     * @return bool
     * @throws \Exception
     */
    public function checkVideoView(){
        if (Config::getInstance()->getConfig('VIDEO_UNLIMIT_VIEW') == ConfigEnum::VIDEO_UNLIMIT_VIEW_DISABLE) {
            $user_id = auth()->guard('api')->check() ? auth()->guard('api')->user()->id : 0;
            if (!$user_id) {
                throw new \Exception('请登录后再观看视频.');
            }
            $userExtends = UserExtendModel::getInstance()->where('user_id', $user_id)->first();
            if ($userExtends->equity_all_view_no_status == 1) {
                $userExtends->increment('equity_all_view_no_count', 1);
            } elseif ($userExtends->equity_daily_view_no_status == 1 && Carbon::now()->toDateTimeString() < $userExtends->equity_daily_view_no_until) {
                $userExtends->increment('equity_daily_view_no_count', 1);
            } elseif ($userExtends->equity_daily_view_10_status == 1 && Carbon::now()->toDateTimeString() < $userExtends->equity_daily_view_10_until) {
                if ($userExtends->equity_daily_view_10_count <= 0) {
                    throw new \Exception('当日观影次数已用完');
                }
                $userExtends->increment('equity_daily_view_10_count', -1);
            } elseif ($userExtends->equity_daily_view_3_status == 1 && Carbon::now()->toDateTimeString() < $userExtends->equity_daily_view_3_until) {
                if ($userExtends->equity_daily_view_3_count <= 0) {
                    throw new \Exception('当日观影次数已用完');
                }
                $userExtends->increment('equity_daily_view_3_count', -1);
            } else {
                throw new \Exception('用户权益不足，无法观看');
            }
            return true;

        }
    }


}