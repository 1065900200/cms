<?php
// +----------------------------------------------------------------------
// | Copyright (c) 2018 http://www.hsycms.com All rights reserved.
// | Author: 神夜 <564379992@qq.com>
// +----------------------------------------------------------------------
error_reporting(E_ERROR | E_PARSE );
use PHPMailer\PHPMailer\PHPMailer;

//打印函数
function p($arr){
	echo '<pre>';
	print_r($arr);
	echo '</pre>';	
}

//打印SQL
function printsql($table){
	echo db($table)->getLastSql();
}


//获取原图
function _getbigimg($img){
	$namearr = explode('/',$img);
	$imgname = strstr($namearr[3],'_');	 
	if($imgname!=""){ 
	  $namearr[3] = substr($imgname,1);
	  return '/upload/'.$namearr[2].'/'.$namearr[3];		 	
	}else{
	  return $img;	 
	}
}


//获取分类名
function getCateName($cid,$nid){
	if($cid==""){
		return db('nav')->where('id',$nid)->value('title');
	}else{
		return db('cate')->where('id',$cid)->value('title');
	}
}

//获取文章数量
function getArticleCount($id){
	return db('article')->where('nid',$id)->count();
}

//获取栏目名
function getNavName($nid){
	return db('nav')->where('id',$nid)->value('title');
}

//获取英文名称
function getEntitle($id){
	$nid = db('article')->where('id='.$id)->value('nid');
	return db('nav')->where('id',$nid)->value('entitle');
}

