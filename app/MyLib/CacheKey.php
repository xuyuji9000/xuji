<?php
/**
* 获取缓存key值
*/

namespace App\MyLib;


class CacheKey
{
	// 示例
	// static function get_order_key($str)
	// {
	// 	return md5(__FUNCTION__.$str."abc");
	// }
	
	// 检查微信消息重复 isDuplicate key
	static public function get_is_duplicate_key()
	{
		return __FUNCTION__."aa";
	}
}