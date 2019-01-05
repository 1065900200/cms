<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;

class Seo extends Base
{
	
	//百度推送
    public function baiduurl() {
		if($_POST){
			$data=input("post.");
			if(db('site')->where('id=1')->update($data)!==false)
			return json(['status'=>'y','info'=>'修改成功']);
		}else{
			$this->assign("v",db('site')->where('id=1')->find());
			return $this->fetch(); 
		}
    }	
	
	//网站内链
	public function sitelink(){
		$id = input('id');
		if($_POST){		
			$data=input("post.");
			if(db('sitelink')->where('title',$data['title'])->find()){
				return json(['status'=>'0','info'=>'关键词已存在']);
			}
			if(db('sitelink')->insert($data)){					
				siteLinkReplace($data);
				return json(['status'=>'y','info'=>'添加成功']);
			}					
		}else{
		  $list = db('sitelink')->order("id desc")->paginate(10,false,['query' => request()->param()]);
		  $page = $list->render();
		  $this->assign("list",$list);
		  $this->assign("page",$page);
		  return $this->fetch();
		}
	}	
	
	//批量删除内链
	public function delsitelink(){
		$id = input('id');
		$arr = db('sitelink')->where('id',$id)->find();
		delSiteLink($arr);	
		db('sitelink')->where('id',$id)->delete();		
		return json(['status'=>'y','info'=>'删除内链成功']);
	}	
	
	//站点地图
	public function sitemap(){
		$act = input('act');
		if($act=="go"){
			sitemap();
			$this->success('生成成功');
		}else{
			$url = db('site')->where("id=1")->value("weburl");
			$this->assign("url",$url.'sitemap.xml');
			return $this->fetch();
		}
	}
	
	//蜘蛛统计
	public function spider(){
		$title = input('title');
		if($title!=""){
			$where['title'] = $title;
			$this->assign("type",$title);
		}
		$list = db('spider')->where($where)->order('datetime desc')->paginate(10,false,['query' => request()->param()]);
		$page = $list->render();
		$this->assign("list",$list);
		$this->assign("page",$page);
		return $this->fetch();
	}
	
	//删除蜘蛛统计
	public function delspider(){	
		$type = input('type');
		if($type!=""){	
			db('spider')->where('title',$type)->delete();
			return json(['status'=>'y','info'=>'删除成功']);
		}else{
			return json(['status'=>'n','info'=>'请选择要删除的搜索引擎']);
		}
	}
	//友情连接
	public function link(){
		$id = input('id');
		if($_POST){
			$data = input('post.');
			if($id==""){
				if(db('link')->insert($data)){
					return json(['status'=>'y','info'=>'添加成功']);
				}	
			}else{
				if(db('link')->where('id',$id)->update($data)!==false){
					return json(['status'=>'y','info'=>'修改成功']);
				}	
			}
		}else{
			if($id!=""){
				$one = db('link')->where("id",$id)->find();
				$this->assign("v",$one);
			}
			$list = db('link')->paginate(10,false,['query' => request()->param()]);
			$this->assign("list",$list);
			$this->assign("page",$list->render());
			return $this->fetch();
		}
	}
	
	//删除友情连接
	public function dellink(){		
		$id = input('id');
		db('link')->where('id',$id)->delete();
		return json(['status'=>'y','info'=>'删除成功']);
	}

}