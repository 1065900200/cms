<?php
namespace app\pskcms\controller;
use think\Controller;
use think\Db;
use app\pskcms\controller\Base;

class Article extends Base
{
	public $nid; 	//栏目ID
	public $mid; 	//模块ID	
	public $iscid;  //是否显示分类			
	public function __construct(){
		parent::__construct();
		$this->nid = input('nid');
		$this->mid = db('nav')->where('id',$this->nid)->value('mid');
		$this->iscid = db('nav')->where('id',$this->nid)->value('showcate');
		$this->assign("nid",$this->nid);
		$this->assign("mid",$this->mid);
		$this->assign("iscid",$this->iscid);
	}
	
	//文章列表
	public function index(){
		$order = "istop desc,sort desc,id desc";
		$map['nid'] = $this->nid;
		if(input('s_ishome')==1){
			$map['ishome'] = 1;
		}
		if(input('s_isvouch')==1){
			$map['isvouch'] = 1;
		}
		if(input('s_istop')==1){
			$map['istop'] = 1;
		}

		
		if($this->mid==1 || $this->mid == 6){
			$order = "istop,sort,id";
		}
		if(input('s_cid')!=""){			
			$map['cid'] = array('in',treecatewhere(input('s_cid')));
		}
		if(input('title')!=""){			
			$map['title'] = array('like','%'.input('title').'%');
		}	
		$list = db('article')->where($map)->order($order)->paginate(10,false,['query' => request()->param()]);
		$nav_list=db('nav')->where(array('msg_tpl'=>'product'))->order("id")->select();	
		if ($nav_list&& is_array($nav_list)) {
			foreach ($nav_list as $k => $v) {
				$nav_list[$k]['cate']= treelist('cate','',['nid'=>$v['id']]);
			}
		}
		$cate = treelist('cate','',['nid'=>$this->nid]);
		$page = $list->render();					
		$this->assign("list",$list);
		$this->assign("cate",$cate);
		$this->assign("nav_list",$nav_list);
		$this->assign("page",$page);
		return $this->fetch(); 
    }
	
	
	//添加编辑文章
	public function add() {
		$id = input('id',0);
		$nav = db('nav')->field("isthumb,thumbwidth,thumbheight,imgtips")->where('id',$this->nid)->find();
		if($_POST){
			$data=input("post.");
			$data['datetime'] = inputDate($data['datetime']);
			if($data['imgs']){ 
				$data['imgs'] = implode('|',$data['imgs']);
			}else{
				$data['imgs'] = "";
			}				
			if($nav['isthumb']==1 && $data['img']!=""){
				 //生成缩略图				  		 			  
				  $nav['img'] = $data['img'];		  
				  $data['img'] = _thumb($nav);				  
			}
			//获取内容简介
			if($data['note']==""){
				$data['note'] = getContentNote($data['content'],200);
			}
			if($data['content']!=""){
				$data['content'] = addArticleSiteLink($data['content']);
			}			
			if($id==""){
				$addId = db('article')->insertGetId($data);				
				if($addId!=""){														
					$webzdts = db('site')->where('id=1')->value('webzdts');	
					if($webzdts == 1){
						$result = baiduPushUrl($addId,$this->nid);
						$result = json_decode($result,true);
						if($result['success']=="1"){
							$info = '添加成功,URL已推送至百度';
						}else{
							$info = '添加成功,<br/>URL推送失败error:'.$result['error'].',message:'.$result['message'];
						}
					}else{
						$info = '添加成功';
					}
					return json(['status'=>'y','info'=>$info]);
				}
			}else{					
				if(db('article')->where('id',$id)->update($data)!==false)
				return json(['status'=>'y','info'=>'修改成功']);
			}
		}else{
			$list = treelist('cate','',['nid'=>$this->nid]);
			$one = db('article')->where('id',$id)->find();
			$one['imgtips'] = $nav['imgtips'];	
			if($one['imgs']!=""){$one['imgs'] = explode('|',$one['imgs']);}				
			$this->assign("v",$one);		
			$this->assign("id",$id);
			$this->assign("list",$list); 	
			if($this->mid==6){
				return $this->fetch(advert); 
			}else{		
				return $this->fetch(); 
			}
		}
    }
	
	//删除文章
	public function del(){	
		$id  = input('id');						
		db('article')->where('id',$id)->delete();		
		return json(['status'=>'y','info'=>'删除成功']);				
	}
	
