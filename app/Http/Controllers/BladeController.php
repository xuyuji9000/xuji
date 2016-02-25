<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Redis;
class BladeController extends Controller
{
    public function index() {
        return view('blade.blade');
    }
    public function test() {
        $key = 'name';
        $value = ['hello', 'world'];
        Redis::sadd($key, $value);
        $user = Redis::smembers($key);
        return $user;
    }
}
