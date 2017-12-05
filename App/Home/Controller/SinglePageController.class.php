<?php
/**
 * 前台单页面控制器
 */
class SinglePageController extends PlatformController{
	/**
	 * 单页面首页动作
	 */
	public function indexAction(){
		$page_id=$_GET['page_id'];
		$singlePage=Factory::M('SinglePageModel');
		$pageInfo=$singlePage->getPageInfoById($page_id);
		$this->assign('pageInfo',$pageInfo);
		//获取站长信息
		$master=Factory::M('MasterModel');
		$masterInfo=$master->getMasterInfo();
		//分配变量
		$this->assign('masterInfo',$masterInfo);
		$this->display('index.html');
	}
}