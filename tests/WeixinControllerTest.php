<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\MyLib\WeixinApi;

class WeixinControllerTest extends TestCase
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

    public function testGetImpData() {
        $obj = new WeixinApi();
        $data = $obj->sub_curl('xuji.yogiman.cn/weixin/getimp',['url'=>'xuji.yogiman.cn/weixin/test']);
        $data = json_decode($data);
        $flag = true;
        foreach(['nonceStr', 'appid', 'signature', 'timestamp'] as $key) {
            if(!array_key_exists($key, $data)){
                $flag = false;
            }
        }
        $this->assertTrue($flag);
    }

}
