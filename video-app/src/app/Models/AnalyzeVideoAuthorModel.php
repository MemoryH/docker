<?php
namespace App\Models;

class AnalyzeVideoAuthorModel extends BaseModel
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

    protected $table = "v_analyze_video_author";

    protected $primaryKey = "id";

    protected $fillable = [
    ];


    public function getVideoId(int $actor_id){
        return self::getInstance()->where('actor_id',$actor_id)->select('video_ids')->first()->toArray();
    }
}