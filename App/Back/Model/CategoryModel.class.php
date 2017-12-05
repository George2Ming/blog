<?php

/**
 * 后台bg_category分类表操作模型
 */
class CategoryModel extends Model {
	/**
	 * 获取分类信息
	 */
	public function getCategory() {
		$sql = "select * from bg_category order by cate_sort asc";
		$list = $this->dao->fetchAll($sql);
		return $this->getCateTree($list);
	}
	/**
	 * 根据id号获取分类信息
	 * @param int $cate_id 当前分类的id号
	 * @return array 当前分类的信息
	 */
	public function getCateInfoById($cate_id) {
		$sql = "select * from bg_category where cate_id = $cate_id";
		return $this->dao->fetchRow($sql);
	}
	/**
	 * 格式化分类列表,重新排序并树状展示
	 * @param array $list 原始分类列表
	 * @param int $pid = 0 父类id号
	 * @param int $level 缩进级别
	 * @return array $cate_list 格式化之后的分类列表
	 */
	private function getCateTree($list, $pid=0, $level=0) {
		// 定义静态数组变量用于存放格式化之后的数组
		static $cate_list = array();
		// 遍历
		foreach($list as $row) {
			if($row['cate_pid'] == $pid) {
				$row['level'] = $level;
				$cate_list[] = $row;
				// 递归
				$this->getCateTree($list, $row['cate_id'], $level+1);
			}
		}
		// 返回遍历结果
		return $cate_list;
	}
	/**
	 * 增加分类信息
	 * @param array $cate 分类信息
	 */
	public function insertCate($cate) {
		// 通过数组得到多个变量
		extract($cate);
		$sql = "insert into bg_category values(null,'$cate_name',$cate_pid,$cate_sort,'$cate_desc')";
		return $this->dao->my_query($sql);
	}
	/**
	 * 根据id号修改分类信息
	 * @param array $cate 分类信息
	 */
	public function updateCateById($cate) {
		// 通过数组得到多个变量
		extract($cate);
		$sql = "update bg_category set cate_name='$cate_name',cate_pid=$cate_pid,cate_sort=$cate_sort,cate_desc='$cate_desc' where cate_id=$cate_id";
		return $this->dao->my_query($sql);
	}
	/**
	 * 删除指定的分类信息
	 */
	public function delCateById($cate_id) {
		$sql = "delete from bg_category where cate_id = $cate_id";
		return $this->dao->my_query($sql);
	}
	/**
	 * 批量删除分类信息
	 */
	public function delAllCate($cate_id) {
		// 此时$cate_id是一个数组,需要先转换为字符串
		$cate_id = implode(',', $cate_id);
		$sql = "delete from bg_category where cate_id in($cate_id)";
		return $this->dao->my_query($sql);
	}
}