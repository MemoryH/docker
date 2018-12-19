<?php

namespace Basesvr\SimpleVideo\Enums;


class IconsEnum
{

    const ICON_NEW  = 1; //最新

    const ICON_HOT  = 2; //最热

    const ICON_RECOMMEND = 3; //推荐

    /**
     * @var array
     */
    public static $getEventMap = [
        self::ICON_NEW,
        self::ICON_HOT,
        self::ICON_RECOMMEND,

    ];

    /**
     * @var array
     */
    public static $getEventNameMap = [
        self::ICON_NEW            => 'video_new.png',
        self::ICON_HOT            => 'video_hot.png',
        self::ICON_RECOMMEND       => 'video_recommend.png',
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




}