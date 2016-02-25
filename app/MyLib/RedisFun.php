<?php
namespace App\MyLib;

use Redis;

class RedisFun extends Redis {

    /* desc:    设定字符串缓存
     * author:  xuyuji9000@163.com
     * ctime:   Thu Feb 25 19:27:16 CST 2016
     */
    public static function setStrValue($key, $value, $due_time=false) {
        $due_time = intval($due_time);
        if($due_time) {
            Redis::set($key,$value);
            Redis::expire($key,$due_time);
        } else {
            Redis::set($key,$value);
        }
    }

    /* desc:    获取字符串缓存
     * author:  xuyuji9000@163.com
     * ctime:   Thu Feb 25 19:27:16 CST 2016
     */
    public static function getStrValue($key) {
        $value = Redis::get($key);
        return $value;
    }
}
