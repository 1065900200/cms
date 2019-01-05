<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

//前端路由配置
if (is_file(APP_PATH . 'install/data/install.lock')) {

  $routeNav  = db('nav')->field('entitle')->order('sort,id')->select();
  $routeCate = db('cate')->field('entitle,mid')->order('sort,id')->select();
  Route::rule('search','index/Search/index');
  foreach ($routeNav as $key=>$v) {
	  if($v['entitle']){
		Route::rule($v['entitle'],'index/Article/index');
		Route::rule($v['entitle'].'/:id','index/Show/index');
	  }
  }

  foreach ($routeCate as $key=>$v) {
	if($v['entitle']){
		switch ($v['mid']) {
		  //单页
		  case '1':
		  Route::rule($v['entitle'],'index/Lis/page');
		  break;

		  case '2':
		  Route::rule($v['entitle'],'index/Lis/article');
		  //Route::rule($v['entitle'].'/page/:page','index/Lis/article');
		  break;

		  //产品
		  case '3':
			Route::rule($v['entitle'],'index/Lis/product');
		  break;
			
		  //下载
		  case '4':
			Route::rule($v['entitle'],'index/Lis/download');
		  break;

		  //视频
		  case '5':
			Route::rule($v['entitle'],'index/Lis/video');
		  break;

		  default:
		  {
			Route::rule($v['entitle'],'index/Lis/error');
		  }
		  break;
		}

		Route::rule($v['entitle'].'/:id','index/Show/index');
	}
  }
}