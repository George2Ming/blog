<?php
/**
 * 前台bg_category操作模型
 */
class CategoryModel extends Model{
	/**
	 * 获取所有一级分类信息
	 */
	public function getFirstCate(){
		$sql="select * from bg_category where cate_pid=0 order by cate_sort";
		return $this->dao->fetchAll($sql);
	}
	// 获取当前类别下一层子类别
	public function getSubCateByPid($pid){
		$sql="select cate_id,cate_name from bg_category where cate_pid=$pid";
		return $this->dao->fetchAll($sql);
	}
}