//传入表名查询三级内容前端
function webtreelist($table,$field="*",$where,$num){
	$where['pid'] = 0;
	if($table=="cate"){ $where['isshow']=1; }
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
function webtreecatewhere($cid=0){
	$catecid = array($cid);	//加入本分类	
	$where = '';		
	$sonid = db('cate')->where('pid='.$cid.' and isshow=1')->field('id')->select();		
	if($sonid){
		foreach($sonid as $key=>$v){					
			  $catecid[] .= $v['id'];	//加入本分类
			  foreach($sonid as $key1=>$v1){					 
				 $son2id = db('cate')->where('pid='.$v1['id'].' and isshow=1')->field('id')->select();	
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


//传入数组显示无限级分类
function treearr($arr,$id,$level){
    $list =array();
    foreach ($arr as $k=>$v){
        if ($v['pid'] == $id){
            $v['level']=$level;
            $v['son'] = treearr($arr,$v['id'],$level+1);
            $list[] = $v;
        }
    }
    return $list;
}


//截取字符串
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=false){
	 if(function_exists("mb_substr")){
	  if($suffix)
	   return mb_substr($str, $start, $length, $charset)."...";
	  else
	   return mb_substr($str, $start, $length, $charset);
	 }elseif(function_exists('iconv_substr')) {
	  if($suffix)
	   return iconv_substr($str,$start,$length,$charset)."...";
	  else
	   return iconv_substr($str,$start,$length,$charset);
	 }
	 $re['utf-8'] = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef][x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
	 $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
	 $re['gbk'] = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
	 $re['big5'] = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
	 preg_match_all($re[$charset], $str, $match);
	 $slice = join("",array_slice($match[0], $start, $length));
	 if($suffix) return $slice."…";
	 return $slice;
}

//二维数组去掉重复值
function array_unique_fb($array2D){
    foreach ($array2D as $v)
    {
        $v = join(",",$v);  //降维,也可以用implode,将一维数组转换为用逗号连接的字符串
        $temp[] = $v;
    }
    
    $temp = array_unique($temp);   //去掉重复的字符串,也就是重复的一维数组
    foreach ($temp as $k => $v)
    {
        $temp[$k] = explode(",",$v);  //再将拆开的数组重新组装
    }
    return $temp;
}

//发邮件
function sendemail($data){
   $site = db('site')->where('id=1')->find();
   $mail = new PHPMailer();
	  try{
		  //邮件调试模式
		  $mail->SMTPDebug = 0;  
		  //设置邮件使用SMTP
		  $mail->isSMTP();
		  // 设置邮件程序以使用SMTP
		  $mail->Host = $site['email_host'];
		  // 设置邮件内容的编码
		  $mail->CharSet='UTF-8';
		  // 启用SMTP验证
		  $mail->SMTPAuth = true;
		  // SMTP username
		  $mail->Username = $site['email_send_name'];
		  // SMTP password
		  $mail->Password = $site['email_send_pass'];
		  // 启用TLS加密，`ssl`也被接受
		  //$mail->SMTPSecure = 'tls';
		  if($site['email_smtpsecure']!=""){
		  	$mail->SMTPSecure = $site['email_smtpsecure'];
		  }
		  // 连接的TCP端口
		  $mail->Port = $site['email_port'];
		  //设置发件人
		  $mail->setFrom($site['email_send_name'], $site['webtitle']);
		  //网站管理后台添加收件人1
		  $mail->addAddress($site['email_form'], $site['email_form_name']);     // Add a recipient
		  //$mail->addAddress('ellen@example.com');               // Name is optional
		  //收件人回复的邮箱
		  $mail->addReplyTo($data['email'], $data['name']); //网页端客户的邮箱地址，客户的姓名字
		  //抄送
		  //$mail->addCC('cc@example.com');
		  //$mail->addBCC('bcc@example.com');
		  //附件
		  //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
		  //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
		  //Content
		  // 将电子邮件格式设置为HTML
		  $mail->isHTML(true);
		  $mail->Subject = $data['title'];
		  $mail->Body    = $data['content'];
		  //$mail->AltBody = '这是非HTML邮件客户端的纯文本';
		  $mail->send();
		  //echo 'Message has been sent';
		  //$mail->isSMTP();
	  }catch (Exception $e){
		  echo 'Mailer Error: ' . $mail->ErrorInfo;
	  }
} 

//导航
function nav(){
	$list = db('nav')->where('isshow=1')->field('showcate,id,title,entitle')->order('sort,id')->select();
	if($list){
		foreach($list as $key=>$v){				
			$list[$key]['link'] = '/'.$v['entitle'].'.html';				
			if($v['showcate']==1){
				$list[$key]['son'] = db('cate')->where("nid=".$v['id']." and isshow=1 and pid=0")->field('id,title,entitle')->order("sort,id")->select();
				if($list[$key]['son'])foreach($list[$key]['son'] as $key1=>$v1){						
					$list[$key]['son'][$key1]['link'] = '/'.$v1['entitle'].'.html';
				}
			}else{
				$list[$key]['son'] = db('article')->where("nid=".$v['id'])->field('title,id')->order("sort,id")->select();
				if($list[$key]['son'])foreach($list[$key]['son'] as $key2=>$v2){
					$list[$key]['son'][$key2]['link'] = '/'.$v['entitle'].'/'.$v2['id'].'.html';
				}
			}
		}
	}
	return $list;		
}

//获取蜘蛛爬虫痕迹
function spider(){
	$ax_ym  = $_SERVER['REQUEST_URI'];
	$ax_ss  = $_SERVER['HTTP_USER_AGENT'];
	$ax_url = $_SERVER['HTTP_REFERER'];
	$ax_ip  = $_SERVER['HTTP_X_FORWARDED_FOR'];
	if(empty($ax_ip)){
		$ax_ip = $_SERVER['REMOTE_ADDR'];
	}	
	$baidu=stristr($ax_ss,"Baiduspider");
	$google=stristr($ax_ss,"Googlebot");
	$soso=stristr($ax_ss,"Sosospider");
	$youdao=stristr($ax_ss,"YoudaoBot");
	$bing=stristr($ax_ss,"bingbot");
	$sogou=stristr($ax_ss,"Sogou web spider");
	$yahoo=stristr($ax_ss,"Yahoo! Slurp");
	$Alexa=stristr($ax_ss,"Alexa");
	$so=stristr($ax_ss,"360Spider");
	if($baidu)
	{
		$ax_ss="baidu";
	}
	elseif($google)
	{
		$ax_ss="Google";
	}
	elseif($soso)
	{
		$ax_ss="soso";
	}
	elseif($youdao)
	{
		$ax_ss="youdao";
	}
	elseif($bing)
	{
		$ax_ss="bing";
	}
	elseif($sogou)
	{
		$ax_ss="sogou";
	}
	elseif($yahoo)
	{
		$ax_ss="yahoo";
	}
	elseif($Alexa)
	{
		$ax_ss="Alexa";
	}
	elseif($so)
	{
		$ax_ss="so";
	}
	else
	{
		$ax_ss=null;
	}
	$data['title']    = $ax_ss;	
	$data['url']      = $ax_ym;
	$data['oldurl']   = $ax_url;
	$data['ip']       = $ax_ip;
	$data['datetime'] = time();
	if($baidu or $google or $soso or $youdao or $bing or $sogou or $yahoo or $Alexa or $so)
	{
		$res = db('spider')->insert($data);
	}
}