	//指量删除文章
	public function delall(){
		if($_POST['id'])foreach($_POST['id'] as $v){				
			db('article')->where('id',$v)->delete();			
		}
		$this->success("批量删除成功",url('index',['nid'=>$this->nid]));
	}
	
	//文章排序
	public function sorts(){
		$id   =  input('id');
		$num  =  input('num');
		$d['sort'] =  $num;
		db('article')->where('id',$id)->update($d);		
		return json(['status'=>'y','info'=>'排序成功']);		
	}
	
	//批量排序
	public function plsorts(){		
		foreach($_POST['id'] as $v){
			foreach($_POST['sort'] as $k=>$v1){	
				if($v==$k){			
					db('article')->where('id',$k)->setField('sort',$v1);
				}
			}
		}
		$this->success("批量排序成功",url('index',['nid'=>$this->nid]));						
	}		
	
	//批量首页
	public function ishome(){	
		$ishome  = input('ishome');				
		if($_POST['id']){
			foreach($_POST['id'] as $v){				
				db('article')->where('id',$v)->setField('ishome',$ishome);
			}
			$txt = $ishome == 1 ? '首页' : '取消首页';
			$this->success("批量".$txt."成功",url('index',['nid'=>$this->nid]));	
		}else{
			$this->error("请选择要操作的内容");		
		}				
	}	
	
	//批量推荐
	public function isvouch(){		
		$isvouch  = input('isvouch');				
		if($_POST['id']){
			foreach($_POST['id'] as $v){				
				db('article')->where('id',$v)->setField('isvouch',$isvouch);
			}
			$txt = $isvouch == 1 ? '推荐' : '取消推荐';
			$this->success("批量".$txt."成功",url('index',['nid'=>$this->nid]));	
		}else{
			$this->error("请选择要操作的内容");		
		}				
	}	
	
	//批量置顶
	public function istop(){		
		$istop  = input('istop');				
		if($_POST['id']){
			foreach($_POST['id'] as $v){				
				db('article')->where('id',$v)->setField('istop',$istop);
			}
			$txt = $istop == 1 ? '置顶' : '取消置顶';
			$this->success("批量".$txt."成功",url('index',['nid'=>$this->nid]));	
		}else{
			$this->error("请选择要操作的内容");		
		}	
	}

	
	//批量移动文章
	public function movecate(){
		$id  = input('cid');	
		$re=db('cate')->where(array('id'=>$id))->select();
		$field = array();
		if ($re['0']['id']) {
			$field['cid']=$re['0']['id'];
			$field['nid']=$re['0']['nid'];
		}else{
			$tem=explode("-", $id);
			$field['cid']='';
			$field['nid']=$tem['1'];
		}
		if($_POST['id']){
			foreach($_POST['id'] as $v){				
				db('article')->where('id',$v)->setField($field);
			}
			$this->success("批量移动文章成功",url('index',['nid'=>$this->nid]));	
		}else{
			$this->error("请选择要操作的内容");		
		}						
	}
	
	//批量复制文章	
	public function copyArticle(){
		$copy  = input('copynum');	
		$id    = $_POST['id'];
        foreach ($id as $k => $v) {
        	$data  = db('article')->where('id',$v)->find();	
        	if ($data['id']) {
    			unset($data['id']);				
				for($i=0; $i < $copy; $i++){				
					db('article')->insert($data);		
				}
    		}	
        }
		
		$this->success("批量复制文章成功",url('index',['nid'=>$this->nid]));					
	}	
	
	
	//广告图片
	public function adv() {
		$id   = input('id');	
		$act  = input('act');
		$imgtips = db('nav')->where('id',$this->nid)->value('imgtips');	
		if($_POST){
			$data=input("post.");
			if($id!=""){			  
			  if(db('article')->where('id',$id)->update($data)!==false)
			  return json(['status'=>'y','info'=>'修改成功']);
			}else{				
			  if(db('article')->insert($data))
			  return json(['status'=>'y','info'=>'添加成功']);
			}
			$this->assign("imgtips",$imgtips);
		}else{
			$one = db('article')->where('id',$id)->find();			
			$list = db('article')->where('nid',$this->nid)->order($order)->paginate(5);		
			$name = db('nav')->where('id',$this->nid)->value('title');							
			$this->assign("v",$one);
			$this->assign("imgtips",$imgtips);
			$this->assign("act",$act);
			$this->assign("name",$name);
			$this->assign("list",$list);
			$this->assign("page",$list->render());
			return $this->fetch(); 
		}
    }		
	
}