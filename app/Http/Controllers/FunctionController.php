<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;

use URL;

use App\Picture;
use App\MyLib\WeixinApi;

class FunctionController extends Controller
{
    public function upload(Request $request) {
      // $pic = Picture::find(7);

      // $wx = new WeixinApi();
      // $info = $wx->wxCardUpdateImg();
      // var_dump($info);
      
      // $url = $pic->get_img_url();
      // echo "<img src=\"".$info."\">";
      // $token = WeixinApi::getAccessToken();
      // echo $token ;
      // echo $pic->get_img_local();
      // var_dump($info);

      $data = array();
      return view("function.upload", $data);
    }

    public function uploadImg(Request $request) {
      $Picture = new Picture();
      $data["id"] = $Picture->uploadFile($request->file("imgfile")->getRealPath());
      // $data = array();
      // Storage::put('test.jpg', file_get_contents($request->file("imgfile")->getRealPath()));
      // $url = Storage::url('test.jpg');
      // $data["url"] = $url;
      // $path = $request->file("imgfile")->getRealPath();
      // $data["path"] = $path;

      // $status = is_uploaded_file($path)?true:false;
      // $data["status"] = $status;
    	echo json_encode($data);
    }

    public function submit(Request $request)
    {  
      $destinationPath = 'upload'; 
      //$extension = $request->file('imgfile')->getClientOriginalExtension(); 
      $fileName = rand(11111,99999).'.'.$extension;
      Storage::put($destinationPath.'/'.$fileName, file_get_contents($request->file("imgfile")->getRealPath()));

      // $result = $request->file('imgfile')->move($destinationPath, $fileName);
      //$result = $request->file('imgfile')->move("", $request->file("imgfile")->getRealPath()));
      // echo $result;
      echo public_path();
    }

    public function test(Request $request){
      echo URL::to('/');
      // $files =  asset("routes-msq_ky.png");
      // echo $files;
      // echo "<img src=\"".$files."\">";
      // phpinfo();
      // echo public_path();
      // $file = Storage::get('images/test.jpg'); 
      // return response($file, 200)->header('Content-Type', 'image/jpeg');
      // echo asset("images/test.jpg");
      // echo env("IMAGE_PATH");
      // echo DIRECTORY_SEPARATOR ;
    }

}
