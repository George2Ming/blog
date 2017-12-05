<?php

/**
 * 后台管理平台控制器
 */
class ManageController extends PlatformController {
	/**
	 * 首页动作
	 */
	public function indexAction() {
		$this->display('index.html');
	}
}