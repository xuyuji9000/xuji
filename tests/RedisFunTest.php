<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\MyLib\RedisFun;

class RedisFunTest extends TestCase
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

    public function testDelete() {
        RedisFun::setStrValue('test', 'this is a test');
        RedisFun::deleteStrValue('test');
        $this->assertTrue(null  == RedisFun::getStrValue('test'));
    }
}
