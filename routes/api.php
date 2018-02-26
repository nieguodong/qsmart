<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

//Route::post('/user/login', 'Api\QSAuthenticateController@login')->name('login');
//Route::post('/user/register', 'Api\QSAuthenticateController@register')->name('register');

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' => 'serializer:array'
], function($api) {

    $api->group(
        ['middleware' => 'api.throttle',
            'limit' => config('api.rate_limits.sign.limit'),
            'expires' => config('api.rate_limits.sign.expires'),],

        function ($api) {

        // 短信验证码
        $api->post('verificationCodes', 'QSVerificationCodesController@request')
            ->name('api.verificationCodes.request');

        // 图片验证码
        $api->post('captchas', 'QSVerificationCodesController@requireCaptchas')
            ->name('api.captchas.requireCaptchas');

        //用户注册
        $api->post('users', 'QSUsersController@register')->name('api.users.register');

        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'QSUsersController@socialLogin')
            ->name('api.socials.authorizations.login');

        // 登录
        $api->post('authorizations', 'QSUsersController@login')
            ->name('api.authorizations.login');

        // 刷新token
        $api->put('authorizations/token', 'QSUsersController@updateToken')
            ->name('api.authorizations.update');

        // 删除token
        $api->delete('authorizations/token', 'QSUsersController@destroyToken')
            ->name('api.authorizations.destroy');


        $api->group(['middleware' => 'api.auth'], function ($api) {

            $api->get('user', 'QSUsersController@me')
                ->name('api.user.show');

            $api->post('images', 'QSImagesController@submit')
                ->name('api.images.submit');

        });

    });
});

