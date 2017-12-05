<?php
/**
 * 前台会员管理控制器
 */
class UserController extends PlatformController{
	/**
	 * 会员注册表单动作
	 */
	public function registerAction(){
		//获取站长信息
		$master=Factory::M('MasterModel');
		$masterInfo=$master->getMasterInfo();
		//分配变量
		$this->assign('masterInfo',$masterInfo);
		//调用article模型
		$article=Factory::M('ArticleModel');
		//获取最新文章列表
		$newArt=$article->getNewArt(8);
		//分配变量
		$this->assign('newArt',$newArt);
		//获取最热文章推荐列表
		$rmdArtByHits=$article->getRmdArtByHits(8);
		//分配变量
		$this->assign('rmdArtByHits',$rmdArtByHits);
		//显示输出视图文件
		$this->display('register.html');
	}
	/**
	 * 处理会员注册
	 */
	public function dealRegisterAction(){
		//接收数据
		$userInfo=array();
		$user_name=$this->escapeData($_POST['user_name']);
		//判断用户名是否为空
		if (empty($user_name)) {
			$this->jump('index.php?p=Home&c=User&a=register','用户名不能为空');
		}
		//是否超出长度
		if (strlen($user_name)>20) {
			$this->jump('index.php?p=Home&c=User&a=register','用户名过长!');
		}
		//用户名是否存在
		$user=Factory::M('UserModel');
		if ($user->if_name_exists($user_name)) {
			$this->jump('index.php?p=Home&c=User&a=register','用户已经存在');
		}
		$userInfo['user_name']=$user_name;
		//判断密码是否一致
		$user_pass1=trim($_POST['pass1']);
		$user_pass2=trim($_POST['pass2']);
		if (empty($user_pass1)||empty($user_pass2)) {
			$this->jump('index.php?p=Home&c=User&a=register','密码不能为空');
		}
		if ($user_pass1 !==$user_pass2) {
			$this->jump('index.php?p=Home&c=User&a=register','两次密码不一致');
		}
		$userInfo['user_pass']=md5($user_pass1);
		//判断是否上传了头像
		if ($_FILES['user_image']['error']!=4) {
			$upload=Factory::M('Upload');
			$allow=array('image/png','image/jpeg','image/gif','image/jpg');
			$path=UPLOADS_DIR.'user';
			//调用uploadAction
			if($result=$upload->uploadAction($_FILES['user_image'],$allow,$path)){$userInfo['user_image']=$result;
			}else{
				//上传失败
				$this->jump('index.php?p=Home&c=User&a=register',Upload::$error);
			}
		}else{
			$userInfo['user_image']='default.jpg';
		}
		$userInfo['user_time']=time();
		//调用模型,数据入库
		$result=$user->insertUser($userInfo);
		if ($result) {
				$this->jump('index.php?p=Home&c=User&a=login','注册成功');
		}else{
				$this->jump('index.php?p=Home&c=User&a=register','发生未知错误');	
		}
	}
	/**
	 * 会员登录表单动作
	 */
	public function loginAction(){
		//获取站长信息
		$master=Factory::M('MasterModel');
		$masterInfo=$master->getMasterInfo();
		//分配变量
		$this->assign('masterInfo',$masterInfo);
		//调用article模型
		$article=Factory::M('ArticleModel');
		//获取最新文章列表
		$newArt=$article->getNewArt(8);
		//分配变量
		$this->assign('newArt',$newArt);
		//获取最热文章推荐列表
		$rmdArtByHits=$article->getRmdArtByHits(8);
		//分配变量
		$this->assign('rmdArtByHits',$rmdArtByHits);
		//显示输出视图文件
		$this->display('login.html');
	}
	/**
	 * 处理会员登录动作
	 */
	public function dealLoginAction(){
		$user_name=$this->escapeData($_POST['user_name']);
		$user_pass=trim($_POST['pass1']);
		if (empty($user_pass)||empty($user_name)) {
			$this->jump('index.php?p=Home&c=User&a=login','不能为空');
		}
		//判断用户名,密码是否合法
		$user=Factory::M('UserModel');
		$result=$user->check($user_name,md5($user_pass));
		if ($result) {
			//用户信息存储到session中
			@session_start();
			$_SESSION['user_info']=$result;
				$this->jump('index.php?p=Home&c=Index&a=index');
		}else{
				$this->jump('index.php?p=Home&c=User&a=login','用户名或密码错误');	
		}
	}
	/**
	 * logout功能
	 */
	public function logoutAction(){
		unset($_SESSION['user_info']);
		session_destroy();
		$this->jump('index.php?p=Home&c=Index&a=index');
	}
}