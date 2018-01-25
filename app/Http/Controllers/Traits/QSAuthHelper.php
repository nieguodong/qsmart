<?php
/**
 * Created by PhpStorm.
 * User: guodongnie
 * Date: 2018/1/15
 * Time: 下午12:20
 */

namespace App\Http\Controllers\Traits;

use App\Http\Controllers\Api\QSApiController;
use GuzzleHttp\Client;
//use App\Exceptions\UnauthorizedException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Validation\UnauthorizedException;

trait QSAuthHelper
{
    public function authenticate($phone, $pwd)
    {
        $client = new Client();


        try {
            $url = request()->root() . '/oauth/token';
            $params = array_merge(config('passport.proxy'), [
                'username' => $phone,//request('email'),
                'password' => MD5($pwd),//request('password'),
            ]);

            //return array(['url' => $url, 'params' => $params]);

            $respond = $client->request('POST', $url, ['form_params' => $params]);
        } catch (RequestException $exception) {

            return array('errorcode' => $exception->getMessage());
            return false;
           // return array(['param' => $exception->getCode()]);
            //throw new UnauthorizedException('请求失败，服务器错误');
        }

        if ($respond->getStatusCode() !== 401) {
            return json_decode($respond->getBody()->getContents(), true);
        }else {

            return false;
        }

        //throw new UnauthorizedException('账号或密码错误');
    }
}