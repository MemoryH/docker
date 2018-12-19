<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:26
 */

namespace App\Models;

class UserCollectModel extends BaseModel
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

    protected $table = 'v_user_collect';

    protected $primaryKey = "id";

    protected $fillable = [
        'user_id',
        'video_id',
        'global_type'
    ];
    /**
     *
     * author
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function author()
    {
        return $this->hasMany(VideoAuthorModel::class, 'video_id', 'id');
    }

    /**
     *
     * videoResource
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function resource()
    {
        return $this->hasMany(VideoResourceModel::class, 'video_id', 'id');
    }
}