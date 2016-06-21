<?php
/**
 * @author yogiman <karl.yogi.xu@gmail.com>
 */
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


    /* desc:    删除key缓存
     * author:  xuyuji9000@163.com
     * ctime:   Mon Mar  7 13:51:45 CST 2016
     */
    public static function deleteStrValue($key) {
        Redis::del($key);
    }

    /**
     * 设置数组缓存
     * @param String $key      缓存键值
     * @param array $value    缓存数组
     * @param int $due_time 缓存expire时间
     */
    public static function setArrayValue($key, $value, $due_time=false) {
        $due_time = intval($due_time);
        $value = json_encode($value);
        if($due_time)
        {
            Redis::set($key, $value);
            Redis::expire($key, $due_time);
        } else {
            Redis::set($key,$value);
        }
    }

    /**
     * 获取数组缓存
     * @param  string $key 缓存键值
     * @return array      缓存数组
     * @createtime  2016/06/06 周一
     */
    public static function getArrayValue($key)
    {
        $value = Redis::get($key);
        return json_decode($value, true);
    }

    /**
     * 删除缓存
     * @param  string $key 缓存键值
     * @return none      none
     * @createtime  2016/06/06 周一
     */
    public static function deleteValue($key) 
    {
        Redis::del($key);
    }
}
