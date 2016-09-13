<?php
namespace App\MyLib;


use App\MyLib\WeixinApi;
use App\MyLib\WeixinTool;
/**
* 微信支付
*/
class WeixinPay
{
	private $base_url = "https://api.mch.weixin.qq.com/"; //unified order basic url
	private var $parameters;	//请求参数，类型为关联数组

	/**
	 * 	作用：设置请求参数
	 */
	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

	/**
	 * 统一下单
	 * @param fee 价格
	 */
	public function unifiedorder()
	{
		$par = array();
		$par['appid'] = $_ENV['WEIXIN_APPID'];
		$par['mch_id'] = $_ENV['WEIXIN_MCH_ID'];
		$par['nonce_str'] = $this->createNonceStr();
		$par['body'] = "徐记激光焊接-充值";
		$par['out_trade_no'] = time().$this->createNonceStr(8);
		$par['total_fee'] = 1;
		$par['spbill_create_ip'] = "115.28.0.14";
		$par['notify_url'] = "xuji.yogiman.cn/pay/payment";
		$par['trade_type'] = "NATIVE";
		$par['product_id'] = 123;


		$par['sign'] = WeixinTool::getSign($par);
		$xml = WeixinTool::arrayToXml($par);
		// var_dump($par['sign']);
  //       $payTpl =  "<xml>
		// 			   <appid><![CDATA[%s]]></appid>
		// 			   <mch_id><![CDATA[%s]]></mch_id>
		// 			   <nonce_str><![CDATA[%s]]></nonce_str>
		// 			   <body><![CDATA[%s]]></body>
		// 			   <out_trade_no><![CDATA[%s]]></out_trade_no>
		// 			   <total_fee><![CDATA[%d]]></total_fee>
		// 			   <spbill_create_ip><![CDATA[%s]]></spbill_create_ip>
		// 			   <notify_url><![CDATA[%s]]></notify_url>
		// 			   <trade_type><![CDATA[%s]]></trade_type>
		// 			   <product_id><![CDATA[%s]]></product_id>
		// 			   <sign><![CDATA[%s]]></sign>
		// 			</xml>";
		// $result = "";
        // $result = vsprintf($payTpl, $par);
        // var_dump($payTpl);
        // var_dump($par);
        // var_dump($result);
        $result =  WeixinApi::sub_curl($this->base_url, $xml);
        return $result;
	}

	/**
	 * 获得签名
	 */
	// public function getSign($par)
	// {
	// 	$temp = array_filter($par);
	// 	ksort($temp);
	// 	var_dump($temp);
	// 	$temp = http_build_query($temp);
	// 	$stringSignTemp = "";
	// 	$stringSignTemp=$temp."&key=".$_ENV['WEIXIN_MCH_SECRET'];
	// 	$stringSignTemp=strtoupper(MD5($stringSignTemp));
	// 	return $stringSignTemp;
	// }

	/**
	 * 创建随机字符串
	 */
	public function createNonceStr($length = 16) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * 支付
     */
    public function payment()
    {
    }
}