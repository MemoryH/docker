<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/8
 * Time: 10:16 AM
 */

namespace Basesvr\SimpleVideo;


abstract class Base
{

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
     */
    abstract public function lists (array $search, array $orders, $columns, int $page, int $limit);

    /**
     *
     * info
     *
     * @param int $id
     * @param array $params
     * @return mixed
     */
    abstract public function info (int $id, array $params);

    /**
     *
     * order
     *
     * @param array $orders
     * @param $query
     * @return mixed
     */
    protected function order (array $orders, $query)
    {
        if (is_array($orders[0])) foreach ($orders as $value) {
            $query = $query->orderBy($value[0], $value[1]);
        } else {
            $query = $query->orderBy($orders[0], $orders[1]);
        }
        return $query;
    }

    /**
     *
     * checkGlobalType
     *
     * @param string $param
     * @throws \Exception
     */
    protected function checkGlobalType (string $param)
    {
        $globalTypeArrs = array_flip(Dict::getGlobalType());
        if (!in_array($param, $globalTypeArrs)) {
            throw new \Exception('global_type for error.');
        }
    }

}