<?php
namespace App\Models;

use Basesvr\SimpleCommon\Config;

class VideoAuthorModel extends BaseModel
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

    protected $table = "v_video_author";

    protected $primaryKey = "id";

    protected $fillable = [
    ];

    /**
     * @var array
     */
    protected $appends = [
        'actor_label',
    ];

    /**
     *
     * getStatusLabelAttribute
     *
     * @param $value
     * @return mixed
     */
    public function getActorLabelAttribute ($value)
    {
        return Config::getInstance()->getConfigByActor($this->actor_id) ?? '';
    }



}