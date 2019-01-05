<?php
namespace app\pskcms\controller;
use think\Controller;
use rbac\RBAC;

/**【登录】**/

class Login extends Controller
{
    public function index()
    {	
		return $this->fetch(); 
    }
	
	//登录处理
	public function dologin(){
		$m = db('user');
		$d['username'] = input('post.username');
		$d['password'] = input('post.password');
		$d['status']   = 1;
		$code 	   = input('post.code');
		if($d['username']==""){
			return json(['status'=>'n', 'info'=>'请输入用户名']);
		}
		if($d['password']=="") { 
			return json(['status'=>'n', 'info'=>'请输入密码']);
		}
		if(!captcha_check($code)) { 
			return json(['status'=>'n', 'info'=>'验证码错误']);
		}
		$d['password'] = md5($d['password']);
		$res = RBAC::authenticate($d,'user');	
		if(false == $res) {		
			return json(['status'=>'n', 'info'=>'用户名或密码错误']);
		}else{
			session('username',$res['username']);
			session('userid',$res['id']);
			$_SESSION['userid'] = $res['id'];
			session(config('USER_AUTH_KEY'),$res['id']);
			if(session('username')==config('RBAC_SUPERADMIN'))
			{
				session(config('ADMIN_AUTH_KEY'),true);						
			}			
			RBAC::saveAccessList($res['id']);
			return json(['status'=>'y', 'info'=>'登录成功']);
		}		
	}
	
	// 用户登出
    public function logout()
    {
        if(session('?'.config('USER_AUTH_KEY'))) {
            session(config('USER_AUTH_KEY'),null);
            session(null);		
			unset($_SESSION['userid']);	 
            return redirect('Login/index');
        } else {
            return $this->error('已经登出！');
        }
    }
	
	
}
