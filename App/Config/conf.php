<?php

return array(
	'db'	=>	array( // 数据库信息组
		'host'	=>	'127.0.0.1',
		'port'	=>	'3306',
		'user'	=>	'root',
		'pass'	=>	'123456',
		'charset'=>	'utf8',
		'dbname' => 'blog'
	),
	'App'	=>	array( // 应用程序组
		'default_platform'=>'Home',
		'dao'	=>	'pdo',// 这里可以是pdo或mysql
	),
	'Home'	=>	array( // 前台组
		'default_controller'=>'Index',
		'default_action'	=>'index'
	),
	'Back'	=>	array(	// 后台组
		'default_controller'=>'Admin',
		'default_action'	=>'login'
	),
	'Captcha'	=>	array( // 验证码信息组
		'width'	=>	80,
		'height'=>	32,
		'pixelnum'=> 0.02,//干扰点密度
		'linenum' => 5,// 干扰线数量
		'stringnum'=> 4, // 验证码字符个数
	),
	'Page'	=>	array(
		'rowsPerPage' =>3,//每页显示的记录数
		'maxNum' =>5 //页面上显示的最多有多少个页面
	),
	// 其他
);

