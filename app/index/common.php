<?php

//前台公用函数

//SEO
function getseo($nid,$id,$cid){
	 //输入网站配置信息	
      $site = db('site')->where('id=1')->find();	  
	  //栏目关键词
	  if($nid!=""){
	  		$seo = db('nav')->where("id",$nid)->find();
	  		$site['webtitle']    	= $seo['seo_title']==""?$seo['title'].'-'.$site['webtitle']:$seo['seo_title'];	  
	  		$site['webkeywords'] 	= $seo['seo_keywords']==""?$site['webkeywords']:$seo['seo_keywords'];
	 		$site['webdescription'] = $seo['seo_description']==""?$site['webdescription']:$seo['seo_description'];
			
			if($id!="" && $cid==""){ 
			    $c = db('article')->where("id",$id)->find();
				$site['webtitle']       = $c['seo_title']==""?$c['title'].'-'.$site['webtitle']:$c['seo_title'];	  
	  			$site['webkeywords']    = $c['seo_keywords']==""?$site['webkeywords']:$c['seo_keywords'];
	 			$site['webdescription'] = $c['seo_description']==""?$site['webdescription']:$c['seo_description'];			
			}
						
			if($cid!="" && $id==""){ 
			    $c = db('cate')->where("id",$cid)->find();
				$site['webtitle']       = $c['seo_title']==""?$c['title'].'-'.$site['webtitle']:$c['seo_title'];	  
	  			$site['webkeywords']    = $c['seo_keywords']==""?$site['webkeywords']:$c['seo_keywords'];
	 			$site['webdescription'] = $c['seo_description']==""?$site['webdescription']:$c['seo_description'];			
			}
			
			if($cid!="" && $id!=""){ 
			    $c = db('cate')->where("id",$cid)->find();
				$l = db('article')->where("id",$id)->find();
				$site['webtitle']       = $l['seo_title']==""?$l['title'].'-'.$site['webtitle']:$l['seo_title'];  
	  			$site['webkeywords']    = $l['seo_keywords']==""?$site['webkeywords']:$l['seo_keywords'];
	 			$site['webdescription'] = $l['seo_description']==""?$l['note']:$l['seo_description'];			
			}			
			
  			
	  }
	  
	  $site['webdescription']=clear_all($site['webdescription']);
	  return $site;
}

//获取栏目ID参数
function getNID(){
	$url = $_SERVER["QUERY_STRING"];	
	$url = substr($url,2);	
	$url = substr($url,0,-1);	
	if(strpos($url,'&page') !== false){		
		$url = substr($url,0,-6);				
	}
	if(strpos($url,'&title') !== false){
		$url = substr($url,0,strrpos($url,'/'));					
	}
	return $url;
}

//获取上下篇
function prevNext($id,$entitle,$one){ 
	  //上一篇
	  if($one['cid']){
		$prev_where="id < {$id} and nid={$one['nid']} and cid={$one['cid']}";
	  }else{
		$prev_where="id < {$id} and nid={$one['nid']}";
	  }
	  $prev=db('article')->field("id,title")->where($prev_where)->order('id desc')->limit('1')->find();  
	  if($prev){
		  $prev['url'] = '/'.$entitle.'/'.$prev['id'].'.html';
	  }else{
		  $prev['url'] = "javascript:void(0)";
		  $prev['title'] = "没有了";
	  }
	  $data['prev'] = $prev; 
	  
	  //下一篇
	  if($one['cid']){
		$next_where="id > {$id} and nid={$one['nid']} and cid={$one['cid']}";
	  }else{
		$next_where="id > {$id} and nid={$one['nid']}";
	  }
	  $next = db('article')->field("id,title")->where($next_where)->order('id asc')->limit('1')->find();  
	  if($next){
		  $next['url'] =  '/'.$entitle.'/'.$next['id'].'.html';
	  }else{
		  $next['url'] = "javascript:void(0)";
		  $next['title'] = "没有了";
	  }
	  $data['next'] = $next;
	  return $data;
}

//判断是否手机访问
function isMobile()
{ 

    // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
    if (isset ($_SERVER['HTTP_X_WAP_PROFILE']))
    {
        return true;
    } 
    // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
    if (isset ($_SERVER['HTTP_VIA']))
    { 
        // 找不到为flase,否则为true
        return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
    } 
    // 脑残法，判断手机发送的客户端标志,兼容性有待提高
    if (isset ($_SERVER['HTTP_USER_AGENT']))
    {
        $clientkeywords = array ('nokia',
            'sony',
            'ericsson',
            'mot',
            'samsung',
            'htc',
            'sgh',
            'lg',
            'sharp',
            'sie-',
            'philips',
            'panasonic',
            'alcatel',
            'lenovo',
            'iphone',
            'ipod',
            'blackberry',
            'meizu',
            'android',
            'netfront',
            'symbian',
            'ucweb',
            'windowsce',
            'palm',
            'operamini',
            'operamobi',
            'openwave',
            'nexusone',
            'cldc',
            'midp',
            'wap',
            'mobile'
            ); 
        // 从HTTP_USER_AGENT中查找手机浏览器的关键字
        if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT'])))
        {
            return true;
        } 
    } 
    // 协议法，因为有可能不准确，放到最后判断
    if (isset ($_SERVER['HTTP_ACCEPT']))
    { 
        // 如果只支持wml并且不支持html那一定是移动设备
        // 如果支持wml和html但是wml在html之前则是移动设备
        if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))))
        {
            return true;
        } 
    } 
    return false;
} 


function get_site($field){
	$site = db('site')->field($field)->where('id=1')->find();
	return $site[$field];
}


function clear_all($area_str){ //过滤成纯文本用于显示
    if ($area_str!=''){
        $area_str = trim($area_str); //清除字符串两边的空格
        $area_str = strip_tags($area_str,""); //利用php自带的函数清除html格式
        $area_str = str_replace("&nbsp;","",$area_str);
         
        $area_str = preg_replace("/   /","",$area_str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $area_str = preg_replace("/
/","",$area_str); 
        $area_str = preg_replace("/
/","",$area_str); 
        $area_str = preg_replace("/
/","",$area_str); 
        $area_str = preg_replace("/ /","",$area_str);
        $area_str = preg_replace("/  /","",$area_str);  //匹配html中的空格
        $area_str = trim($area_str); //返回字符串
    }
    return $area_str;
}




