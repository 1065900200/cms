<?php

/***前台配置***/

return [	 
	 'template'        => [       
        'view_path'    => VIEW_PATH.'cn/',      	  // 模板路径 				
		'taglib_pre_load' => 'app\common\taglib\P',  // 预先加载标签库
		'view_depr'    => '_',
     ],
	 
	 // 异常页面的模板文件
    //'exception_tmpl' => '/public/404/404.html',
		
	//定义样式路径
	'view_replace_str' =>[
		'_CN_' => '/tpl/cn/public',  	//中文模板路径	
		'_MB_' => '/tpl/mobile/public',  //手机模板路径		
	]	
];
