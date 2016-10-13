<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Good;

class GoodsController extends Controller
{
    public function list(){
        $goods = Good::paginate(12);


        return view("goods.list", ['goods'=>$goods]);
    }

    public function item($id) {
        echo $id;
    }
}