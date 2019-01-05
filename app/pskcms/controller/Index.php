<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;
use psk\Backup;

class Index extends Base
{
    public function index()
    {
		$list = db('nav')->where('mid<>6 and mid<>7')->order("sort,id")->select();
		$adv  = db('nav')->where('mid=6')->order("sort,id")->select(); //广告	
		$this->assign("n",input('n',2));
		$this->assign("list",$list);
		$this->assign("adv",$adv);
		return $this->fetch(); 
    }
	
	public function home(){
		$data['list'] = db('nav')->where('mid<>6')->order("sort,id")->select(); //导航			
		$sql= new Backup();  
		$data['tableCount']= count($sql->dataList()); //数据库表数量
		$data['navCount']  = db('nav')->count();	  //导航数量	 
		$data['bookCount'] = db('book')->count();	  //留言数量						
		$this->assign($data);
		return $this->fetch();
	}
	
   
}