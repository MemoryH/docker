<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/7
 * Time: 5:32 PM
 */

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Basesvr\SimpleUser\User;
use Basesvr\SimpleCommon\Utils\ReturnJson;

class UserController extends Controller
{

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|confirmed|min:6',
        ]);

        User::getInstance()->resetPassword(['email' => $request->email, 'password' => $request->password]);

        return ReturnJson::success('', trans('common.password_reset_success'));
    }

}