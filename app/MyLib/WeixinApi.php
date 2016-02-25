<?php

namespace App\MyLib

class WeixinApi 
{
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
            curl_setopt($ch, CURLOPT_POSTFIELDS, $datacube);
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
