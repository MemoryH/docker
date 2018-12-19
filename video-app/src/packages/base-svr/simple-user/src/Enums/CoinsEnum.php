<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/13
 * Time: 8:59 PM
 */

namespace Basesvr\SimpleUser\Enums;


class CoinsEnum
{

    const EVENT_REGISTER = 'REGISTER';

    const EVENT_CLICK_AD = 'CLICK_AD';

    const EVENT_DAILY_SIGN_IN = 'SAILY_SIGN_IN';

    const EVENT_INVITE_REGISTER = 'INVITE_REGISTER';

    const EVENT_LOOKED_VIDEO_SATISFY = 'LOOKED_VIDEO_SATISFY';

    const EVENT_DAILY_SHARED = 'DAILY_SHARED';

    const EVENT_CONVERT_HD              = 'CONVERT_HD';

    const EVENT_CONVERT_DAILY_CACHE_3   = 'CONVERT_DAILY_CACHE_3';

    const EVENT_CONVERT_DAILY_CACHE_10  = 'CONVERT_DAILY_CACHE_10';

    const EVENT_CONVERT_DAILY_CACHE_NO  = 'CONVERT_DAILY_CACHE_NO';

    const EVENT_CONVERT_VIEW_COUNT      = 'CONVERT_VIEW_COUNT';

    const EVENT_CONVERT_BL                           = 'CONVERT_BL';

    const EVENT_CONVERT_DAILY_VIEW_COUNT_3           = 'CONVERT_DAILY_VIEW_COUNT_3';

    const EVENT_CONVERT_DAILY_VIEW_COUNT_10          = 'CONVERT_DAILY_VIEW_COUNT_10';

    const EVENT_CONVERT_DAILY_VIEW_COUNT_NO          = 'CONVERT_DAILY_VIEW_COUNT_NO';

    const EVENT_CONVERT_ALL_VIEW_COUNT_NO            = 'CONVERT_ALL_VIEW_COUNT_NO';

    /**
     * @var array
     */
    public static $getEventMap = [
        self::EVENT_REGISTER,
        self::EVENT_CLICK_AD,
        self::EVENT_DAILY_SIGN_IN,
        self::EVENT_INVITE_REGISTER,
        self::EVENT_LOOKED_VIDEO_SATISFY,
        self::EVENT_DAILY_SHARED,

        self::EVENT_CONVERT_HD,
        self::EVENT_CONVERT_DAILY_CACHE_3,
        self::EVENT_CONVERT_DAILY_CACHE_10,
        self::EVENT_CONVERT_DAILY_CACHE_NO,
        self::EVENT_CONVERT_VIEW_COUNT,

        self::EVENT_CONVERT_BL,
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_3,
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_10,
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_NO,
        self::EVENT_CONVERT_ALL_VIEW_COUNT_NO,

    ];

    /**
     * @var array
     */
    public static $getEventNameMap = [
        self::EVENT_REGISTER            => '新用户注册',
        self::EVENT_CLICK_AD            => '点击广告',
        self::EVENT_DAILY_SIGN_IN       => '每日签到任务',
        self::EVENT_INVITE_REGISTER     => '邀请用户注册',
        self::EVENT_LOOKED_VIDEO_SATISFY=> '观影满30分钟',
        self::EVENT_DAILY_SHARED        => '每日分享影片',

        self::EVENT_CONVERT_HD                  => '高清画质',
        self::EVENT_CONVERT_DAILY_CACHE_3       => '每日三次缓存特权',
        self::EVENT_CONVERT_DAILY_CACHE_10      => '每日十次缓存特权',
        self::EVENT_CONVERT_DAILY_CACHE_NO      => '每日没限次缓存特权',
        self::EVENT_CONVERT_VIEW_COUNT          => '十次观影次数',

        self::EVENT_CONVERT_BL                           => '蓝光画质',
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_3           => '每日三次观影次数',
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_10          => '每日十次观影次数',
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_NO          => '每日不限观影次数',
        self::EVENT_CONVERT_ALL_VIEW_COUNT_NO            => '永久不限观影次数'


    ];

    /**
     * @var array
     */
    public static $getEventValueMap = [
        self::EVENT_REGISTER            => 10,
        self::EVENT_CLICK_AD            => 10,
        self::EVENT_DAILY_SIGN_IN       => 10,
        self::EVENT_INVITE_REGISTER     => 100,
        self::EVENT_LOOKED_VIDEO_SATISFY=> 10,
        self::EVENT_DAILY_SHARED        => 10,

        self::EVENT_CONVERT_HD                  => -50, // 有效期1个月
        self::EVENT_CONVERT_DAILY_CACHE_3       => -60,  // 有效期3个月
        self::EVENT_CONVERT_DAILY_CACHE_10      => -300, // 有效期3个月
        self::EVENT_CONVERT_DAILY_CACHE_NO      => -500, // 有效期3个月
        self::EVENT_CONVERT_VIEW_COUNT          => -100, // 有效期1个月

        self::EVENT_CONVERT_BL                           => -50,   // 有效期3个月
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_3           => -60,   // 有效期3个月
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_10          => -300,  // 有效期3个月
        self::EVENT_CONVERT_DAILY_VIEW_COUNT_NO          => -500,  // 有效期3个月
        self::EVENT_CONVERT_ALL_VIEW_COUNT_NO            => -1000, // 永久有效


    ];

    /**
     *
     * getEventName
     *
     * @param string $param
     * @return mixed|string
     */
    public static function getEventName (string $param)
    {
        return self::$getEventNameMap[$param] ?? '';
    }

    /**
     *
     * getEventValue
     *
     * @param string $param
     * @return mixed|string
     */
    public static function getEventValue (string $param)
    {
        return self::$getEventValueMap[$param] ?? '';
    }


}