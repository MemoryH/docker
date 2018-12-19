<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/13
 * Time: 8:33 PM
 */

namespace Basesvr\SimpleUser;


use App\Models\UserCoinsHistoryModel;
use App\Models\UserExtendModel;
use Basesvr\SimpleUser\Enums\CoinsEnum;
use Carbon\Carbon;

class UserCoins extends Base
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
        if (!in_array($event, CoinsEnum::$getEventMap)) throw new \Exception('error : coins history event is invalid.');
        \DB::transaction(function () use ($user_id, $event) {
            switch ($event) {
                case CoinsEnum::EVENT_LOOKED_VIDEO_SATISFY :
                case CoinsEnum::EVENT_DAILY_SIGN_IN :
                case CoinsEnum::EVENT_CLICK_AD :
                    $ret = UserCoinsHistoryModel::getInstance()->where(['user_id' => $user_id, 'event' => $event])
                        ->whereBetween('created_at', [Carbon::now()->toDateString().' 00:00:00', Carbon::now()->toDateString().' 23:59:59'])->first();
                    if (!isset($ret)) self::saveHistory($user_id, $event);
                    break;
                case CoinsEnum::EVENT_CONVERT_HD:
                    $ret = UserExtendModel::getInstance()->where(['user_id' => $user_id])->first();
                    if ($ret->equity_hd_until <= Carbon::now()->toDateTimeString()) {
                        $ret->equity_hd = 1;
                        $ret->equity_hd_until = Carbon::now()->addMonths(3);
                        $ret->saveOrFail() && self::saveHistory($user_id, $event);
                    }
                    break;
                case CoinsEnum::EVENT_CONVERT_DAILY_VIEW_COUNT_3:
                    $ret = UserExtendModel::getInstance()->where(['user_id' => $user_id])->first();
                    if ($ret->equity_daily_view_3_until <= Carbon::now()->toDateTimeString()) {
                        $ret->equity_daily_view_3_status = 1;
                        $ret->equity_daily_view_3_count = 3;
                        $ret->equity_daily_view_3_until = Carbon::now()->addMonths(3);
                        $ret->saveOrFail() && self::saveHistory($user_id, $event);
                    }
                    break;
                case CoinsEnum::EVENT_CONVERT_DAILY_VIEW_COUNT_10:
                    $ret = UserExtendModel::getInstance()->where(['user_id' => $user_id])->first();
                    if ($ret->equity_daily_view_10_until <= Carbon::now()->toDateString()) {
                        $ret->equity_daily_view_10_status = 1;
                        $ret->equity_daily_view_10_count = 10;
                        $ret->equity_daily_view_10_until = Carbon::now()->addMonths(3);
                        $ret->saveOrFail() && self::saveHistory($user_id, $event);
                    }
                    break;
                case CoinsEnum::EVENT_CONVERT_DAILY_VIEW_COUNT_NO:
                    $ret = UserExtendModel::getInstance()->where(['user_id' => $user_id])->first();
                    if ($ret->equity_daily_view_no_until <= Carbon::now()->toDateString()) {
                        $ret->equity_daily_view_no_status = 1;
                        $ret->equity_daily_view_no_count = 0;
                        $ret->equity_daily_view_no_until = Carbon::now()->addMonths(3);
                        $ret->saveOrFail() && self::saveHistory($user_id, $event);
                    }
                    break;
                case CoinsEnum::EVENT_CONVERT_ALL_VIEW_COUNT_NO:
                    $ret = UserExtendModel::getInstance()->where(['user_id' => $user_id])->first();
                    if (!$ret->equity_all_view_no_status) {
                        $ret->equity_all_view_no_status = 1;
                        $ret->equity_daily_view_no_count = 0;
                        $ret->saveOrFail() && self::saveHistory($user_id, $event);
                    }
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
     *  @throws \Exception
     */
    protected static function saveHistory (int $user_id, string $event)
    {
        UserCoinsHistoryModel::getInstance()->create([
            'user_id' => $user_id,
            'event'   => $event,
            'value'   => CoinsEnum::getEventValue($event)
        ]);

        $UserExtends = UserExtendModel::getInstance()->where('user_id', $user_id)->first();
        if ($UserExtends->coins + CoinsEnum::$getEventValueMap[$event] < 0) {
            throw new \Exception('积分不足');
        }
        $UserExtends->increment('coins', CoinsEnum::$getEventValueMap[$event]);
        // todo 升级lv
    }



}