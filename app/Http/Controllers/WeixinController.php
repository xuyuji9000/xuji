<?php

namespace App\Http\Controllers;

use Log;
// use Redis;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\MyLib\WeixinApi;
use App\MyLib\RedisFun;
use App\MyLib\CacheKey;

use App\Fan;


define("TOKEN", "xujijiguangxuxuewen123");

class WeixinController extends Controller
{
    public function test() {
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
            if($this->isDuplicate($postObj))    // 排重
            {
                echo "";
                exit();
            }
            $this->addNewFans($postObj->FromUserName);
            $msg_type = trim($postObj->MsgType); // 事件、文本、图片、视频、语音、位置、链接
            $result = "";
            switch($msg_type) {
                case "text":
                    $result = $this->receiveText($postObj);
                    break;
                case "event":
                    if("LOCATION"==$postObj->Event)
                        $result = $this->receiveLocation($postObj);
                    if("subscribe"==$postObj->Event)
                        Log::info("{$postObj->FromUserName} subscribed!");
                    if("unsubscribe"==$postObj->Event)
                        Log::info("{$postObj->FromUserName} unsubscribed!");
                    break;
                case "image":
                    // Log::info(trim($postObj->MediaId));
                    $result = $this->receiveImage($postObj);
                    break;
                case "shortvideo":
                    // Log::info(trim($postObj->MediaId));
                    $result = $this->receiveShortVedio($postObj);
                    break;
                case "video":
                    // Log::info(trim($postObj->MediaId));
                    $result = $this->receiveVedio($postObj);
                    break;
                case "link":
                    // Log::info(trim($postObj->Url));
                    $result = $this->receiveLink($postObj);
                    break;
                default:
                    $result = $this->receiveDefault($postObj);
            }
            // if(empty($result))
            //     $result = '';
            echo $result;
        }else {
        	echo "";
        	exit;
        }
    }

    /**
     * 链接返回值
     * @param  class $postObj 微信返回类
     * @return string          返回字符串
     */
    private function receiveLink($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        Log::info("{$toUsername} receive link from {$fromUsername}.");

        $contentStr = "Received Link!";
        $resultStr = $this->formatText($postObj, $contentStr);
        return $resultStr;
    }

    /**
     * 视频返回值
     * @param  class $postObj 微信返回类
     * @return string          返回字符串
     */
    private function receiveVedio($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        Log::info("{$toUsername} receive vedio from {$fromUsername}.");

        $contentStr = "Received Vedio!";
        $resultStr = $this->formatText($postObj, $contentStr);
        return $resultStr;
    }

    /**
     * 默认返回
     * @param  class $postObj 微信返回类
     * @return string          返回字符串
     */
    private function receiveDefault($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        Log::info("{$toUsername} receive default from {$fromUsername}.");

        $contentStr = "Default Response!";
        $resultStr = $this->formatText($postObj, $contentStr);
        return $resultStr;
    }

    /**
     * 短视频返回值
     * @param  class $postObj 微信返回类
     * @return string          返回字符串
     */
    private function receiveShortVedio($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        Log::info("{$toUsername} receive shortvideo from {$fromUsername}.");

        $contentStr = "Received shortvedio!";
        $resultStr = $this->formatText($postObj, $contentStr);
        return $resultStr;
    }

    // 返回文字信息
    private function receiveText($postObj) {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        Log::info("{$toUsername} receive text from {$fromUsername}.");

        if(!empty( $keyword ))
        {
            if("news" == $keyword)
            {
                $resultStr = $this->formatNewsDemo($postObj);
            } else {
                $contentStr = "Welcome to wechat world!";
                $resultStr = $this->formatText($postObj, $contentStr);
            }
            
            return $resultStr;
        }else{
            $contentStr = "Welcome to wechat world!";
            $resultStr = $this->formatText($postObj, $contentStr);
            return $resultStr;
        }
    }

    /**
     * 获取文字返回值
     * @param  class $postObj    微信返回值类
     * @param  string $contentStr 需要返回的字符串
     * @return string             字符串返回值
     */
    private function formatText($postObj, $contentStr) {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $msgType = "text";
        $time = time();
        $textTpl = "<xml>
                    <ToUserName><![CDATA[%s]]></ToUserName>
                    <FromUserName><![CDATA[%s]]></FromUserName>
                    <CreateTime>%s</CreateTime>
                    <MsgType><![CDATA[%s]]></MsgType>
                    <Content><![CDATA[%s]]></Content>
                    <FuncFlag>0</FuncFlag>
                    </xml>"; 
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
        return $resultStr;
    }

    /**
     * 返回图文消息(demo), 
     * @param  [type] $postObj [description]
     * @return [type]          [description]
     */
    private function formatNewsDemo($postObj)
    {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $msgType = "news";
        $time = time();

        $title = "图文消息标题";
        $description = "图文消息描述";
        $picurl = "http://images.nationalgeographic.com/wpf/media-live/photos/000/936/cache/bear-road-denali_93621_990x742.jpg";
        $url = "http://www.baidu.com";

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
                    <PicUrl><![CDATA[%s]]></PicUrl>
                    <Url><![CDATA[%s]]></Url>
                    </item>
                    </Articles>
                    </xml>"; 
        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $title, $description, $picurl, $url);
        return $resultStr;
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

    /**
     * 图片消息返回
     * @param  class $postObj 微信返回值类
     * @return string          返回字符串
     */
    private function receiveImage($postObj) {
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        Log::info("{$toUsername} receive image from {$fromUsername}.");

        $contentStr = "Received your image!";
        $resultStr = $this->formatText($postObj, $contentStr);
        return $resultStr;
    }

    /**
     * 添加新粉丝
     * @param string $FromUserName 粉丝openid
     */
    private function addNewFans($FromUserName)
    {
        $fanMod = new Fan();
        if(!Fan::where('openid',$FromUserName)->first())
        {
            $fanMod->openid = $FromUserName;
            $result = $fanMod->save();
            if(!$result)
                Log::info("添加粉丝失败.");
        }
            
    }

    /**
     * 微信消息排重
     * @param  class  $postObj 微信返回值类
     * @return boolean          是否重复
     * @createtime  2016/06/06 周一
     */
    private function isDuplicate($postObj) 
    {
        $needle = $postObj->FromUserName.$postObj->CreateTime;
        $key = CacheKey::get_is_duplicate_key();
        $value = RedisFun::getArrayValue($key);
        if($value)  // 缓存存在
        {
            if(in_array($needle, $value))   // 在缓存中, 重复消息
            {
                return true;
            } else {    // 不在缓存中, 非重复消息
                return false;
            }
        } else { // 缓存不存在, 非重复消息
            $data = array_push($value, $needle);
            RedisFun::setArrayValue($key, $data);
            return false;
        }
    }
}
