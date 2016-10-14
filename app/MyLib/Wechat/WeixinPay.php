<?php
namespace App\MyLib\Wechat;


use App\MyLib\Wechat\WeixinApi;
use App\MyLib\Wechat\WeixinTool;

use App\Picture;
use \Milon\Barcode\DNS2D;
/**
* 微信支付
*/
class WeixinPay
{
	private $url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
	private $parameters;	//请求参数，类型为关联数组

	function __construct($fee=1, $name="徐记激光焊接-充值")
	{
		$this->fee = $fee;
		$this->name = $name;
	}

	/**
	 * 	作用：设置请求参数
	 */
	function setParameter($parameter, $parameterValue)
	{
		$this->parameters[$this->trimString($parameter)] = $this->trimString($parameterValue);
	}

    /**
     *  统一下单
     * @param fee 价格
     * @param name 支付名称
     * @return array
     */
	public function unifiedOrder()
	{
        $result =  WeixinApi::sub_curl($this->url, WeixinTool::arrayToXml($this->getPayParameters()));
        return WeixinTool::xmlToArray($result);
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
     * @return string
     */
    public function getQRCode()
    {
        $qrcode  = new DNS2D();
        dd($this->unifiedOrder()['code_url']);
        // $qrcode->getBarcodePNGPath( $this->unifiedOrder()['code_url'], "QRCODE");
        // return $this->storeQRCode($qrcode->getBarcodePNGPath( $this->unifiedOrder()['code_url'], "QRCODE"));
    }

    /**
     * @return array
     */
    private function getPayParameters()
    {
        $par = array();
        $par['appid'] = $_ENV['WEIXIN_APPID'];
        $par['mch_id'] = $_ENV['WEIXIN_MCH_ID'];
        $par['nonce_str'] = $this->createNonceStr();
        $par['body'] = $this->name;
        $par['out_trade_no'] = time() . $this->createNonceStr(8);
        $par['total_fee'] = $this->fee;
        $par['spbill_create_ip'] = "115.28.0.14";
        $par['notify_url'] = "xuji.yogiman.cn/pay/payment";
        $par['trade_type'] = "NATIVE";
        $par['product_id'] = 123;
        $par['sign'] = WeixinTool::getSign($par);
        return $par;
    }

    /**
     * @param $path
     * @return mixed
     */
    private function storeQRCode($path)
    {
        $picture = new Picture();
        $id = $picture->uploadFile($path);
        return !$id ? false : $id;
    }
}