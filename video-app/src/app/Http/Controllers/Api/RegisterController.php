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
use App\Models\UserExtendModel;
use Hash;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Basesvr\SimpleCommon\Utils\ReturnJson;
use Basesvr\SimpleCommon\Common;
use Basesvr\SimpleUser\Events\UserRegisterEvent;

class RegisterController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     * @throws ValidationException
     */
    public function register (Request $request)
    {

        $res = $this->validateRegister($request);
        if ($res){
            return ReturnJson::fail($res);
        }

        return $this->attempRegister($request);
    }

    /**
     * @param Request $request
     */
    public function validateRegister (Request $request)
    {
        $rules = [
            $this->username() => 'required|string|email|max:255',
            'password' => 'required|string|min:6|confirmed',
            'invite_code' => 'string|min:6|max:6|exists:v_user_invite_code,code',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $validator->errors()->all();
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\Response|void
     */
    protected function attempRegister (Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return $this->sendFailedRegisterResponse($request);
        }

        $params = [
            'email' => $request->email,
            'password' => $request->password,
            'invite_code' => $request->invite_code
        ];

        if (isset($request->invite_code))
            $params = $params + ['invite_code' => $request->invite_code];
        $ret = $this->create($params);

        return $this->sendRegisterResponse($ret);
    }

    /**
     * @param Request $request
     */
    protected function sendFailedRegisterResponse(Request $request)
    {
        return ReturnJson::fail('邮箱已存在!');
    }

    /**
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\Response
     */
    protected function sendRegisterResponse($result)
    {
        return ReturnJson::success($result);
    }

    public function username()
    {
        return 'email';
    }

    /**
     *
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return mixed
     * @throws \Exception
     */
    protected function create(array $data)
    {
        \DB::beginTransaction();
        try {
            $ret = User::create([
                'name' => $data['name'] ?? Common::generateUsername(),
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
            ]);
            UserExtendModel::create([
                'user_id' => $ret->id,
            ]);

            $params['empirical_event'] = EmpiricalEnum::EVENT_REGISTER;
            $params['coins_event'] = CoinsEnum::EVENT_REGISTER;
            if (isset($data['invite_code'])) $params['invite_code'] = $data['invite_code'];

            event(new UserRegisterEvent($ret->id, 'register', $params ?? []));

            \DB::commit();
        } catch (\Exception $e) {
            \DB::rollBack();
            throw new \Exception('register dbcreate failed for : ' . $e->getMessage());
        }
        return $ret;
    }
}