<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;

class Article extends Common
{
    public function index(){
		//判断手机端
		if(isMobile()){
			$page_type=get_site('wap_page')?true:false;
		}else{
			$page_type=get_site('page')?true:false;
		}

		$q_nid 	 = getNID();
		$q_cid   = 0;	
		$title   = urldecode(input('title'));
		$cid     = "";
		if($q_nid!=""){		
			$nid = db('nav')->where("entitle",$q_nid)->value("id");			
			if($nid==""){
				$cate = db('cate')->field('nid,id,page')->where("entitle",$q_nid)->find();								
				$nid  = $cate['nid'];
				$cid  = $cate['id'];  
				$q_cid = 1;	//是分类
			}
		}	
	
		if($nid==""){ $this->error( 'error');}				
		$navrow = db('nav')->where('id',$nid)->find();
		$data["nid"] 	 = $navrow['id'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data["columnName"]	 = $navrow['title'];
		$data["showcate"]	 = $navrow['showcate'];
		$data["entitle"] = $navrow['entitle'];	
		$data["content"] = $navrow['content'];
			
		$data["leftlist"] = webtreelist('cate','title,img,id,entitle,url,target,mid',['nid'=>$nid]);	
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
		//有分类直接显示列表
		if($navrow['mid']!=1){
			
			if(isMobile()){
				$page=$cate['wap_page']?$cate['wap_page']:$navrow['wap_page'];
				
			}else{
				$page=$cate['page']?$cate['page']:$navrow['page'];
			}

			$where['nid'] = $nid;
												
			if($q_cid==1 && $cid != ""){					
				$where['cid'] = array("in",webtreecatewhere($cid));
				$data["cid"] = $cid;	
			}			
			if($title!=""){				
				$where['title'] = array('like','%'.$title.'%');
			}		
		
			$order = "istop desc,sort desc,id desc";			
			$list = db('article')->where($where)->order($order)->paginate($page,$page_type,['query' => request()->param()]);										
			$data["list"] = $list;
			$data["page"] = $list->render();			
		}
		$data["site"] = getseo($nid,"",$cid);
		$data["cateName"] = getCateName($cid,$nid);
		$this->assign($data);

		//模板定义
		$ntpl  = $navrow['mid']!=1?$navrow['list_tpl']:$navrow['msg_tpl'];		
		$mtpl = db('module')->where('id',$navrow['mid'])->value('tpl');				
		$tpl  = $ntpl ? $ntpl : $mtpl;	
		return $this->fetch($navrow['mid']!=1?$tpl:'show:'.$tpl);			
    }	
	
}
