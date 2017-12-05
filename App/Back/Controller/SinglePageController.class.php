<?php
/**
 * 后台单页管理控制器
 */
class SinglePageController extends PlatformController{
	/**
	 * 单页管理首页
	 */
	public function indexAction(){
		$singlePage=Factory::M('SinglePageModel');
		$pageInfo=$singlePage->getSinglePage();
		//分配变量
		$this->assign('pageInfo',$pageInfo);
		//输出视图文件
		$this->display('index.html');
	}
	/**
	 * 显示添加但也表单的动作
	 */
	public function addAction(){
		$this->display('add.html');
	}
	/**
	 * 处理单页提交的数据
	 */
	public function dealAddAction(){
		$pageInfo=array();
		$pageInfo['title']=$this->escapeData($_POST['title']);
		$pageInfo['content']=addslashes($_POST['content']);
		//判断数据合法性
		if (empty($pageInfo['title'])||empty($pageInfo['content'])) {
			$this->jump('index.php?p=Back&c=SinglePage&a=Add',':(填写信息不完整');
		}
		//调用模型,数据入库
		$singlePage=Factory::M('SinglePageModel');
		$result=$singlePage->insertPage($pageInfo);
		if ($result) {
			$this->jump('index.php?p=Back&c=SinglePage&a=index');
		}else{
			$this->jump('index.php?p=Back&c=SinglePage&a=Add',':(发生未知错误');
		}
	}
}