<?php
namespace App\MyLib;

use App\MyLib\WeixinApi;

/**
* 微信网页授权
*/
class WeixinAuth 
{
	private static $auth_url = "https://open.weixin.qq.com/connect/oauth2/authorize";
	private static $code_url = "https://api.weixin.qq.com/sns/";

	/**
	 * 构建用户授权的链接
	 * @param  string $redirect_uri 授权后重定向的回调链接地址
	 * @param  string $scope        网页授权类型 snsapi_base || snsapi_userinfo
	 * @param  string $state        重定向链接的参数
	 * @return string               用户授权链接
	 */
	public static function getAuthUrl($redirect_uri, $scope, $state='') {
		$query = '';
		$query .= "appid=".$_ENV['WEIXIN_APPID'];
		$query .= "&redirect_uri=".urlencode($redirect_uri);
		$query .= "&response_type=code";
		$query .= "&scope=".$scope;
		if($state) 
			$query .= "&state=".$state;
		return self::$auth_url.'?'.$query.'#wechat_redirect';
	}

	/**
	 * 获取用户的openid
	 * @param  string $code 上一步获取到的$_GET['code']
	 * @return object       包含access_token && openid的对象
	 * @url 				https://api.weixin.qq.com/sns/oauth2/access_token
	 */
	public static function getOpenid($code) {
		$url_part = "oauth2/access_token"; // 特有的url结构
		$query = "";
		$query .= "appid=".$_ENV['WEIXIN_APPID']; 
		$query .= "&secret=".$_ENV['WEIXIN_SECRET']; 
		$query .= "&code=".$code; 
		$query .= "&grant_type=authorization_code";
		$url = self::$code_url.$url_part."?".$query;
		$data = WeixinApi::sub_curl($url, array(), 1);
		$data = json_decode($data, true);
		$data = json_decode(json_encode($data));
		return   $data;
	}

	/**
	 * 获取用户详细信息
	 * @param  object $obj 	上一步操作的返回值Object 包含openid, access_token
	 * @return object 	 	用户的消息信息
	 * @url 				https://api.weixin.qq.com/sns/userinfo      
	 */
	public static function getDetailInfo($obj)
	{
		$url_part = "userinfo"; // 特有的url结构
		$query = "";
		$query .= "access_token=".$obj->access_token;
		$query .= "&openid=".$obj->openid;
		$query .= "&lang=zh_CN";
		$url = self::$code_url.$url_part."?".$query;
		$data = WeixinApi::sub_curl($url, array(), 1);
		$data = json_decode($data, true);
		$data = json_decode(json_encode($data));
		return $data;
	}
}