<?php
/**
 * Created by PhpStorm.
 * User: pro
 * Date: 2018/12/7
 * Time: 3:39 PM
 */

namespace App\Http\Controllers\Api;

use Basesvr\SimpleUser\Enums\CoinsEnum;
use Basesvr\SimpleUser\Enums\EmpiricalEnum;
use Basesvr\SimpleUser\Events\UserLoginEvent;
use Hash;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Validation\ValidationException;
use Basesvr\SimpleCommon\Utils\ReturnJson;

class LoginController extends Controller
{
    use ThrottlesLogins;

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     * @throws ValidationException
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->hasTooManyLoginAttempts($request)) {
            return $this->sendLockoutResponse($request);
        }

        return $this->attempLogin($request);

    }

    /**
     * @param Request $request
     */
    public function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => 'required|string',
            'password' => 'required|string'
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     */
    protected function attempLogin(Request $request)
    {
        $this->incrementLoginAttempts($request);

        \DB::beginTransaction();
        try {
            $user = User::where('email', $request->email)->first();

            if (!$user || !Hash::check($request->password, $user->password)) {
                return $this->sendFailedLoginResponse($request);
            }

            $api_token = md5(uniqid($user->id, true)) . ' ' . uniqid($user->id, true);
            $user->api_token = $api_token;
            $user->save();

            $params['empirical_event'] = EmpiricalEnum::EVENT_DAILY_LOGINED;
            event(new UserLoginEvent($user->id, 'login', $params ?? []));

            \DB::commit();
        } catch (\Exception $e) {

            \DB::rollBack();
            return ReturnJson::fail('用户名不存在或密码错误!',403);
        }


        return $this->sendLoginResponse($request, $user);
    }

    /**
     * @param Request $request
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request, User $user)
    {
        $this->clearLoginAttempts($request);

        return ReturnJson::success([
            'user' => $user,
            'token' => $user->api_token
        ]);
    }

    public function username()
    {
        return 'email';
    }
}