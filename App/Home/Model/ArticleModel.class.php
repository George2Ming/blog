<?php
/**
 * 前台bg_article操作模型
 */
class ArticleModel extends Model{
	/**
	 * 获取推荐文章列表
	 */
	public function getRecommendArt($length){
		$sql="select a.*,c.cate_name from bg_article as a left join bg_category as c on a.cate_id=c.cate_id where is_del='0' and is_recommend='1' order by addtime desc limit $length";
		return $this->dao->fetchAll($sql);
	}
	//获取最新文章列表
	public function getNewArt($length){
		$sql="select art_id,title from bg_article where is_del='0' order by addtime desc limit $length";
		return $this->dao->fetchAll($sql);
	}
	//获取最热文章
	public function getRmdArtByHits($length){
		$sql="select art_id,title from bg_article where is_del='0' and is_recommend='1' order by hits desc limit $length";
		return $this->dao->fetchAll($sql);
	}
	/**
	 * 根据类别号获取当前类别/子类别下面所有文章
	 */
	public function getArtInfo($cate_id){
		//先获取该类别下所有子类别
		$ids=$this->getAllSubIds($cate_id);
		//加上当前分类id号
		$ids.=$cate_id;
		//计算偏移量
		$pageNum=isset($_GET['$pageNum'])? $_GET['$pageNum']:1;
		$offset=($pageNum-1)*9;
		$sql="select a.*,c.cate_name from bg_article as a left join bg_category as c on a.cate_id=c.cate_id where is_del='0' and a.cate_id in ($ids) limit $offset,9";
		return $this->dao->fetchAll($sql);
	}
	/**
	 * 根据当前分类号获取所有子分类号
	 */
	private function getAllSubIds($cate_id){
		$sql="select cate_id from bg_category where cate_pid=$cate_id";
		$id=$this->dao->fetchAll($sql);
		static $ids='';
		foreach ($id as $row) {
			$ids.=$row['cate_id'].',';
			$this->getAllSubIds($row['cate_id']);
		}
		return $ids;
	}
	/**
	 * 获取当前分类及其子分类下总记录数
	 * @return [type] [description]
	 */
	public function getRowCount($cate_id){
		//先获取该类别下所有子类别
		$ids=$this->getAllSubIds($cate_id);
		//加上当前分类id号
		$ids.=$cate_id;
		$sql="select count(*) from bg_article where is_del='0' and cate_id in ($ids)";
		return $this->dao->fetchColumn($sql);
	}
	/**
	 * 获取面包屑导航数组信息
	 */
	public function getAllCateName($cate_id){
		//获取当前分类名称和其父类id
		$sql="select cate_pid,cate_name from bg_category where cate_id=$cate_id";
		$cateInfo=$this->dao->fetchRow($sql);
		$cate_name=$cateInfo['cate_name'];
		static $list=array();
		$list[$cate_id]=$cate_name;
		$cate_pid=$cateInfo['cate_pid'];
		//如果父类不为0,一直重复
		if ($cate_pid!=0) {
			$this->getAllCateName($cate_pid);
		}
		return array_reverse($list,true);
	}
	/**
	 * 获取当前分类下点击排行文章
	 */
	public function getSortByHits($cate_id,$length){
		$ids=$this->getAllSubIds($cate_id);
		$ids.=$cate_id;
		$sql="select art_id,title from bg_article where is_del='0' and cate_id in ($ids) order by hits desc limit $length";
		return $this->dao->fetchAll($sql);
	}
	//获取当前分类下推荐文章
	public function getSortByRecommend($cate_id,$length){
		$ids=$this->getAllSubIds($cate_id);
		$ids.=$cate_id;
		$sql="select art_id,title from bg_article where is_del='0' and cate_id in ($ids) and is_recommend='1' order by addtime desc limit $length";
		return $this->dao->fetchAll($sql);
	}
	/**
	 * 根据id号获取文章信息
	 */
	public function getArtInfoById($art_id){
		$sql="select * from bg_article where art_id=$art_id";
		return $this->dao->fetchRow($sql);
	}
	/**
	 * 增加点击次数
	 */
	public function updateHitsById($art_id){
		$sql="update bg_article set hits=hits+1 where art_id=$art_id";
		$this->dao->my_query($sql);
	}
	/**
	 * 获取上一篇文章
	 */
	public function getPrevArt($art_id,$cate_id){
		//获取所有子分类id号
		$son_ids=$this->getAllSubIds($cate_id);
		//在获取该分类所有父类号
		$father_ids=$this->getAllCateName($cate_id);
		$father_ids=implode(',',array_keys($father_ids));
		$ids=$son_ids.$father_ids;
		$sql="select art_id,title from bg_article where is_del='0' and cate_id in($ids) and art_id<$art_id order by art_id desc limit 1";
		return $this->dao->fetchRow($sql);
	}
	/**
	 * 获取下一篇文章信息
	 */
	public function getNextArt($art_id,$cate_id){
		//获取所有子分类id号
		$son_ids=$this->getAllSubIds($cate_id);
		//在获取该分类所有父类号
		$father_ids=$this->getAllCateName($cate_id);
		$father_ids=implode(',',array_keys($father_ids));
		$ids=$son_ids.$father_ids;
		$sql="select art_id,title from bg_article where is_del='0' and cate_id in($ids) and art_id>$art_id order by art_id limit 1";
		return $this->dao->fetchRow($sql);
	}
	/**
	 * 增加评论数
	 */
	public function updateReplyNumsById($art_id){
		$sql="update bg_article set reply_nums =reply_nums+1 where art_id=$art_id";
		$this->dao->my_query($sql);
	}
}