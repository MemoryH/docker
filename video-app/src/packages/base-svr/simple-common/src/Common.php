<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/6
 * Time: 2:50 PM
 */

namespace Basesvr\SimpleCommon;


class Common
{

    /**
     *
     * create_password
     *
     * @param int $pw_length
     * @return string
     */
    public static function createPassword(int $pw_length = 4){
        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++){
            $randpwd .= chr(mt_rand(33, 126));
        }
        return $randpwd;
    }

    /**
     *
     * generateUsername
     *
     * @param int $length
     * @return string
     */
    public static function generateUsername(int $length = 6) {
        // $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|';
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $password = 'User_';
        for ( $i = 0; $i < $length; $i++ ) {
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }

}