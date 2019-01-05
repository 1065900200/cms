<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;

class Show extends Common
{
    public function index()
    {
		$id = input('id');
		$one  = db('article')->where('id',$id)->find();			
		if(empty($one)){ exit("文章不存在");}		
		$navrow = db('nav')->where('id',$one['nid'])->find();		
		$data['showcate'] = $navrow['showcate'];		
		$data['entitle']  = $navrow['entitle'];
		$data['columnName'] = $navrow['title'];
		$data["banner"]	 = isMobile() ? $navrow['img1'] : $navrow['img'];
		$data['id'] = $id;
		$view['views'] = $one['views']+1;
		db('article')->where('id',$id)->update($view);//浏览次数				
		//无分类
		if($data['showcate']==0){ 			
			$data["leftlist"] = db('article')->field('id,title')->where('nid',$one['nid'])->order("sort,id")->select();						
		}		
		//有分类
		if($data['showcate']==1){			
			$data['cid'] = $one['cid'];	
			$data["leftlist"] = webtreelist('cate','title,id,entitle,url,mid',['nid'=>$one['nid']]);	

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

		}
		
		if($one['imgs']!=""){
			$image = explode('|',$one['imgs']);				
		}else{
			$one['img'] = _getbigimg($one['img']);
			$image[0]   = $one['img'];
		}					
		$data['image'] = $image;
		
		$data['cateName'] = getCateName($one['cid'],$one['nid']);

		$data['pn'] = prevNext($id,$navrow['entitle'],$one);	
		$cate=db('cate')->field('msg_tpl')->where("id",$data['cid'])->find();	

		$data['one'] = $one;
		$data['nid'] = $one['nid'];
		
		$data['site'] = getseo($one['nid'],$id,$one['cid']);
		$this->assign($data);
		//模板定义 
		

		if(!empty($cate['msg_tpl'])){
			$tpl=$cate['msg_tpl'];
		}else{
			$ntpl  = $navrow['showcate']==1?$navrow['msg_tpl']:$navrow['msg_tpl'];
			$mtpl  = db('module')->where('id',$navrow['mid'])->value('tpl');
			$tpl   = $ntpl ? $ntpl : $mtpl;	
		}	
		return $this->fetch($tpl);		
    }

	
	//发送邮件
	public function sendemail(){			
		if($_POST){
		  $data = input("post.");
		  $data['datetime'] = time();
		  $issend = db('site')->where('id=1')->value('email_issend');
		  if($issend==1){
		  	sendemail($data);	//发送邮件	 
		  }
		  if($data){
			  $res = db('book')->insert($data);
			  if($res){
			  	return json(['code'=>'1','msg'=>'留言成功']);
		  	  }
		  }		  
		}
	}
	
	
}
