<?php
/**
 * 图片存储表
 * @author karl.yogi.xu@gmail.com
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

use App\MyLib\UploadFile;
use URL;

class Picture extends Model
{
    protected $fillable = [
        'org'
    ];

    /**
	 *	$file_name : 表单中file的name属性
	 *	返回值：数组   array("org"=>org/1/b9/e73/64e7/4bd979d19b34918fc277f8.jpg, "id=>"1")
	 */
	public function upload_file($souce_file)
    {
    	$upload=new UploadFile($souce_file);
    	// $data = array();

    	$rr["org"]=$upload->copy_org();
		if($rr["org"]["result"])
		{
			// $data["org"]=$rr["org"]["img_url"];
			$this->org=$rr["org"]["img_url"];
		}
		$upload->destroy();
		$id=$this->save();
		return $this->id?$this->id:false;
    }

    private function get_img_base_path()
    {
    	return '/'.env("IMAGE_PATH");
    }

    /**
	 *	获取图片链接
	 *	返回值: 字符串 图片链接 "http://xuji.yogiman.cn/../../../test.jpg"
	 */
    public function get_img_url($org_path='') {
    	$org_path = $org_path?$org_path:$this->org;
    	return URL::to('/').$this->get_img_base_path().$org_path;
    }

    public function get_img_local(){
    	return public_path().'/'.env("IMAGE_PATH").$this->org;
    }
}
