<?php
/**
 * 前台平台控制器
 */
class PlatformController extends Controller{
	/**
	 * 构造方法
	 */
	public function __construct(){
		parent::__construct();
		$this->initFirstCateInfo();
		$this->initVars();
		$this->initSession();
	}
	/**
	 * 分配导航中的一级分类列表信息
	 */
	public function initFirstCateInfo(){
		//调用模型
		$category=Factory::M('CategoryModel');
		$firstCate=$category->getFirstCate();
		//分配变量
		$this->assign('firstCate',$firstCate);
	}
	/**
	 * 分配meta标签的公共数据
	 */
	public function initVars(){
		$title="圣骑士个人博客";
		$keywords="个人博客,圣骑士个人博客,周洋老师个人博客";
		$description="个人博客,圣骑士个人博客,周洋老师个人博客,响应式，神秘、俏皮。";
		$this->assign('title',$title);
		$this->assign('keywords',$keywords);
		$this->assign('description',$description);
	}
	public function initSession(){
		@session_start();
	}
}