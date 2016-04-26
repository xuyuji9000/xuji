<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;

class FunctionController extends Controller
{
    public function upload(Request $request) {
        // $content_url = Storage::disk('local')->publicUrl('avatars/test.jpg');
        // // dd($content_url);
        $data = array();
        // $data["img_url"] = $content_url;
        return view("function.upload", $data);
    }

    public function uploadImg(Request $request) {
        Storage::put('avatars/test.jpg', file_get_contents($request->file("imgfile")->getRealPath()));
    	echo json_encode(array("status"=>1));
    }
}
