<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/13
 * Time: 8:33 PM
 */

namespace Basesvr\SimpleUser;


use App\Models\UserInviteCodeModel;
use App\Models\UserInviteHistoryModel;
use Doorman;

class UserInvitation extends Base
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
     * storeInviteCode
     *
     * @param array $params
     * @return bool
     */
    public function storeInviteCode (array $params = [])
    {
        Doorman::generate()->owner($params['user_id'])->make();
        return true;
    }

    /**
     *
     * addInviteHistory
     *
     * @param int $user_id
     * @param string $code
     */
    public function addInviteHistory (int $user_id, string $code)
    {
        Doorman::redeem($code);
        $model = UserInviteCodeModel::getInstance()->where(['code' => $code])->first();
        UserInviteHistoryModel::getInstance()->create([
            'code' => $code,
            'owner_user_id' => $model->owner_user_id,
            'use_user_id' => $user_id
        ]);
    }



}