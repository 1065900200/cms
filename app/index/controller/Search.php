<?php
namespace app\index\controller;
use think\Controller;
use app\index\controller\Common;

class Search extends Common
{
    public function index(){		
		$title   = urldecode(input('title'));
 		$where['title'] = array('like','%'.$title.'%');
		$where['mid'] = array('eq','6'); //
		$order = "istop desc,sort desc,id desc";			
		$list = db('article')->where($where)->order($order)->paginate(get_site('search_page'),get_site('page')?true:false,['query' => request()->param()]);		
		$data["banner"]=get_site('search_banner');
		$data['title']=$title;
		$data["list"] = $list;
		$data["page"] = $list->render();		
		$data["site"] = getseo($nid,"",$cid);	
		$data["site"]['webtitle'] = $title.'-'.$data["site"]['webtitle'];
		$data["nid"] = 1;
		$data["columnName"] = '搜索';
		$this->assign($data);
		return $this->fetch();			
    }	
	
}
