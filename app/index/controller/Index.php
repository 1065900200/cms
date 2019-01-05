<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;

class Index extends Common
{
    public function index()
    {
		$this->assign("site",getseo());
		$this->assign("nid",'0');
		return $this->fetch(); 
    }
}
