<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MyLib\RedisFun;
class WeixinAuthController extends Controller
{
    // 测试函数
    public function test() {
        RedisFun::setStrValue('test', 'this is a test');
        RedisFun::deleteStrValue('test');
        $data = RedisFun::getStrValue('test');
        dd($data);
    }

    /**
        * @brief getAccessToken 
        *
        * @return 
     */
    public function getAccessToken() {
        $acc_key = 'access_token';
        $access_token = '';
        if(!($access_token = RedisFun::getStrValue($acc_key))) {
            $paras = [
                'grant_type' => 'client_credential', 
                'appid' => $_ENV['WEIXIN_APPID'],
                'secret' => $_ENV['WEIXIN_SECRET'],
            ];
            $url =  'https://api.weixin.qq.com/cgi-bin/token';
            $data = $this->sub_curl($url, $paras, false);
            $data = json_decode($data, true);
            RedisFun::setStrValue($acc_key, $data['access_token'], $data['expires_in']-1200);
            $access_token = $data['access_token'];
        }
        return $access_token;
    }

    private function sub_curl($url, $data, $is_post=1) {
        $ch = curl_init();
        if(!$is_post)//get 请求
        {
            $url =  $url.'?'.http_build_query($data);
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        if($is_post){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $info = curl_exec($ch);
        $code=curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $info;
    }
}
