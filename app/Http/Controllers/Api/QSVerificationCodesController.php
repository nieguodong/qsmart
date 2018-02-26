<?php

namespace App\Http\Controllers\Api;

use App\Http\Models\VCode;
use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;


class QSVerificationCodesController extends QSApiController
{
    //普通验证码
    public function request(VerificationCodeRequest $request) {

        $phone = $request->phone;

        if (!app()->environment('production')) {
            $vcode = '1234';
        }
        else {

            $vcode = VCode::requestVCode($phone, VCode::REGISTER);
        }

        $key = 'verificationCode_'.str_random(15);
        $expiredAt = now()->addMinutes(3);

        \Cache::put($key, ['phone' => $phone, 'code' => $vcode], $expiredAt);

        return $this->response->array([
                                        'key' => $key,
                                        'expired_at' => $expiredAt->toDateTimeString(),
                                        ])->setStatusCode(201);
    }

    //图片验证码
    public function requireCaptcha(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
