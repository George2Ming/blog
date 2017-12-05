<?php

header("Content-type:text/html;Charset=utf-8");
$config = array(
	'pass'=>'zhouyang',
	'dbname'=>'php2017'
);
include './MySQLDB.class.php';
$db = MySQLDB::getInstance($config);

// 先获取所有分类的信息
$sql = "select * from category";
$list = $db->fetchAll($sql);

/**
 * 格式化分类列表,重新排序并树状展示
 * @param array $list 原始分类列表
 * @param int $pid = 0 父类id号
 * @param int $level 缩进级别
 * @return array $cate_list 格式化之后的分类列表
 */
function getCateTree($list, $pid=0, $level=0) {
	// 定义静态数组变量用于存放格式化之后的数组
	static $cate_list = array();
	// 遍历
	foreach($list as $row) {
		if($row['cate_pid'] == $pid) {
			$row['level'] = $level;
			$cate_list[] = $row;
			// 递归
			getCateTree($list, $row['cate_id'], $level+1);
		}
	}
	// 返回遍历结果
	return $cate_list;
}

// 调用函数
$cate_list = getCateTree($list);

foreach($cate_list as $row) {
	echo str_repeat('-',5*$row['level']),$row['cate_name'],'<br />';
}