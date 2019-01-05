<?php
namespace app\index\controller;
use think\Controller;
use think\Request;
use SimpleXMLElement;

class Common extends Controller
{
    
	public  function __construct(Request $request){

        if($request->isMobile()){
           config('template.view_path', VIEW_PATH.'mobile/'); //设置手机模板
        }
        parent::__construct($request);
    }
	
	public function _initialize(){	

		if (!is_file(APP_PATH . 'install/data/install.lock')) {
			header("Location: /index.php/install/index");
			exit;
		}

		//站点状态
		$isopen = db('site')->field('webstatus,webclosedesc')->where('id=1')->find();	
		if($isopen['webstatus']==0){
			exit($isopen['webclosedesc']);
		}
		spider(); //蜘蛛爬虫		
		$this->assign("nav",$this->nav());	

    }	
	
	//导航
	public function nav(){

		$list = db('nav')->where('isshow=1')->field('showcate,id,title,entitle,url,target')->order('sort,id')->select();
		if($list){
			foreach($list as $key=>$v){				
				if(!empty($v['url'])){
					$list[$key]['link'] = ''.$v['url'].'';
				}else{
					$list[$key]['link'] = '/'.$v['entitle'].'/';
				}					
				if($v['showcate']==1){
					$list[$key]['son'] = db('cate')->where("nid=".$v['id']." and isshow=1 and pid=0")->field('id,title,entitle,url')->order("sort,id")->select();
					if($list[$key]['son'])foreach($list[$key]['son'] as $key1=>$v1){
						if(!empty($v1['url'])){
							$list[$key]['son'][$key1]['link'] = ''.$v1['url'].'';
						}else{
							$list[$key]['son'][$key1]['link'] = '/'.$v1['entitle'].'/';
						}	
						
					}
				}
			}
		}
		return $list;		
	}
	
	
	
}
