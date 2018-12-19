<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:14
 */

namespace Basesvr\SimpleVideo;

use App\Models\VideoAdModel;

class VideoAd extends Base
{

    /**
     * @var $_instance null
     */
    private static  $_instance = null;

    /**
     * User constructor.
     */
    private function __construct ()
    {
    }

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
     * @param $columns
     * @param int $page
     * @param int $limit
     * @return mixed
     * @throws \Exception
     */
    public function lists (array $search, array $orders, $columns, int $page, int $limit)
    {
    }

    /**
     *
     * info
     *
     * @param int $id
     * @param array $params
     * @return mixed
     */
    public function info (int $id, array $params)
    {

    }

    /**
     *
     * adLists
     *
     * @param string $key
     * @param array $params
     * @return mixed
     */
    public function adLists (string $key, array $params = [])
    {
        $search = ['key' => $key] + $params;
        return VideoAdModel::getInstance()->where($search)->get();
    }


    /**
     * 添加轮播
     * @param array $params
     * @return mixed
     */
    public function setVideoBroadcast(array $params){

        return  VideoAdModel::getInstance()->insert($params);
    }


    /**
     * 编辑轮播
     * @param int $id
     * @param array $params
     * @return mixed
     */
    public function editVideoBroadcast(int $id,array $params){

        return VideoAdModel::getInstance()->where('id',$id)->update($params);
    }

    public function delVideoBroadcast(int $id){
        return VideoAdModel::getInstance()->where('id',$id)->delete();
    }

}