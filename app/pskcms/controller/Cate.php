<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;

class Cate extends Base
{
	public $nid; //栏目ID	
	public function __construct(){
		parent::__construct();
		$this->nid = input('nid');
	}
	
	//分类管理
    public function index(){
		$list = treelist('cate','',['nid'=>$this->nid]);
		$this->assign("nid",$this->nid);
		$this->assign("list",$list);
		return $this->fetch();
    }
	
	
	//添加编辑分类
	public function add() {
		$id = input('id',0);

		if($_POST){
			$data=input("post.");
			if($id==""){						
				$result = checkEntitle($data['title'],$data['entitle']);				
				if($result=="no"){
					return json(['status'=>'0','info'=>'英文名称已存在']);
				}else{
					$data['entitle'] = $result;
				}
				if(db('cate')->insert($data))
				return json(['status'=>'y','info'=>'添加成功']);
			}else{		
				$result = checkEntitle($data['title'],$data['entitle'],$id);				
				if($result=="no"){
					return json(['status'=>'0','info'=>'英文名称已存在']);
				}else{
					$data['entitle'] = $result;
				}
				if(db('cate')->where('id',$id)->update($data)!==false)
				return json(['status'=>'y','info'=>'修改成功']);
			}
		}else{
			$list = treelist('cate','',['nid'=>$this->nid]);
			$one = db('cate')->where('id',$id)->find();
			$module_list = db('module')->order("id")->select();
			$this->assign("v",$one);
			$this->assign("nid",$this->nid);
			$this->assign("id",$id);
			$this->assign("list",$list);
			$this->assign("module_list",$module_list); 
			return $this->fetch(); 
		}
    }
	
	//删除分类
	public function del(){	
		$id  = input('id');	
		$res = db('cate')->where('pid',$id)->find();		
		if($res){
			return json(['status'=>'0','info'=>'请先删除该分类下的子类']);	
		}
		if(db('article')->where('cid',$id)->find()){
			return json(['status'=>'0','info'=>'该分类已有内容，不能删除']);	
		}				
		db('cate')->where('id',$id)->delete();		
		return json(['status'=>'y','info'=>'删除成功']);				
	}
	
	//排序
	public function sorts(){
		$id   =  input('id');
		$num  =  input('num');
		$d['sort'] =  $num;
		db('cate')->where('id',$id)->update($d);		
		return json(['status'=>'y','info'=>'排序成功']);		
	}
	
	
}