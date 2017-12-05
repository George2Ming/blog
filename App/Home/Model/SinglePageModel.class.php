<?php
class SinglePageModel extends Model{
	public function getPageInfoById($page_id){
		$sql="select * from bg_singlepage where page_id=$page_id";
		return $this->dao->fetchRow($sql);
	}
}