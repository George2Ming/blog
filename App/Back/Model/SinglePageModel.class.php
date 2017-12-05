<?php
/**
 * 后台bg_singlepage表操作模型
 */
class SinglePageModel extends Model{
	/**
	 * 获取所有单页面信息
	 */
	public function getSinglePage(){
		$sql="select * from bg_singlepage order by page_id desc";
		return $this->dao->fetchAll($sql);
	}
	/**
	 * 数据入库
	 */
	public function insertPage($pageInfo){
		extract($pageInfo);
		$sql="insert into bg_singlepage values(null,'$title','$content')";
		return $this->dao->my_query($sql);
	}
}