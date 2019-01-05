<?php
namespace app\index\controller;

use think\exception\Handle;

class HttpException extends Handle
{
	public function render(\Exception $e){
		if(config('app_debug')){
			return parent::render($e);
		}else{
			header("Location: /public/404/404.html");
		}
	}
}
