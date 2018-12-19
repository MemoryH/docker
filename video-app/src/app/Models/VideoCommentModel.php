<?php
namespace App\Models;

class VideoCommentModel extends BaseModel
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

    protected $table = "v_video_comment";

    protected $primaryKey = "id";

    protected $fillable = [
        'global_type',
        'video_id',
        'user_id',
        'content',
    ];

    public function commentable()
    {
        return $this->morphTo();
    }

}