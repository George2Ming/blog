<?php
/**
 * bg_master表的操作模型
 */
class MasterModel extends Model{
	// 获取站长信息
	public function getMasterInfo(){
		$sql="select * from bg_master";
		return $this->dao->fetchRow($sql);
	}
}