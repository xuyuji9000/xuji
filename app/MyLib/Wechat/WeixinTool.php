<?php
namespace App\MyLib\Wechat;

/**
* 微信通用工具
*/
class WeixinTool
{
	/**
	 *	将数组转成xml格式
	 */
	static public function arrayToXml($arr)
	{
		$xml = "<xml>";
        foreach ($arr as $key=>$val)
        {
        	 if (is_numeric($val))
        	 {
        	 	$xml.="<".$key.">".$val."</".$key.">";

        	 }
        	 else
        	 	$xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
        }
        $xml.="</xml>";
        return $xml;
	}

	/**
	 * 将xml格式转换成数组格式
	 */
	static public function xmlToArray($xml)
	{
        libxml_disable_entity_loader(true);
		$obj = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
		$array =  (array) $obj;
		return $array;
	}

	/**
	 * 	作用：格式化参数，签名过程需要使用
	 */
	static function formatBizQueryParaMap($paraMap, $urlencode)
	{
		$buff = "";
		ksort($paraMap);
		foreach ($paraMap as $k => $v)
		{
		    if($urlencode)
		    {
			   $v = urlencode($v);
			}
			//$buff .= strtolower($k) . "=" . $v . "&";
			$buff .= $k . "=" . $v . "&";
		}
		$reqPar;
		if (strlen($buff) > 0)
		{
			$reqPar = substr($buff, 0, strlen($buff)-1);
		}
		return $reqPar;
	}

	/**
	 * 	作用：生成签名
	 */
	static public function getSign($Obj)
	{
		foreach ($Obj as $k => $v)
		{
			$Parameters[$k] = $v;
		}
		//签名步骤一：按字典序排序参数
		ksort($Parameters);
		$String = self::formatBizQueryParaMap($Parameters, false);
		//echo '【string1】'.$String.'</br>';
		//签名步骤二：在string后加入KEY
		$String = $String."&key=".$_ENV['WEIXIN_MCH_SECRET'];
		//echo "【string2】".$String."</br>";
		//签名步骤三：MD5加密
		$String = md5($String);
		//echo "【string3】 ".$String."</br>";
		//签名步骤四：所有字符转为大写
		$result_ = strtoupper($String);
		//echo "【result】 ".$result_."</br>";
		return $result_;
	}
}