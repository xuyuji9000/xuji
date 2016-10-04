<?php
use App\MyLib\WeixinTool;
/**
* 微信支付响应
*/
class WeixinPayResponse
{

	public function callback()
	{
		$returnMsg = array();

        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify = WeixinTool::xmlToArray($xml);
        $sign = WeixinTool::getSign($notify);//本地签名

        $signStatus = $sign == $notify['sign']; // 本地签名 == 线上签名


        if(FALSE == $signStatus)
        {
            $returnMsg["return_code"] = "FAIL"; //返回状态码
            $returnMsg["return_msg"] = "签名失败"; //返回信息
        } else {
            $returnMsg["return_msg"] = "SUCCESS"; //返回状态码
        }

        $returnXml = WeixinTool::arrayToXml($returnMsg);
        return $returnXml;
	}
}