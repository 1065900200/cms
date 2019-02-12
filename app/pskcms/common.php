<?php
use psk\ChinesePinyin;
//状态
function _status($v){
	if($v==1){
		return '<font color="#00CC99">正常</font>';
	}else{
		return '锁定';
	}
}

//生成缩略图
function _thumb($data){
	$data['thumbwidth'] = $data['thumbwidth'] ? $data['thumbwidth'] : '300';
	$data['thumbheight'] = $data['thumbheight'] ? $data['thumbheight'] : '250';
	$namearr = explode('/',$data['img']);
	$imgname = strstr($namearr[3],'_');	 
	if($imgname!=""){ $namearr[3] = substr($imgname,1);	}
	$thumbname = $data['thumbwidth'].'x'.$data['thumbheight'].'_'.$namearr[3];
	$thumb = '/upload/'.$namearr[2].'/'.$thumbname; 	
	$thumburl = ROOT_PATH . DS . 'upload/'.$namearr[2].'/'.$thumbname;   
	$image = \think\Image::open('.'.$data['img']);
	$image->thumb($data['thumbwidth'],$data['thumbheight'],\think\Image::THUMB_FIXED)->save($thumburl);
	return $thumb;	
}

//属性管理
function _attr($v,$attr){
	if($attr=='ishome' && $v==1){
		return '<font color="#33FFCC">首页</font>';
	}
	if($attr=='isvouch' && $v==1){
		return '<font color="#0099FF">推荐</font>';
	}
	if($attr=='istop' && $v==1){
		return '<font color="#FF9966">置顶</font>';
	}
}

//时间处理
function inputDate($date){
	$datetime = '';
	if($date!=""){
		$datetime = strtotime($date);
	}else{
		$datetime = time();
	}
	return $datetime;
}


//传入表名查询三级内容
function treelist($table,$field="*",$where,$num){
	$where['pid'] = 0;
	$list = db($table)->field($field)->where($where)->order("sort,id")->limit($num)->select();		
	if($list){foreach($list as $key=>$v){
		$list[$key]['son'] = db($table)->field($field)->where('pid',$v['id'])->order("sort,id")->select();		
		if($list[$key]['son'])foreach($list[$key]['son'] as $key1=>$v1){
			$list[$key]['son'][$key1]['son'] = db($table)->field($field)->where('pid',$v1['id'])->order("sort,id")->select();
		}
	}}
	return $list;
}


//三级分类查询语句生成
function treecatewhere($cid=0){
	$catecid = array($cid);	//加入本分类	
	$where = '';		
	$sonid = db('cate')->where('pid',$cid)->field('id')->select();		
	if($sonid){
		foreach($sonid as $key=>$v){					
			  $catecid[] .= $v['id'];	//加入本分类
			  foreach($sonid as $key1=>$v1){					 
				 $son2id = db('cate')->where('pid',$v1['id'])->field('id')->select();	
				 if($son2id){
					foreach($son2id as $key2=>$v2){
						 $catecid[] .= $v2['id'];	//加入本分类
					}
				 }
			  }		
		}
		$catecid = array_flip(array_flip($catecid));  //去除数组中重复元素			
		$catecid=implode(',',$catecid);	//拆分分类ID	
		$where .= "$catecid";													
	}else{
		$where .= "$cid";				
	}		
	return $where;
}


//检测英文名称是否存在
/*
* @param $title 标题 $entitle 英文名称  $id 文章ID
*/
function checkEntitle($title,$entitle,$id){
	if($entitle==""){

		$Pinyin = new \psk\ChinesePinyin();
		$entitle = $Pinyin->TransformWithoutTone($title);
	}
	$result = $entitle;
	if($entitle != ""){
		if($id==''){
		   $res = db('nav')->where('entitle',$entitle)->find();	
		   $res2 = db('cate')->where('entitle',$entitle)->find();
		   if(!empty($res) || !empty($res2)){
			  $result = "no";	 
		   }
		}else{
			$where['entitle'] = $entitle;
			$where['id'] = array('neq',$id);
			$res  = db('nav')->where($where)->find();
			$res2 = db('cate')->where($where)->find();		
			if(!empty($res) || !empty($res2)){
				 $result = "no";
			}
		}
	}	
	return $result;		
}


function create_item($data){
    $item="<url>\n";
    $item.="<loc>".$data['loc']."</loc>\n";
    $item.="<priority>".$data['priority']."</priority>\n";
    $item.="<lastmod>".$data['lastmod']."</lastmod>\n";
    $item.="<changefreq>".$data['changefreq']."</changefreq>\n";
    $item.="</url>\n";
    return $item;
}

//生成XML 
function makeXML($data_array){
   $content='<?xml version="1.0" encoding="UTF-8"?>
   <urlset
    xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
    http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
   ';  
   foreach($data_array as $data){
    $content.= create_item($data);
   }
   $content.='</urlset>';
   $fp=fopen('sitemap.xml','w+');
   fwrite($fp,$content);
   fclose($fp);
}


