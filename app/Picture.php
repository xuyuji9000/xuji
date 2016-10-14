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

    public static $imageBasePath = '/'.env("IMAGE_PATH");




    /**
	 *	$file_name : 表单中file的name属性
	 *	返回值：数组   array("org"=>org/1/b9/e73/64e7/4bd979d19b34918fc277f8.jpg, "id=>"1")
	 */
	public function uploadFile($souce_file)
    {
    	$upload=new UploadFile($souce_file);

    	$rr["org"]=$upload->copy_org();
		if($rr["org"]["result"])
		{
			$this->org=$rr["org"]["img_url"];
		}
		$upload->destroy();
		$id=$this->save();
		return $this->id?$this->id:false;
    }

    /**
     * @return string
     */
    private function getImageBasePath()
    {
    	return '/'.env("IMAGE_PATH");
    }

    /**
	 *	获取图片链接
	 *	返回值: 字符串 图片链接 "http://xuji.yogiman.cn/../../../test.jpg"
	 */
    public function getImageURL($org_path='') {
    	$org_path = $org_path?$org_path:$this->org;
    	return URL::to('/').self::$imageBasePath.$org_path;
    }

    /**
     * @return string
     */
    public function getLocalImage(){
    	return public_path().self::$imageBasePath.$this->org;
    }

    /**
     * @param $id
     * @return bool
     */
    function getElementById($id){
        if(!($result = Picture::find($id)))
            return false;
        $result['org'] = self::$imageBasePath.$result['org'];
        return $result;
    }

}
