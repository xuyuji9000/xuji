<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Storage;
use App\Picture;

class FunctionController extends Controller
{
    public function upload(Request $request) {
        $data = array();
        return view("function.upload", $data);
    }

    public function uploadImg(Request $request) {
      $Picture = new Picture();
      $data["id"] = $Picture->upload_file($request->file("imgfile")->getRealPath());
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

}
