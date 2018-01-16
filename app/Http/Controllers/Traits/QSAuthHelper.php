<?php
/**
 * Created by PhpStorm.
 * User: guodongnie
 * Date: 2018/1/15
 * Time: 下午12:20
 */

namespace App\Http\Controllers\Traits;

use GuzzleHttp\Client;
//use App\Exceptions\UnauthorizedException;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Validation\UnauthorizedException;

trait QSAuthHelper
{
    public function authenticate()
    {
        $client = new Client();


        try {
            $url = request()->root() . '/oauth/token';
            $params = array_merge(config('passport.proxy'), [
                'username' => request('email'),
                'password' => request('password'),
            ]);

            //return array(['url' => $url, 'params' => $params]);

            $respond = $client->request('POST', $url, ['form_params' => $params]);
        } catch (RequestException $exception) {
            return array(['param' => $exception->getCode()]);
            throw new UnauthorizedException('请求失败，服务器错误');
        }



        if ($respond->getStatusCode() !== 401) {
            return json_decode($respond->getBody()->getContents(), true);
        }

        throw new UnauthorizedException('账号或密码错误');
    }
}