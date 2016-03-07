<?php

namespace App\MyLib;

use App\MyLib\RedisFun;

class WeixinApi 
{
    private $_access_token_key = '_rd_acc_token';
    private $due_time = 7000;

    /*
     * desc:    获取access_token
     * author:  xuyuji9000@163.com
     * ctime:   Thu Feb 25 15:04:30 CST 2016
     */
    public function getAccessToken() {
        $url="https://api.weixin.qq.com/cgi-bin/token";
        $par['grant_type'] = 'client_credential';
        $par['appid'] = 'wx2ed90fa37503aa8a';
        $par['secret'] = 'cf184a14ce775bbcf2797c018fe4adbd';
        
        // 先检查有没有缓存, 没有缓存调用接口
        $access_token = RedisFun::getStrValue($this->_access_token_key);
        if(empty($access_token)){
            $data = $this->sub_curl($url, $par, 0);
            $data = json_decode($data, true);
            $access_token = $data['access_token'];
            RedisFun::setStrValue($this->_access_token_key, $access_token, $this->due_time);
        }
        return $access_token;
    }

    /* desc:    请求url获取数据
     * author:  xuyuji9000@163.com
     * ctime:   Thu Feb 25 15:48:42 CST 2016
     */
    private function sub_curl($url, $data, $is_post=1) {
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
}
