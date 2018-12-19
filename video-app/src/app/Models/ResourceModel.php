<?php
namespace App\Models;

class ResourceModel extends BaseModel
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

    protected $table = "v_resource";

    protected $primaryKey = "id";

    protected $fillable = [
    ];

//    /**
//     * @var array
//     */
//    protected $appends = [
//        'video_list',
//    ];
//
//    /**
//     *
//     * getStatusLabelAttribute
//     *
//     * @param $value
//     * @return mixed
//     */
//    public function getVideoListAttribute ($value)
//    {
//        dd(123,$value);
//        $status = [
//            0 => '禁用',
//            1 => '正常',
//        ];
//        return isset($status[$this->status])?$status[$this->status]:'';
//    }



}