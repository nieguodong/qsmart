<?php
/**
 * Created by PhpStorm.
 * User: guodongnie
 * Date: 2018/1/15
 * Time: 上午10:44
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Traits\QSAuthHelper;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;


class QSApiController extends Controller
{
    use QSAuthHelper, QSApiResponse;

    const SUCCESS = '0';

    const ERROR_USER_EXIST = '1001';
    const ERROR_VCODE = '1002';
    const ERROR_REGISTER_FAILED = '1003';
    const ERROR_LOGIN_FAILED = '1004';

    const MESSAGE_USER_EXIST = '用户已存在';
    const MESSAGE_VCODE = '验证码错误';
    const MESSAGE_REGISTER_FAILED = '注册失败';
    const MESAGE_LOGIN_FAILED = '登录失败';

}