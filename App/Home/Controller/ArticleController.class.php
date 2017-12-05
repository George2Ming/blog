<?php

/**
 * 前台文章管理控制器
 */
class ArticleController extends PlatformController{
	/**
	 * 栏目首页动作
	 */
	public function indexAction(){
		//接收栏目编号(分类)
		$cate_id=$_GET['cate_id'];
		//获取该主类别下所有文章
		$article=Factory::M('ArticleModel');
		$artInfo=$article->getArtInfo($cate_id);
		//分配变量
		$this->assign('artInfo',$artInfo);
		//以下代码与分页有关
		//获取该分类下文章总页数
		$rowCount=$article->getRowCount($cate_id);
		$maxNum=$GLOBALS['conf']['Page']['maxNum'];
		$url='index.php?p=Home&c=Article&a=index&cate_id=$cate_id&';
		//实例化分页类
		$page=new Page(9,$rowCount,$maxNum,$url);
		$strPage=$page->getStrPage();
		$this->assign('strPage',$strPage);
		//分页到此结束
		//调用公共动作
		$this->PublicAction($cate_id);
		//输出视图
		$this->display('index.html');
	}
	/**
	 * 公共动作
	 */
	private function PublicAction($cate_id){
		//获取一层子类别
		$category=Factory::M('CategoryModel');
		$subCate=$category->getSubCateByPid($cate_id);
		//分配变量
		$article=Factory::M('ArticleModel');
		$this->assign('subCate',$subCate);
		//获取面包屑导航数组信息
		$list=$article->getAllCateName($cate_id);
		//分配变量
		$this->assign('list',$list);
		// 获取当前分类下点击排行文章
		$sortByHits=$article->getSortByHits($cate_id,9);
		//分配变量
		$this->assign('sortByHits',$sortByHits);

		//获取当前分类下推荐文章
		$sortByRecommend=$article->getSortByRecommend($cate_id,9);
		$this->assign('sortByRecommend',$sortByRecommend);
	}
	/**
	 * 显示文章内容页动作
	 */
	public function showAction(){
		//接收当前文章的id号
		$art_id=$_GET['art_id'];
		//调用模型.提取当前文章信息
		$article=Factory::M('ArticleModel');
		//在获取文章信息之前更新浏览次数
		$article->updateHitsById($art_id);
		//通过文章id获取文章信息
		$artInfoById=$article->getArtInfoById($art_id);
		//分配变量
		$this->assign('artInfoById',$artInfoById);

		//获取当前文章id号
		$cate_id=$artInfoById['cate_id'];

		//调用公共动作
		$this->PublicAction($cate_id);

		//获取文章上一篇,下一篇信息
		$prev=$article->getPrevArt($art_id,$cate_id);
		$next=$article->getNextArt($art_id,$cate_id);
		$this->assign('prev',$prev);
		$this->assign('next',$next);

		//以下代码与分页有关
		$rowsPerPage=5;
		$maxNum=$GLOBALS['conf']['Page']['maxNum'];
		$url="index.php?p=Home&c=Article&a=show&art_id=$art_id&";
		$comment=Factory::M('CommentModel');
		$rowCount=$comment->getRowCountByArtId($art_id);
		//实例化分页类
		$page=new Page($rowsPerPage,$rowCount,$maxNum,$url);
		$strPage=$page->getStrPage();
		//分配页码字符串
		$this->assign('strPage',$strPage);
		//分页到此结束
		
		//提取当前页面所有评论
		$cmtInfos=$comment->getCmtInfosById($art_id,$rowsPerPage);
		//分配变量
		$this->assign('cmtInfos',$cmtInfos);
		//输出视图
		$this->display('show.html');
	}
	/**
	 * 评论动作
	 */
	public function commentAction(){
		//先判断是否登陆
		if (!isset($_SESSION['user_info'])) {
			$this->jump('index.php?p=Home&c=User&a=login','请您先登录!');
		}
		//接收数据
		$cmtInfo=array();
		$cmtInfo['art_id']=$_POST['art_id'];
		$cmt_content=$this->escapeData($_POST['content']);
		if (empty($cmt_content)) {
			$this->jump("index.php?p=Home&c=Article&a=show&art_id={$cmtInfo['art_id']}",'评论不能为空');
		}
		$cmtInfo['cmt_content']=$cmt_content;
		$cmtInfo['cmt_time']=time();
		$cmtInfo['cmt_user']=$_SESSION['user_info']['user_name'];
		//调用模型
		$comment=Factory::M('CommentModel');
		if ($comment->insertComment($cmtInfo)) {
			//给当前文章评论数+1
			$article=Factory::M('ArticleModel');
			$article->updateReplyNumsById($cmtInfo['art_id']);
			$this->jump("index.php?p=Home&c=Article&a=show&art_id={$cmtInfo['art_id']}");
		}else{
			$this->jump("index.php?p=Home&c=Article&a=show&art_id={$cmtInfo['art_id']}",'发生未知错误');
		}
	}
}