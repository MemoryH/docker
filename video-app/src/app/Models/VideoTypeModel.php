<?php
namespace App\Models;

use Basesvr\SimpleVideo\Dict;

class VideoTypeModel extends BaseModel
{

    /**
     * @var $_instance null
     */
    private static  $_instance = null;

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

    protected $table = "v_video_type";

    protected $primaryKey = "id";

    protected $fillable = [
    ];

    /**
     * @var array
     */
    protected $appends = [
        'type_label',
    ];

    /**
     *
     * getStatusLabelAttribute
     *
     * @param $value
     * @return mixed
     */
    public function getTypeLabelAttribute ($value)
    {
        return Dict::getCommonName($this->global_type,'type', $this->type_id);
    }



}