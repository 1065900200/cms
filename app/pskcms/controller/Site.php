<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;


class Site extends Base
{
	
	//网站信息
    public function info() {
		if($_POST){
			$data=input("post.");
			if(db('site')->where('id=1')->update($data)!==false)
			return json(['status'=>'y','info'=>'修改成功']);
		}else{
			$this->assign("v",db('site')->where('id=1')->find());
			return $this->fetch(); 
		}
    }
	
	//内容管理
	public function contact() {
		if($_POST){
			$data=input("post.");
			if(db('site')->where('id=1')->update($data)!==false)
			return json(['status'=>'y','info'=>'修改成功']);
		}else{
			$this->assign("v",db('site')->where('id=1')->find());
			return $this->fetch(); 
		}
    }
	
	//邮箱管理
	public function email() {
		if($_POST){
			$data=input("post.");						
			if(db('site')->where('id=1')->update($data)!==false)
			return json(['status'=>'y','info'=>'修改成功']);
		}else{
			$this->assign("v",db('site')->where('id=1')->find());
			return $this->fetch(); 
		}
    }


	//其他设置
	public function other(){
		if($_POST){
			$data=input("post.");						
			if(db('site')->where('id=1')->update($data)!==false)
			return json(['status'=>'y','info'=>'修改成功']);
		}else{
			$this->assign("v",db('site')->where('id=1')->find());
			return $this->fetch(); 
		}
	}
	
	//导航管理
	public function nav(){
		$list = db('nav')->order("sort,id")->select();
		$this->assign("list",$list);
		return $this->fetch();
	}
	
	//内页顶图
	public function topimg() {
		$id   = input('id');	
		$type = input('type');		
		if($_POST){
			$data=input("post.");  
			if(db('nav')->where('id',$id)->update($data)!==false)
			return json(['status'=>'y','info'=>'修改成功']);			
			$this->assign("imgtips",$imgtips);
		}else{
			$one  = db('nav')->where('id',$id)->find();			
			$list = db('nav')->where('isshow=1')->order('sort,id')->select();		
			if($type=='pc'){ 
				$size = "图片尺寸：1920*330"; 
				$name = '内页顶图【电脑】';
			} else { 
				$size = "图片尺寸：640*200";
				$name = '内页顶图【手机】'; 
			}					
			$this->assign("v",$one);
			$this->assign("size",$size);
			$this->assign("name",$name);
			$this->assign("type",$type);
			$this->assign("list",$list);
			return $this->fetch(); 
		}
    }		
		
	
	//添加编辑导航
	public function editnav() {
		$id   = input('id');
		$list = db('module')->order("id")->select();
		if($_POST){
			$data=input("post.");
			if($id==""){					
				$result = checkEntitle($data['title'],$data['entitle']);			
				if($result=="no"){
					return json(['status'=>'0','info'=>'英文名称已存在']);
				}else{
					$data['entitle'] = $result;
				}

				if(db('nav')->insert($data))
				return json(['status'=>'y','info'=>'添加成功']);
			}else{		
				$result = checkEntitle($data['title'],$data['entitle'],$id);				
				if($result=="no"){
					return json(['status'=>'0','info'=>'英文名称已存在']);
				}else{
					$data['entitle'] = $result;
				}
				if(db('nav')->where('id',$id)->update($data)!==false)
				return json(['status'=>'y','info'=>'修改成功']);
			}
		}else{
			$this->assign("v",db('nav')->where('id',$id)->find());
			$this->assign("id",$id);
			$this->assign("list",$list); 
			return $this->fetch(); 
		}
    }
	
	//删除导航
	public function delnav(){
		$id = input('id');
		$res = db("cate")->where("nid",$id)->find();
		$res2 = db("article")->where("nid",$id)->find();
		if($res || $res2){
			return json(['status'=>'0','info'=>'已有内容无法删除']);
		}else{
			db('nav')->where('id',$id)->delete();
			return json(['status'=>'y','info'=>'删除成功']);
		}
	}
	
	//导航排序
	public function sorts(){
		$id   =  input('id');
		$num  =  input('num');
		$d['sort'] =  $num;
		db('nav')->where('id',$id)->update($d);		
		return json(['status'=>'y','info'=>'排序成功']);		
	}
	
	//图片管理
	public function image() {
		if(input('type')!=""){
			$map['type']=input('type');
		}
		$list = db('upload')->where($map)->order("id desc")->paginate(10);
		$page = $list->render();			
		$this->assign("list",$list);
		$this->assign("page",$page);
		return $this->fetch(); 
    }
	
	//删除图片
	public function delimage(){
		$id = input('id');		
		$res = db('upload')->where('id',$id)->find();
		if(file_exists('.'.$res['thumb'])){
			unlink('.'.$res['thumb']);
		}
		if(file_exists('.'.$res['url'])){
			unlink('.'.$res['url']);
		}
		db('upload')->where('id',$id)->delete();
		return json(['status'=>'y','info'=>'删除成功']);
	}
	
	//批量删除图片	
	public function delAllImage(){
		if($_POST['id'])foreach($_POST['id'] as $v){	
			$res = db('upload')->where('id',$v)->find();
			if(file_exists('.'.$res['thumb'])){
				unlink('.'.$res['thumb']);
			}
			if(file_exists('.'.$res['url'])){
				unlink('.'.$res['url']);
			}
			db('upload')->where('id',$v)->delete();			
		}
		$this->success("批量删除成功");
	}
	
	//上传设置
	public function uploadset(){
		$this->assign("v",db('site')->where('id=1')->find());
		return $this->fetch();
	}
		
	//留言管理
	public function book(){
		$list = db('book')->order("id desc")->paginate(10);	
		$this->assign("list",$list);
		$page = $list->render();	
		$this->assign("page",$page);
		return $this->fetch();
	}
	
	//单个删除留言
	public function delbook(){
		$id = input('id');
		db('book')->where('id',$id)->delete();
		return json(['status'=>'y','info'=>'删除成功']);
	}
	
	//批量删除留言
	public function delsbook(){
		if($_POST['id'])foreach($_POST['id'] as $v){	
			db('book')->where('id',$v)->delete();
		}
		$this->success("批量删除留言成功");
	}	

}