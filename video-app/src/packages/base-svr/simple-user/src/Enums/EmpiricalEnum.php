<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/13
 * Time: 8:59 PM
 */

namespace Basesvr\SimpleUser\Enums;


class EmpiricalEnum
{

    const EVENT_REGISTER = 'REGISTER';

    const EVENT_LOOK_AD = 'LOOK_AD';

    const EVENT_DAILY_LOGINED = 'DAILY_LOGINED';

    const EVENT_LOOK_VIDEO = 'LOOK_VIDEO';

    const EVENT_SHARE_VIDEO = 'SHARE_VIDEO';

    /**
     * @var array
     */
    public static $getEventMap = [
        self::EVENT_REGISTER,
        self::EVENT_LOOK_AD,
        self::EVENT_DAILY_LOGINED,
        self::EVENT_LOOK_VIDEO,
        self::EVENT_SHARE_VIDEO,
    ];

    /**
     * @var array
     */
    public static $getEventNameMap = [
        self::EVENT_REGISTER            => '新用户注册',
        self::EVENT_LOOK_AD             => '观看广告',
        self::EVENT_DAILY_LOGINED       => '每日登录',
        self::EVENT_LOOK_VIDEO          => '观看影片',
        self::EVENT_SHARE_VIDEO         => '分享影片',
    ];

    /**
     * @var array
     */
    public static $getEventValueMap = [
        self::EVENT_REGISTER            => 10,
        self::EVENT_LOOK_AD             => 15,
        self::EVENT_DAILY_LOGINED       => 10,
        self::EVENT_LOOK_VIDEO          => 5,
        self::EVENT_SHARE_VIDEO         => 5,
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