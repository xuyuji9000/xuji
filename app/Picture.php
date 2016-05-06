<?php
/**
 * 图片存储表
 * @author karl.yogi.xu@gmail.com
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

use App\MyLib\UploadFile;

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
}
