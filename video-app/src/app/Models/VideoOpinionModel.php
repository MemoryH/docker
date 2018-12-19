<?php
namespace App\Models;

class VideoOpinionModel extends BaseModel
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

    protected $table = "v_video_opinion";

    protected $primaryKey = "id";

    protected $fillable = [
        'global_type',
        'video_id',
        'message',
        'user_id',
    ];



}