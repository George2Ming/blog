<?php
/**
 * 图像处理类
 */
class Image{
	public static $error;//记录错误信息
	//制作缩略图
	public function makeThumb($max_w,$max_h,$src_file,$path,$red=255,$green=255,$blue=255){
		//判断是否合法
		if (!file_exists($src_file)) {
			self::$error='原图像不存在';
			return false;
		}
		if (!getimagesize($src_file)) {
			//源文件不是图像
			self::$error='原图像类型不合法';
			return false;
		}
		//2.创建原图像画布
		$src_info=getimagesize($src_file);
		switch($src_info[2]){
			case 1:$type='gif';break;
			case 2:$type='jpeg';break;
			case 3:$type='png';
		}
		$create_name='imagecreatefrom'.$type;
		$src_img=$create_name($src_file);
		//3.创建缩略图画布
		$dst_img=imagecreatetruecolor($max_w, $max_h);
		$bgcolor=imagecolorallocate($dst_img,$red,$green,$blue);
		imagefill($dst_img,0,0,$bgcolor);
		// 4.计算相关参数
		$dst_wh=$max_w/$max_h;//缩略图宽高比
		$src_w=$src_info[0];
		$src_h=$src_info[1];
		$src_wh=$src_w/$src_h;//原图宽高比
		//5.确认拷贝到缩略图的宽和高
		if ($src_wh>$dst_wh) {
			$dst_w=$max_w;
			$dst_h=floor($dst_w/$src_wh);
		}else{
			$dst_h=$max_h;
			$dst_w=floor($dst_h/$src_wh);
		}
		//6.确定拷贝到缩略图画布的图片的坐标
		$dst_x=($max_w-$dst_w)/2;
		$dst_y=($max_h-$dst_h)/2;
		//7.采样拷贝
		if(imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, 0, 0, $dst_w, $dst_h, $src_w, $src_h)){
			//采样成功
			//先得到原图名称
			$filename=basename($src_file);//去掉字符串的路径,只保留最后的名称
			//拼凑出缩略图的名称
			$thumb='thumb_'.$filename;
			//保存图片
			$save_name='image'.$type;
			$save_name($det_img,$path.''.$thumb);
			//销毁画布资源
			imagedestroy($dst_img);
			imagedestroy($src_img);
			//返回缩略图的名称
			return $thumb;
		}else{
			//采样失败
			//销毁画布资源
			imagedestroy($dst_img);
			imagedestroy($src_img);
			self::$error='发生未知错误';
			return false;
		}
	}
}