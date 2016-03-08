<?php

namespace App\MyLib;

use App\MyLib\RedisFun;

class WeixinApi 
{
    public $noncestr;
    public $timestamp;
    /*
     * desc:    获取access_token
     * author:  xuyuji9000@163.com
     * ctime:   Thu Feb 25 15:04:30 CST 2016
     */
    public static function getAccessToken() {
        $access_token_key = '_rd_acc_token';
        $url="https://api.weixin.qq.com/cgi-bin/token";
        $par['grant_type'] = 'client_credential';
        $par['appid'] = $_ENV['WEIXIN_APPID'];
        $par['secret'] = $_ENV['WEIXIN_SECRET'];
        
        // 先检查有没有缓存, 没有缓存调用接口
        $access_token = RedisFun::getStrValue($access_token_key);
        if(empty($access_token)){
            $data = self::sub_curl($url, $par, 0);
            $data = json_decode($data, true);
            $access_token = $data['access_token'];
            RedisFun::setStrValue($access_token_key, $access_token, $data['expires_in'] - 1200);
        }
        return $access_token;
    }

    /* desc:    请求url获取数据
     * author:  xuyuji9000@163.com
     * ctime:   Thu Feb 25 15:48:42 CST 2016
     */
    public static function sub_curl($url, $data, $is_post=1) {
        $ch = curl_init();
        if(!$is_post)//get 请求
        {
            $url = $url.'?'.http_build_query($data);
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, $is_post);
        if($is_post) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        $info = curl_exec($ch);
        curl_close($ch);
        return $info;
    }

    public function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public function getJsapiTicket() {
        $jsapi_ticket_key = '_rd_jsapi_ticket';
        $url="https://api.weixin.qq.com/cgi-bin/ticket/getticket";
        $par['access_token'] = WeixinApi::getAccessToken();
        $par['type'] = 'jsapi';
        $jsapi_ticket = RedisFun::getStrValue($jsapi_ticket_key);
        if(empty($jsapi_ticket)){
            $data = self::sub_curl($url, $par, 0);
            $data = json_decode($data, true);
            $jsapi_ticket = $data['ticket'];
            RedisFun::setStrValue($jsapi_ticket_key, $jsapi_ticket, $data['expires_in'] - 1200);
        }
        return $jsapi_ticket;
    }

    public function getSignature($url) {
        if(empty($url))
            return false;
        $par['jsapi_ticket'] = $this->getJsapiTicket();
        $par['noncestr'] = $this->createNonceStr();
        $par['timestamp'] = time();
        $par['url'] = $url;
        $this->noncestr = $par['noncestr'];
        $this->timestamp = $par['timestamp'];
        $str = "jsapi_ticket=".$par['jsapi_ticket']."&noncestr=".$par['noncestr']."&timestamp=".$par['timestamp']."&url=".$par['url'];
        return sha1($str);
    }
}
