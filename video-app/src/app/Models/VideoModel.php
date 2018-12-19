<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:26
 */

namespace App\Models;

use Basesvr\SimpleVideo\Dict;

class VideoModel extends BaseModel
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
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    protected $table = 'v_video';

    /**
     * @var array
     */
    protected $appends = [
        'status_label',
        'dist_label'
    ];

    /**
     *
     * getStatusLabelAttribute
     *
     * @param $value
     * @return mixed
     */
    public function getStatusLabelAttribute($value)
    {
        $status = [
            0 => '禁用',
            1 => '正常',
        ];
        return isset($status[$this->status]) ? $status[$this->status] : '';
    }

    /**
     *
     * getDistLabelAttribute
     *
     * @param $value
     * @return string
     */
    public function getDistLabelAttribute($value)
    {
        $filmDist = Dict::getFilmDist();
        $teleplay = Dict::getTeleplayDist();
        $cartoon = Dict::getCartoonDist();
        $variety = Dict::getVarietyDist();
        switch ($this->global_type) {
            case 'film' :
                return isset($filmDist[$this->dist_id]) ? $filmDist[$this->dist_id] : '';
                break;
            case 'teleplay' :
                return isset($teleplay[$this->dist_id]) ? $teleplay[$this->dist_id] : '';
                break;
            case 'cartoon' :
                return isset($cartoon[$this->dist_id]) ? $cartoon[$this->dist_id] : '';
                break;
            case 'variety' :
                return isset($variety[$this->dist_id]) ? $variety[$this->dist_id] : '';
                break;
        }
    }

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

    /**
     *
     * type
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function type()
    {
        return $this->hasMany(VideoTypeModel::class, 'video_id', 'id');
    }

    /**
     *
     * comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comment()
    {
        return $this->hasMany(VideoCommentModel::class, 'video_id', 'id');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(VideoCommentModel::class, 'commentable');
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function appraise()
    {
        return $this->hasMany(VideoAppraiseModel::class, 'video_id', 'id');
    }

}