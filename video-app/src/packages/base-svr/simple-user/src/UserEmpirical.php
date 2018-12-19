<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/13
 * Time: 8:33 PM
 */

namespace Basesvr\SimpleUser;


use App\Models\LevelModel;
use App\Models\UserEmpiricalHistoryModel;
use App\Models\UserExtendModel;
use Basesvr\SimpleUser\Enums\EmpiricalEnum;
use Carbon\Carbon;

class UserEmpirical extends Base
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
     * addHistory
     *
     * @param int $user_id
     * @param string $event
     * @return bool
     * @throws \Exception
     */
    public static function addHistory (int $user_id, string $event)
    {
        if (!in_array($event, EmpiricalEnum::$getEventMap)) throw new \Exception('error : empirical history event is invalid.');
        \DB::transaction(function () use ($user_id, $event) {
            switch ($event) {
                case EmpiricalEnum::EVENT_DAILY_LOGINED :
                    $ret = UserEmpiricalHistoryModel::getInstance()->where(['user_id' => $user_id, 'event' => $event])
                        ->whereBetween('created_at', [Carbon::now()->toDateString().' 00:00:00', Carbon::now()->toDateString().' 23:59:59'])->first();
                    if (!isset($ret)) self::saveHistory($user_id, $event);
                    break;
                default :
                    self::saveHistory($user_id, $event);
            }

        });

        return true;
    }

    /**
     *
     * saveHistory
     *
     * @param int $user_id
     * @param string $event
     */
    protected static function saveHistory (int $user_id, string $event)
    {
        UserEmpiricalHistoryModel::getInstance()->create([
            'user_id' => $user_id,
            'event'   => $event,
            'value'   => EmpiricalEnum::getEventValue($event)
        ]);
        $UserExtend = UserExtendModel::getInstance()->where(['user_id' => $user_id])->first();
        $UserExtend->increment('empirical', EmpiricalEnum::$getEventValueMap[$event]);
        $levels = LevelModel::query()->orderBy('id', 'asc')->get();
        $user_level_id = 0;
        foreach ($levels as $level){
            if ($UserExtend->empirical >= $level->empirical_satisfy) $user_level_id = $level->id;
        }
        $UserExtend->level_id = $user_level_id;
        $UserExtend->save();
    }



}