//生成站点地图
function sitemap(){
	$url = db('site')->where('id=1')->value('weburl');			
	//默认
	echo '<div style="width:500px; margin:0 auto; background:#fff; border:1px solid #ddd; padding:20px; line-height:22px; font-size:12px; color:red;">';
	$data_array=array(
		array(
		 'loc'=>$url,
		 'priority'=>'1.0',
		 'lastmod'=>date('Y-m-d H:i:s'),
		 'changefreq'=>'always',
		)
	);			
	//导航
	$nav = nav();
	$a = array();
	if($nav){
		foreach($nav as $key=>$v){
			$a[$key] = array(	
				'loc' 		 => $url.$v['entitle'].'/',
				'priority'   => '1.0',
				'lastmod'	 => date('Y-m-d H:i:s'),
				'changefreq' => 'always',	
			);	
			echo '生成导航'.$url.$v['entitle'].'/'.'<br/>';
		}
		$data_array= array_merge($data_array,$a);
	}	
	//分类
	$cate = db('cate')->field("entitle")->where("nid != 5")->order("nid,sort,id")->select();
	$b = array();
	if($cate){
		foreach($cate as $key=>$v){
			$b[$key] = array(	
				'loc' 		 => $url.$v['entitle'].'/',
				'priority'   => '1.0',
				'lastmod'	 => date('Y-m-d H:i:s'),
				'changefreq' => 'always',	
			);	
			echo '生成分类'.$url.$v['entitle'].'/'.'<br/>';
		}
		$data_array= array_merge($data_array,$b);
	}			
	//文章
	$list = db('article')->field("id,nid,datetime")->where("nid != 5")->order("datetime desc")->select();
	$c = array();
	if($list){
		foreach($list as $key=>$v){
			$entitle = db('nav')->where('id',$v['nid'])->value("entitle");
			$c[$key] = array(	
				'loc' 		 => $url.$entitle.'/'.$v['id'].'.html',
				'priority'   => '0.5',
				'lastmod'	 => date('Y-m-d H:i:s',$v['datetime']),
				'changefreq' => 'daily',	
			);	
			echo '生成文章'.$url.$entitle.'/'.$v['id'].'.html'.'<br/>';			
		}
		echo '</div>';
		$data_array= array_merge($data_array,$c);
	}
	makeXML($data_array);
}

//百度主动推送
function baiduPushUrl($id,$nid){	
	$site = db('site')->field('weburl,webzdts,webzdtsurl')->where('id=1')->find();	
	if($site['webzdts']==1){
		$api = $site['webzdtsurl'];
		$entitle = db('nav')->where('id',$nid)->value('entitle');				
		$urls = array( $site['weburl'].$entitle.'/'.$id.'.html' );		
		$ch = curl_init();
		$options =  array(
			CURLOPT_URL => $api,
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_POSTFIELDS => implode("\n", $urls),
			CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
		);
		curl_setopt_array($ch, $options);
		$result = curl_exec($ch);
		return $result;
	}
}

/**
 * 文章详情匹配一次关键字内链
 *
 * @param  $content string 文章内容
 * @param  $arr     array  关键字数组 
 * 
 */
function articleInner($content, $arr){
    $regular = '<a .*>.*<\/a>|<img .*>|<iframe .*>.*<\/iframe>';
    $chunkeds = preg_split("/($regular)/Ui", $content, -1, PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);    
    $tagArr = array();
 
    foreach ($chunkeds as $k => $val) {
        $bool = preg_match("/($regular)/Ui", $val);  
        if(!$bool){
            foreach ($arr as $key => $value) {
                if(($position = strpos($val,$value['title']))!==false && !in_array($value['title'], $tagArr)){
                    $leng     = strlen($value['title']);
                    $replIntro = '<a href="'.$value['url'].'" target="_blank" title="'.$value['title'].'">'.$value['title'].'</a>';
                    $val       =  substr_replace($val,$replIntro,$position,$leng);
                    $tagArr[]  = $value['title']; //记录已内链的关键字
                }
            }
            $chunkeds[$k] = $val;
         }
    }
    return implode('', $chunkeds);
}

//网站内链转换
function siteLinkReplace($arr){	
	if($arr){
	  $b[0]['title'] = $arr['title'];
	  $b[0]['url'] = $arr['url'];	
	  $list = db('article')->field('id,content')->select();
	  if($list)
	  foreach($list as $key=>$vo){		
		$data['content'] = articleInner($vo['content'],$b);		
		db('article')->where('id',$vo['id'])->update($data);
	  }
    }
}

//删除网站内链
function delSiteLink($arr){
	$str='<a href="'.$arr['url'].'" target="_blank" title="'.$arr['title'].'">'.$arr['title'].'</a>';	
	$list = db('article')->field('id,content')->select();
	if($list)	
	foreach($list as $key=>$vo){		
	  $data['content'] = str_replace($str,$arr['title'],$vo['content']);	  		
	  db('article')->where('id',$vo['id'])->update($data);
	}
}

//添加文章内链
function addArticleSiteLink($body){
	$content;
	if($body!=""){
		$list = db('sitelink')->select();
		$content=articleInner($body,$list);
		return $content;
	}
}


//获取文章简介
function getContentNote($content, $count)
{
    $content = preg_replace("@<script(.*?)</script>@is", "", $content);
    $content = preg_replace("@<iframe(.*?)</iframe>@is", "", $content);
    $content = preg_replace("@<style(.*?)</style>@is", "", $content);
    $content = preg_replace("@<(.*?)>@is", "", $content);
    $content = str_replace(PHP_EOL, '', $content);
    $space = array(" ", "　", "  ", " ", " ");
    $go_away = array("", "", "", "", "");
    $content = str_replace($space, $go_away, $content);
    $res = mb_substr($content, 0, $count, 'UTF-8');
    if (mb_strlen($content, 'UTF-8') > $count) {
        $res = $res . "...";
    }
    return $res;
}









