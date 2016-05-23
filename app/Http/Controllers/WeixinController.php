<?php

namespace App\Http\Controllers;

use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MyLib\WeixinApi;

define("TOKEN", "xujijiguangxuxuewen123");

class WeixinController extends Controller
{
    public function test() {
        //$obj = new WeixinApi();
        //dd($obj->getSignature("http://xuji.yogiman.cn/weixin/test"));
        return view('weixin.test');
    }

    /* desc:    获得重要的数据
     * author:  xuyuji9000@163.com
     * ctime:   Tue Mar  8 14:36:41 CST 2016
     */
    public function getImpData() {
        $url = $_POST['url'];
        if(empty($url))
            return false;
        $obj = new WeixinApi();
        $data['signature'] = $obj->getSignature($url);
        $data['nonceStr'] = $obj->noncestr;
        $data['timestamp'] = $obj->timestamp;
        $data['appid'] = $_ENV['WEIXIN_APPID'];
        return json_encode($data);
    }

    /* desc:    接收微信访问信息,并响应
     * author:  xuyuji9000@163.com
     * etime:   Fri Feb 26 14:07:45 CST 2016
     */
    public function confirm() {
        if (isset($_GET['echostr'])) {
            $this->valid();
        }
        $this->responseMsg();
    }

    /* desc:    检验有效性
     * author:  xuyuji9000@163.com
     * etime:   Fri Feb 26 14:07:45 CST 2016
     */
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        	exit;
        }
    }


    private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

    /* desc:    微信响应
     * author:  xuyuji9000@163.com
     * etime:   Fri Feb 26 14:07:45 CST 2016
     */
    public function responseMsg()
    {
		//get post data, May be due to the different environments
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");

      	//extract post data
		if (!empty($postStr)){
            /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
               the best way is to check the validity of xml by yourself */
            libxml_disable_entity_loader(true);
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $msg_type = trim($postObj->MsgType); // 事件、文本、图片、视频、语音、位置、链接
            switch($msg_type) {
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "event":
                    if("LOCATION"==$postObj->Event)
                        $result = $this->receiveLocation($postObj);
                    break;
            }
            echo $result;
        }else {
        	echo "";
        	exit;
        }
    }


    // 返回文字信息
    private function receiveText($postObj) {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        Log::info("{$toUsername} receive text from {$fromUsername}.");
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>";             
        if(!empty( $keyword ))
        {
            $msgType = "text";
            $contentStr = "Welcome to wechat world!";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            return $resultStr;
        }else{
            // echo "Input something...";
            $msgType = "text";
            $contentStr = "Welcome to wechat world!";
            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
            return $resultStr;
        }
    }

    // 用户位置响应
    private function receiveLocation($postObj) {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <ArticleCount>1</ArticleCount>
                    <Articles>
                    <item>
                    <Title><![CDATA[%s]]></Title>
                    <Description><![CDATA[%s]]></Description>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    </Articles>
                    </xml>";             
        $msgType = "news";
        $itemTitle = "徐记激光焊导航";
        $itemDesc = " ";
        $url = "http://xuji.yogiman.cn/baidu/local";
        $data = array("Latitude"=>$postObj->Latitude, "Longitude"=>$postObj->Longitude);
        $url = $url."?".http_build_query($data);
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $itemTitle, $itemDesc, $url);
        return $resultStr;
        
    }

}
