<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/13
 * Time: 8:33 PM
 */

namespace Basesvr\SimpleUser;


use App\Models\UserEmpiricalHistoryModel;
use Basesvr\SimpleUser\Enums\EmpiricalEnum;

class UserExtend extends Base
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
     * User __clone.
     */
    public function __clone ()
    {
        die('Clone is not allowed.' . E_USER_ERROR);
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
     * addHistory
     *
     * @param int $user_id
     * @param string $event
     * @return bool
     * @throws \Exception
     */
    public static function addHistory (int $user_id, string $event)
    {
        if (!in_array($event, EmpiricalEnum::$getEventMap)) throw new \Exception('error : empirical history event is invalid.');

        UserEmpiricalHistoryModel::getInstance()->create([
            'user_id' => $user_id,
            'event'   => $event,
            'value'   => EmpiricalEnum::getEventValue($event)
        ]);
        return true;
    }



}