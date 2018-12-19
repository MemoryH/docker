<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/7 0007
 * Time: 16:26
 */

namespace App\Models;

class UserInviteCodeModel extends BaseModel
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

    protected $table = 'v_user_invite_code';

    protected $primaryKey = "id";

    protected $fillable = [
        'owner_user_id',
        'code',
        'status',
        'max',
        'uses',
        'valid_until'
    ];

}