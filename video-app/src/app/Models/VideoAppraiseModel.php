<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/8 0008
 * Time: 10:47
 */

namespace App\Models;


class VideoAppraiseModel extends BaseModel
{
    /**
     * @var $_instance null
     */
    private static $_instance = null;

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

    protected $table = 'v_video_appraise';

    protected $primaryKey = "id";

    protected $fillable = [
        'user_id',
        'video_id',
        'recommend',
        'negative',
    ];
}