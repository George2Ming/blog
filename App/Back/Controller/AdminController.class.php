<?php

/**
 * 后台管理员控制器(登录、注销、管理员的增删改查等)
 */ 
class AdminController extends PlatformController {
	/**
	 * 展示登录表单动作
	 */
	public function loginAction() {
		// 载入当前的视图文件
		$this->display('login.html');
	}
	/**
	 * 后台注销功能
	 */
	public function logoutAction() {
		@session_start();
		// 删除相关会话数据
		unset($_SESSION['adminInfo']);
		// 删除会话数据区
		session_destroy();
		// 立即跳转到登录页面
		$this->jump('index.php?p=Back&c=Admin&a=login');
	}
	/**
	 * 验证管理员的合法性
	 */
	public function checkAction() {
		// 接收表单数据
		$admin_name = $this->escapeData($_POST['admin_name']);
		$admin_pass = $_POST['admin_pass'];
		$passcode = trim($_POST['passcode']);
		$captcha = Factory::M('Captcha');
		if(!$captcha->checkCaptcha($passcode)) {
			// 验证码非法,跳转
			$this->jump('index.php?p=Back&c=Admin&a=login', ':( 验证码错误！');
		}
		// 从数据库中验证管理员的合法性
		// 实例化模型
		$admin = Factory::M('AdminModel');
		if($row = $admin->check($admin_name, $admin_pass)) {
			// 如果合法,应该把用户信息放到session中
			@session_start(); // 确定开启session机制
			$_SESSION['adminInfo'] = $row;
			// 更新当前管理员信息
			$admin->updateAdminInfo($row['admin_id']);
			// 直接跳转到后台管理首页
			$this->jump('index.php?p=Back&c=Manage&a=index');
		}else {
			// 验证非法,给出提示后跳转
			$this->jump('index.php?p=Back&c=Admin&a=login', ':( 用户名或密码错误！');
		}
	}
	/**
	 * 生成验证码图片的动作
	 */
	public function captchaAction() {
		// 实例化验证码类
		$captcha = Factory::M('Captcha');
		$captcha->generate();
	}
}