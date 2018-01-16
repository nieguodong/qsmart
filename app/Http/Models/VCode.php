<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\ThirdLibrary\Ucpaas;

class VCode extends Model
{
    //

    const ACCOUNTSID = 'bee116cd3223f452b5c79a80536b6cfb';
    const AUTHTOKEN = '798fbb499cf365253a2d84663a74f04c';
    const APPID = '1a8b0c5629cc4bfaa404f349d90c0abf';
    const TEMPLATEID = '66819';

    const REGISTER = 0;

    /**
     * 将验证码添加到数据库
     * @param null $phone
     * @return mixed
     */
    public static function requestVCode($phone=null,$type=null){

        if(VCode::is_mobile($phone)){
            $data['phone'] = $phone;
            $data['vcode'] = VCode::get_rand_str();
            //$data['content'] = '您的验证码为：'.$data['vcode'].'，30分钟内有效。';
            $data['time_add'] = time();
            if($type){
                $data['type'] = $type;
            }

            DB::table('vcodes')->insertGetId($data);
            self::sendSms($phone, $data['vcode']);

            return array('code' => '0');
        }else{
            return array('code' => '1006');
        }
    }

    /**
     *@name 产生N位随机字符
     *@param $length 多少位随机字符
     *@return string
     */
    static function get_rand_str($length=6) {
        $str = '1234567890';
        $str_num = strlen($str) - 1;
        $return_str = '';
        for ($i = 0; $i < $length; $i++) {
            $num = mt_rand(0, $str_num);
            $return_str.=$str[$num];
        }
        return $return_str;
    }

    /**
     * 判断是否是合法的手机号码
     */
    static function is_mobile($mobileNumer)
    {
        $mobileNumer = trim($mobileNumer);
        if (strlen($mobileNumer) != 11) {
            return FALSE;
        }
        $r = preg_match('/^((\+?86)|\(\+?86\))?0?1(0|3|4|5|6|7|8)(\d){9}$/', $mobileNumer);
        return $r ? TRUE : FALSE;
    }

    static function sendSms($phone, $code) {

        //初始化必填
        $options['accountsid'] = VCode::ACCOUNTSID;
        $options['token'] = VCode::AUTHTOKEN;

        //初始化 $options必填
        $ucpass = new Ucpaas($options);

        //短信验证码（模板短信）,默认以65个汉字（同65个英文）为一条（可容纳字数受您应用名称占用字符影响），超过长度短信平台将会自动分割为多条发送。分割后的多条短信将按照具体占用条数计费。
        $appId = VCode::APPID;
        $to = $phone;
        $templateId = VCode::TEMPLATEID;
        $param = $code;

        $ucpass->templateSMS($appId,$to,$templateId,$param);
    }

    /**
     * 验证 验证码
     * @param null $phone
     * @param null $code
     * @return int
     */
    public static function checkVCode($phone=null,$code=null,$type=null){

        $where = array('phone'=>$phone);

        if($type){
            $where['type'] = $type;
        }

        $data = DB::table('vcodes')
            ->where($where)
            ->orderBy('id', 'desc')
            ->first(); //查询验证码

        if($data){
            if($data->vcode == intval($code)){
                //DB::table('vcodes')->where('id',$data->id)->update(['use' => 1]); //表示使用了验证码
                $ret['code'] = true;
            }else{
                $ret['code'] = false;
            }
        }else{
            $ret['code'] = false;
        }

        return $ret;
    }

    public static function test() {

        return true;
    }
}
