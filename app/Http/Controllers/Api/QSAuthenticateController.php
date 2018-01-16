<?php
/**
 * Created by PhpStorm.
 * User: guodongnie
 * Date: 2018/1/15
 * Time: 上午10:52
 */

namespace App\Http\Controllers\Api;

use App\Http\Models\VCode;
use Illuminate\Support\Facades\DB;


class QSAuthenticateController extends QSApiController
{
    //use AuthenticatesUsers

    public function __construct()
    {
        $this->middleware('auth:api')->only([
            'logout'
        ]);
    }

    //注册
    public function register() {

        $phone = request('phone');
        $vcode = request('vcode');
        $pwd = request('password');


        $id = DB::table('users')->where('phone', $phone)->select('id')->first();

        //$res = VCode::checkVCode($phone, $vcode, VCode::REGISTER);
        $res['code'] = VCode::test();

        if ($res['code'] == true) { //验证码可用

            if (!$id) {

                $data = array('phone' => $phone, 'password' => MD5($pwd),
                                'created_at' => date('Y-m-d H:i:s'),
                                'updated_at' => date('Y-m-d H:i:s'),);

                $userId = DB::table('users')->insertGetId($data); //注册用户;
                return $userId ? array('code' => self::SUCCESS, 'user_id' => $userId)
                                : array('code' => self::ERROR_REGISTER_FAILED, 'message' => self::MESSAGE_REGISTER_FAILED);

            } else {

                return array('code' => self::ERROR_USER_EXIST, 'message' => self::MESSAGE_USER_EXIST);
            }

        }
        else {
            return array('code' => self::ERROR_VCODE, 'message' => self::MESSAGE_VCODE);
        }

    }

    public function login()
    {
        $phone = request('phone');
        $pwd = request('password');

        $where = array('phone' => $phone, 'password' => MD5($pwd));
        $user = DB::table('users')->where($where)->select('id')->first();

        if ($user) {

            return array('code' => self::SUCCESS, 'user_id' => $user->id);
        } else {
            return array('code' => self::ERROR_LOGIN_FAILED, 'message' => self::MESAGE_LOGIN_FAILED);
        }

    }

    /*
    // 登录
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'    => 'required|exists:user',
            'password' => 'required|between:6,16',
        ]);

        if ($validator->fails()) {
            $request->request->add([
                'errors' => $validator->errors()->toArray(),
                'code' => 401,
            ]);
            return $this->sendFailedLoginResponse($request);
        }

        $credentials = $this->credentials($request);

        if ($this->guard()->attempt($credentials, $request->has('remember'))) {
            return $this->sendLoginResponse($request);
        }

        return $this->setStatusCode(401)->failed('登录失败');
    }

    // 第三方登录
    public function redirectToProvider($driver) {

        if (!in_array($driver,['qq','wechat'])){

            throw new NotFoundHttpException;
        }

        return Socialite::driver($driver)->redirect();
    }

    // 第三方登录回调
    public function handleProviderCallback($driver) {

        $user = Socialite::driver($driver)->user();

        $openId = $user->id;

        // 第三方认证
        $db_user = User::where('xxx',$openId)->first();

        if (empty($db_user)){

            $db_user = User::forceCreate([
                'phone' => '',
                'xxUnionId' => $openId,
                'nickname' => $user->nickname,
                'head' => $user->avatar,
            ]);

        }

        // 直接创建token

        $token = $db_user->createToken($openId)->accessToken;

        return $this->success(compact('token'));

    }*/

    //调用认证接口获取授权码
    /*protected function authenticateClient(Request $request)
    {
        $credentials = $this->credentials($request);

        // 个人感觉通过.env配置太复杂，直接从数据库查更方便
        $password_client = Client::query()->where('password_client',1)->latest()->first();

        $request->request->add([
            'grant_type' => 'password',
            'client_id' => $password_client->id,
            'client_secret' => $password_client->secret,
            'username' => $credentials['phone'],
            'password' => $credentials['password'],
            'scope' => ''
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        $response = \Route::dispatch($proxy);

        return $response;
    }*/

    /*protected function authenticated(Request $request)
    {
        return $this->authenticateClient($request);
    }*/


/*
    protected function sendLoginResponse(Request $request)
    {
        $this->clearLoginAttempts($request);

        return $this->authenticated($request);
    }

    protected function sendFailedLoginResponse(Request $request)
    {
        $msg = $request['errors'];
        $code = $request['code'];
        return $this->setStatusCode($code)->failed($msg);
    }*/
}