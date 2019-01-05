<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;

class Lis extends Common
{
	public $cate="";
	public $page_type="";

	public function _initialize(){	

		parent::_initialize();

		$q_nid 	 = getNID();
    	if($q_nid!=""){	
    		$this->cate=db('cate')->where("entitle",$q_nid)->find();	
    	}
    	
		if(empty($this->cate)){exit("分类错误");}	
		if(isMobile()){
			$this->page_type=get_site('wap_page')?true:false;
		}else{
			$this->page_type=get_site('page')?true:false;
		}
    }	

    //单页
	public function page(){
		$data=$this->cate;
		$data["leftlist"] = webtreelist('cate','title,img,id,entitle,url,target,mid',['nid'=>$this->cate['nid']]);	
		if($data["leftlist"]){
			foreach($data["leftlist"] as $k=>$v){
				if($v['mid']==7){
					$data["leftlist"][$k]['entitle']=$v['url'];
				}else{
					$data["leftlist"][$k]['entitle']='/'.$v['entitle'];
				}
				unset($data["leftlist"][$k]['url']);
				unset($data["leftlist"][$k]['mid']);
			}
		}	


		$navrow = db('nav')->where('id',$this->cate['nid'])->find();
		$data["nid"] 	 = $navrow['id'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data["columnName"]	 = $navrow['title'];
		$data["showcate"]	 = $navrow['showcate'];
		$data["entitle"] = $navrow['entitle'];	
		$data["site"] = getseo($this->cate['nid'],"",$this->cate['id']);
		$data["cateName"] = getCateName($this->cate['id'],$this->cate['nid']);
		$data["content"] = $this->cate['content'];
		$data["cid"] = $this->cate['id'];
		$this->assign($data);

		if(!empty($this->cate['msg_tpl'])){
			$tpl="list_".$this->cate['msg_tpl'];
		}else{
			$ntpl  = $navrow['msg_tpl'];
			$mtpl  = db('module')->where('id',$navrow['mid'])->value('tpl');
			$tpl   = $ntpl ? "show_".$ntpl : "show_".$mtpl;	
		}	

		
		return $this->fetch($tpl);				
	}

	//文章
	public function article(){
		$data=$this->cate;
		if(isMobile()){
			$page=$data['wap_page']?$data['wap_page']:10;
		}else{
			$page=$data['page']?$data['page']:10;
		}
		$data["leftlist"] = webtreelist('cate','title,img,id,entitle,url,target,mid',['nid'=>$this->cate['nid']]);	
		if($data["leftlist"]){
			foreach($data["leftlist"] as $k=>$v){
				if($v['mid']==7){
					$data["leftlist"][$k]['entitle']=$v['url'];
				}else{
					$data["leftlist"][$k]['entitle']='/'.$v['entitle'];
				}
				unset($data["leftlist"][$k]['url']);
				unset($data["leftlist"][$k]['mid']);
			}
		}		
	
		if($this->cate['id'] != ""){	
			$where['cid'] = array("in",webtreecatewhere($this->cate['id']));
			$data["cid"] = $this->cate['id'];
		}
		$order = "istop desc,sort desc,id desc";			
		$list = db('article')->where($where)->order($order)->paginate($page,$this->page_type,['query' => request()->param()]);												
		$data["list"] = $list;
		$data["page"] = $list->render();	

		$navrow = db('nav')->where('id',$this->cate['nid'])->find();
		$data["nid"] 	 = $navrow['id'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data["columnName"]	 = $navrow['title'];
		$data["showcate"]	 = $navrow['showcate'];
		$data["entitle"] = $this->cate['entitle'];	
		$data["site"] = getseo($this->cate['nid'],"",$this->cate['id']);
		$data["cateName"] = getCateName($this->cate['id'],$this->cate['nid']);
		//$data["cateName"] = getCateName($this->cate['id']);

		$this->assign($data);

		if(!empty($this->cate['list_tpl'])){
			$tpl="list_".$this->cate['list_tpl'];
		}else{
			$ntpl  = $navrow['list_tpl'];
			$mtpl  = db('module')->where('id',$navrow['mid'])->value('tpl');
			$tpl   = $ntpl ? "article_".$ntpl : "article_".$mtpl;	
		}	

		return $this->fetch($tpl);	
	}


    //产品
	public function product(){
		$data=$this->cate;
		if(isMobile()){
			$page=$data['wap_page']?$data['wap_page']:10;
		}else{
			$page=$data['page']?$data['page']:10;
		}
		$data["leftlist"] = webtreelist('cate','title,img,id,entitle,url,target,mid',['nid'=>$this->cate['nid']]);	
		if($data["leftlist"]){
			foreach($data["leftlist"] as $k=>$v){
				if($v['mid']==7){
					$data["leftlist"][$k]['entitle']=$v['url'];
				}else{
					$data["leftlist"][$k]['entitle']='/'.$v['entitle'];
				}
				unset($data["leftlist"][$k]['url']);
				unset($data["leftlist"][$k]['mid']);
			}
		}			
		if($this->cate['id'] != ""){	
			$where['cid'] = array("in",webtreecatewhere($this->cate['id']));
			$data["cid"] = $this->cate['id'];
		}
		$order = "istop desc,sort desc,id desc";			
		$list = db('article')->where($where)->order($order)->paginate($page,$this->page_type,['query' => request()->param()]);												
		$data["list"] = $list;
		$data["page"] = $list->render();	

		$navrow = db('nav')->where('id',$this->cate['nid'])->find();
		$data["nid"] 	 = $navrow['id'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data["columnName"]	 = $navrow['title'];
		$data["showcate"]	 = $navrow['showcate'];
		$data["entitle"] = $navrow['entitle'];	
		$data["site"] = getseo($this->cate['nid'],"",$this->cate['id']);
		$data["cateName"] = getCateName($this->cate['id'],$this->cate['nid']);

		$this->assign($data);

		if(!empty($this->cate['list_tpl'])){
			$tpl="list_".$this->cate['list_tpl'];
		}else{
			$ntpl  = $navrow['list_tpl'];
			$mtpl  = db('module')->where('id',$navrow['mid'])->value('tpl');
			$tpl   = $ntpl ? "article_".$ntpl : "article_".$mtpl;	
		}	

		return $this->fetch($tpl);				
	}

	//下载列表
	public function download(){
		$data=$this->cate;
		if(isMobile()){
			$page=$data['wap_page']?$data['wap_page']:10;
		}else{
			$page=$data['page']?$data['page']:10;
		}
		$data["leftlist"] = webtreelist('cate','title,img,id,entitle,url,target,mid',['nid'=>$this->cate['nid']]);	
		if($data["leftlist"]){
			foreach($data["leftlist"] as $k=>$v){
				if($v['mid']==7){
					$data["leftlist"][$k]['entitle']=$v['url'];
				}else{
					$data["leftlist"][$k]['entitle']='/'.$v['entitle'];
				}
				unset($data["leftlist"][$k]['url']);
				unset($data["leftlist"][$k]['mid']);
			}
		}										
		if($this->cate['id'] != ""){	
			$where['cid'] = array("in",webtreecatewhere($this->cate['id']));
			$data["cid"] = $this->cate['id'];
		}
		$order = "istop desc,sort desc,id desc";			
		$list = db('article')->where($where)->order($order)->paginate($page,$this->page_type,['query' => request()->param()]);												
		$data["list"] = $list;
		$data["page"] = $list->render();	

		$navrow = db('nav')->where('id',$this->cate['nid'])->find();
		$data["nid"] 	 = $navrow['id'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data["columnName"]	 = $navrow['title'];
		$data["showcate"]	 = $navrow['showcate'];
		$data["entitle"] = $navrow['entitle'];	
		$data["site"] = getseo($this->cate['nid'],"",$this->cate['id']);
		$data["cateName"] = getCateName($this->cate['id'],$this->cate['nid']);

		$this->assign($data);

		if(!empty($this->cate['list_tpl'])){
			$tpl="list_".$this->cate['list_tpl'];
		}else{
			$ntpl  = $navrow['list_tpl'];
			$mtpl  = db('module')->where('id',$navrow['mid'])->value('tpl');
			$tpl   = $ntpl ? "article_".$ntpl : "article_".$mtpl;	
		}	

		return $this->fetch($tpl);	
	}



	//视频
	public function video(){
		$data=$this->cate;
		if(isMobile()){
			$page=$data['wap_page']?$data['wap_page']:10;
		}else{
			$page=$data['page']?$data['page']:10;
		}
		$data["leftlist"] = webtreelist('cate','title,img,id,entitle,url,target,mid',['nid'=>$this->cate['nid']]);	
		if($data["leftlist"]){
			foreach($data["leftlist"] as $k=>$v){
				if($v['mid']==7){
					$data["leftlist"][$k]['entitle']=$v['url'];
				}else{
					$data["leftlist"][$k]['entitle']='/'.$v['entitle'];
				}
				unset($data["leftlist"][$k]['url']);
				unset($data["leftlist"][$k]['mid']);
			}
		}										
		if($this->cate['id'] != ""){	
			$where['cid'] = array("in",webtreecatewhere($this->cate['id']));
			$data["cid"] = $this->cate['id'];
		}
		$order = "istop desc,sort desc,id desc";			
		$list = db('article')->where($where)->order($order)->paginate($page,$this->page_type,['query' => request()->param()]);												
		$data["list"] = $list;
		$data["page"] = $list->render();	

		$navrow = db('nav')->where('id',$this->cate['nid'])->find();
		$data["nid"] 	 = $navrow['id'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data["columnName"]	 = $navrow['title'];
		$data["showcate"]	 = $navrow['showcate'];
		$data["entitle"] = $navrow['entitle'];	
		$data["site"] = getseo($this->cate['nid'],"",$this->cate['id']);
		$data["cateName"] = getCateName($this->cate['id'],$this->cate['nid']);

		$this->assign($data);

		if(!empty($this->cate['list_tpl'])){
			$tpl="list_".$this->cate['list_tpl'];
		}else{
			$ntpl  = $navrow['list_tpl'];
			$mtpl  = db('module')->where('id',$navrow['mid'])->value('tpl');
			$tpl   = $ntpl ? "article_".$ntpl : "article_".$mtpl;	
		}	
		return $this->fetch($tpl);	
	}



	//错误分类
    public function error(){
    	$this->error("此分类无法在前台打开");

    }	
	

    



}
