<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\MyLib\WeixinApi;

class WeixinApiTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    // 测试getAccessToken()方法
    public function testGetAccessToken() {
       $this->assertGreaterThan(100, strlen(WeixinApi::getAccessToken()));
    }

    // 测试sub_curl()方法
    public function testSubCurl() {
        $this->assertContains('Baidu', WeixinApi::sub_curl('http://www.baidu.com', [''], false));
    }

    // 测试createNonceStr()方法
    public function testCreateNonceStr() {
        $obj = new WeixinApi();
        $this->assertTrue(16 == strlen($obj->createNonceStr()));
    }

    // 测试getJsapiTicket()方法
    public function testGetJsapiTicket() {
        $obj = new WeixinApi();

        $this->assertGreaterThan(80, strlen($obj->getJsapiTicket()));
    }

    // 测试getSignature()方法
    public function testGetSignature() {
        $obj = new WeixinApi();
       $this->assertEquals(40, strlen($obj->getSignature('http://xuji.yogiman.cn/weixin/test')));
    }

}
