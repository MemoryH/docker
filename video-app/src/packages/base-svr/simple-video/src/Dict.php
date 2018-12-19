<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/6
 * Time: 2:50 PM
 */

namespace Basesvr\SimpleVideo;

use Dict as BaseDict;

class Dict
{

    /**
     *
     * 获取全局类型： 电影、电视剧、综艺...
     *
     * @return array
     */
    public static function getGlobalType () : array
    {
        return BaseDict::get("video","global_type") ?? [];
    }

    /**
     *
     * 获取全局年代：2018...2013-2010...80年代...更早
     *
     * @return array
     */
    public static function getGlobalEpoch () : array
    {
        return BaseDict::get("video","global_epoch") ?? [];
    }

    /**
     *
     * 获取电影类型
     *
     * @return array
     */
    public static function getFilmType () : array
    {
        return BaseDict::get("film","type") ?? [];
    }

    /**
     *
     * 获取电影地区
     *
     * @return array
     */
    public static function getFilmDist () : array
    {
        return BaseDict::get("film","dist") ?? [];
    }

    /**
     *
     * 获取电视剧类型
     *
     * @return array
     */
    public static function getTeleplayType () : array
    {
        return BaseDict::get("teleplay","type") ?? [];
    }

    /**
     *
     * 获取电视剧区域
     *
     * @return array
     */
    public static function getTeleplayDist () : array
    {
        return BaseDict::get("teleplay","dist") ?? [];
    }

    /**
     *
     * 获取动漫区域
     *
     * @return array
     */
    public static function getCartoonDist () : array
    {
        return BaseDict::get("cartoon","dist") ?? [];
    }

    /**
     *
     * 获取综艺地区
     *
     *
     * @return array
     */
    public static function getVarietyDist () : array
    {
        return BaseDict::get("variety","dist") ?? [];
    }

    /**
     *
     * 公共获取key value的方法
     *
     * @param string $table_code
     * @param string $code
     * @return array
     */
    public static function getCommon (string $table_code, string $code) : array
    {
        return BaseDict::get($table_code, $code) ?? [];
    }

    /**
     *
     * 获取公共的name
     *
     * @param string $table_code
     * @param string $code
     * @param string $value
     * @return string
     */
    public static function getCommonName (string $table_code, string $code, string $value) : string
    {
        return BaseDict::get($table_code, $code, $value) ?? '';
    }

    public static function handleData (array $data) : array
    {
        if (!empty($data)) foreach ($data as $k => $v) {
            $d['key'] = (string)$k;
            $d['name'] = (string)$v;
            $ret[] = $d;
        }
        return $ret ?? [];
    }

}