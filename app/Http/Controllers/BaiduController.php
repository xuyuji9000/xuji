<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class BaiduController extends Controller
{
    public function local(){
        if(!$_GET)
            echo "请求错误";
        if("array"==gettype($_GET["Latitude"]))
            $Latitude = $_GET["Latitude"][0];
        else
            $Latitude = $_GET["Latitude"];
        if("array"==gettype($_GET["Longitude"]))
            $Longitude = $_GET["Longitude"][0];
        else
            $Longitude = $_GET["Longitude"];
        return view("baidu.navigate", ["Latitude"=>$Latitude, "Longitude"=>$Longitude]);
    }
}
