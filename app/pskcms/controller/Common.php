<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;
use think\Request; 



class Common extends Base
{
	
	//上传图片显示
    public function uploadimg() {
		$id = input('id');
		$at = input('at');	
		$this->assign("id",input('id'));
		$this->assign("at",input('at'));		
		$this->assign("type",1);
		$list = db('upload')->where('type=1')->order("id desc")->paginate(18,false,['query' => request()->param()]);
        $page = $list->render();
        $this->assign("page",$page);
		$this->assign("list",$list);
		return $this->fetch(); 
	}
	
	//上传文件处理
    public function uploadfile() {
		$this->assign("id",input('id'));
		$this->assign("at",input('at',0));
		$this->assign("type",2);
		$map['type'] = 2;
		if(input('at')=="video"){
			$map['ext'] =  array(array('eq','avi'),array('eq','mp4'),array('eq','swf'),array('eq','rmvb'), 'or');
		}		
		$list = db('upload')->where($map)->order("id desc")->select();
		$this->assign("list",$list);
		return $this->fetch(); 
	}
	
	//上传图片处理
	public function doupload(){		
		$file = request()->file('file');		 
		if($file){
		  $site = db('site')->where('id=1')->field("upload_size,upload_ext")->find();		  
		  $size = $site['upload_size']?$site['upload_size']:1024*1024*10; //允许上传10M	  
		  $ext  = $site['upload_ext']?$site['upload_ext']:'jpg,gif,png,jpeg,doc,docx,xls,xlsx,ppt,txt,zip,rar,gz,bz2,pdf,ios,7z,avi,mp4,swf,wmv,rm,rmvb,mkv,mp3,wma,wav';	  		  
		  $info = $file->validate(['size'=>$size,'ext'=>$ext])->move(ROOT_PATH . DS . 'upload');			 
		  if($info){			  
			  $type  = 1;
			  		  
			  //原路径
			  $url = str_replace('\\', '/', $info->getPathname());
			  $url = substr($url,strpos($url,'//')+1);
			 
			  $namearr = explode('.',$info->getFilename());				 
			  $imgarry = array("jpg","JPG","png","PNG","gif","GIF","bmp","BMP","jpeg","JPEG");			  	  
			  if(!in_array($namearr[1],$imgarry)){
					$type = 2; 
			  }	
			  
			  //如果是图片生成缩略图		  
			  if($type==1){
				  //缩略图路径
				  if(strtoupper(substr(PHP_OS,0,3))==='WIN'){
				  	$date= explode('\\',$info->getSaveName());
			      }else{
					$date= explode('/',$info->getSaveName());
				  }
				  $name = '88x88_'.$namearr[0].'.'.$namearr[1];
				  $thumb = '/upload/'.$date[0].'/'.$name; 	
				  $thumburl = ROOT_PATH . DS . 'upload/'.$date[0].'/'.$name; 			  
				  
				  //生成缩略图
				  $image = \think\Image::open($info->getPathname());
				  $image->thumb(88,88,\think\Image::THUMB_SCALING)->save($thumburl);				  
				  $data['thumb'] = $thumb;
			  }			  
			  $data['name']  = $namearr[0];
			  $data['url']   = $url;
			  $data['type']  = $type;
			  $data['ext']   = $namearr[1];			
			  db('upload')->insert($data);	
			  if($type==1){			  	  	  
			  	return json(['status'=>'y','info'=>'图片上传成功','url'=>$url,'thumb'=>$thumb]);
			  }else{
			  	return json(['status'=>'y','info'=>'文件上传成功','url'=>$url,'ext'=>$namearr[1],'name'=>$namearr[0]]);
			  }
		  }else{
			   return json(['status'=>'0','info'=>$file->getError()]);
		  }
		}		
	}
	
	
	//图标管理
	public function icon(){
		$this->assign("name",input('name'));
		return $this->fetch();
	}
	
	//清除缓存
	public function clearCache(){
		array_map('unlink', glob(TEMP_PATH . '/*.php'));
		rmdir(TEMP_PATH);
		$this->success( '清除成功');
	}
	
	
}