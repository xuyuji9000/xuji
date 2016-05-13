<?php
namespace App\MyLib;
/**
* 上传图片
* @author karl.yogi.xu@gmail.com
*/
class UploadFile
{
	public  $src_im;   	//ImageMagick 对象
	private $src_file; 		//源文件路径
	private $is_img;		//源文件是否存在
	private $src_format;	//图片格式
	private $src_width; 	//源文件高度
	private $src_height; 	//源文件宽度
	public $md5_file_name; //文件md5值
	public $file_ext;     // 文件扩展名
	private $pic_base_url;  //public中图片路径

	function __construct($src_file) {
		$this->src_file = $src_file;
		$this->src_im   = new \Imagick();
		$this->is_img = $this->src_im->readImage($this->src_file);
		$this->src_format = $this->src_im->getImageFormat();
		$this->src_width = $this->src_im->getImageWidth();
		$this->src_height = $this->src_im->getImageHeight();
		$this->pic_base_url = env("IMAGE_PATH");


		if($this->is_img)
		{
			$this->md5_file_name=md5_file($this->src_file);
		}
		else
		{
			$this->md5_file_name='';
		}

		$this->init_ext();
		$this->src_im->destroy();
	}


	/**
	 * 初始化扩展名
	 */
	private function init_ext()
	{
		$srcT =$this->src_format;
		if ($srcT == "JPEG")
		{
			$this->file_ext = "jpg";
		}
		elseif ($srcT == "GIF")
		{
			$this->file_ext = "gif";
		}
		elseif ($srcT == "PNG")
		{
			$this->file_ext = "png";
		}
		elseif ($srcT == "BMP")
		{
			$this->file_ext = "bmp";
		}
		else
		{
			$this->file_ext = "";
		}
	}

	/**
	 * 拷贝原图
	 */
	public function copy_org()
	{
		if(!$this->is_img)return false;
		if(!$this->md5_file_name)return false;
		$arr=$this->explode_md5();
		$dir=public_path().'/'.$this->pic_base_url.'/org/'.$arr[1].'/'.$arr[2].'/'.$arr[3].'/'.$arr[4].'/'; //图片完整目录
		if(!is_dir($dir))//原图目录不存在
		{
			$this->make_dir($dir);
		}
		$df=$dir.$arr[5].'.'.$this->file_ext;
		$up_status=0;
		if(is_uploaded_file($this->src_file))//http
		{
			$up_status=move_uploaded_file($this->src_file,$df);
		}
		else
		{
			$up_status=copy($this->src_file,$df);
		}
		if(!$up_status){
			$return_info['result']=0;
			// 此处应该写日志
			// $this->write_log('copy_org->上传原图失败(src:'.$this->src_file.',df->'.$df.')');
		}
		else
		{
			$return_info['result']=1;
			$return_info["img_url"] = str_replace(public_path().'/'.$this->pic_base_url,'',$df);
		}
		return $return_info;
	}

	/**
	 * 创建目录
	 */
	function make_dir($dir='')
	{
		return is_dir($dir) or ($this->make_dir(dirname($dir)) and mkdir($dir, 0777));
	}

	/**
	 * 拆分md5字符串
	 */
	private function explode_md5()
	{
		if(!$this->md5_file_name)return false;
		$arr[1]=substr($this->md5_file_name, 0, 1);
		$arr[2]=substr($this->md5_file_name, 1, 2);
		$arr[3]=substr($this->md5_file_name, 3, 3);
		$arr[4]=substr($this->md5_file_name, 6, 4);
		$arr[5]=substr($this->md5_file_name, 10);
		return $arr;
	}

	/**
	 * 清除临时文件
	 */
	public function destroy()
	{
		if(is_file($this->src_file)) @unlink($this->src_file);
	}

}