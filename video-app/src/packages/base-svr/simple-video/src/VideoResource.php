<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:14
 */

namespace Basesvr\SimpleVideo;

use App\Models\ResourceModel;
use App\Models\VideoResourceModel;

class VideoResource extends Base
{


    protected $_resourceModel;

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

    /**
     *
     * lists
     *
     * @param array $search
     * @param array $orders
     * @param array $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws \Exception
     */
    public function lists(array $search, array $orders = ['created','desc'], $columns = ['*'], int $page = 1, int $limit = 15)
    {
    }

    /**
     *
     * info
     *
     * todo
     *
     * @param int $video_id
     * @param array $params
     * @return VideoModel
     */
    public function info (int $video_id, array $params = [])
    {
        $resource = \Cache::rememberForever('resourceLists', function() {
            $ret = ResourceModel::getInstance()->orderBy('sort','asc')->get()->toArray();
            return $ret;
        });
        array_walk($resource, function (&$v, $k) use ($video_id, $resource) {
            $ret = VideoResourceModel::getInstance()->where(['video_id' => $video_id, 'resource_id' => $v['id']])->get();
            $v['video_list'] = $ret;
            $v['video_total'] = count($ret);
        });
        foreach ($resource as $k => $v) if ($v['video_total'] == 0) unset($resource[$k]);
        return array_values($resource);
    }

}