<?php
/***后台配置***/

return [
    'APP_DEBUG' => true,  //调试模式	
	
	 
	//RBAC认证配置信息
	'RBAC_SUPERADMIN' => 'admin', 	  //超级管理员名称，对应用户表中某一用户名-username
	'ADMIN_AUTH_KEY' => 'superadmin', //超级管理员识别	
    'USER_AUTH_ON' =>  true,  		  //是否需要认证
    'USER_AUTH_TYPE' =>  2,  		  //认证类型 1为登录后才认证 2 为实时认证
    'USER_AUTH_KEY' => 'authId',  	  //认证识别号，此名称可以自已取
    //'REQUIRE_AUTH_MODULE' =>    	  //需要认证模块
    'NOT_AUTH_MODULE' => 'Index,Login',//无需认证模块，和上重复，我们只用一个
    'NOT_AUTH_ACTION' => '',  //无需认证操作
    'USER_AUTH_GATEWAY' => '/admin/login',  //认证网关，此处可以不用
    //'RBAC_DB_DSN' => 'mysql://root:123456@localhost:3306/studyimrbac',   //数据库连接DSN，公用的，此处可以省略
    'RBAC_ROLE_TABLE' => 'psk_role',  	  //角色表名称
    'RBAC_USER_TABLE' => 'psk_user',  	  //用户表名称
    'RBAC_ACCESS_TABLE' => 'psk_access',   //权限表名称
    'RBAC_NODE_TABLE' => 'psk_node',  	  //节点表名称
	'GUEST_AUTH_ON' => false,
		
	//定义样式路径
	'view_replace_str' =>[
		'_ACSS_' => '/public/admin/css',
		'_AJS_' => '/public/admin/js',
		'_AIMG_' => '/public/admin/images',
		'_LAYER_' => '/public/layer/',
		'_LAYUI_' => '/public/layui/',
	]
];
