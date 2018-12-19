<?php
namespace App\Models;

use Dict as BaseDict;

class AnalyzeVideoTypeModel extends BaseModel
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

    protected $table = "v_analyze_video_type";

    protected $primaryKey = "id";

    protected $fillable = [
    ];

    /**
     * @var array
     */
    protected $appends = [
        'type_cover_path',
    ];

    /**
     *
     * getTypeCoverPathAttribute
     *
     * @return mixed
     */
    public function getTypeCoverPathAttribute ()
    {
        return config('sys.static_url') . 'images/' . BaseDict::getExtend($this->global_type, 'type', $this->type_id);
    }



}