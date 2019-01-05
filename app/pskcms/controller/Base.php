<?php
namespace app\pskcms\controller;
use think\Controller;
use rbac\RBAC;
use think\Request;

/*
* Admin分组公共类
*/
class Base extends Controller
{

    public function _initialize()
    {
		if (!is_file(APP_PATH . 'install/data/install.lock')) {
			header("Location: index.php/install/index");
		}
		
        if (!session(config('USER_AUTH_KEY')))
		{
			$this->redirect('login/index');
		}
		
		if (config('USER_AUTH_ON') && !in_array($this->request->controller(), explode(',', config('NOT_AUTH_MODULE'))))
		{		
			if (!RBAC::AccessDecision())
			{
				$this->error("没有权限","Index/home");
			}
		}
    }
	
}




