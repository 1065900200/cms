<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;
use psk\DbManage;
use psk\Backup;

class Database extends Base
{
	public $root; 	//备份目录
	public function __construct(){
		parent::__construct();
		$this->root = ROOT_PATH . 'public/backup/';		
		$this->assign("root",$this->root);
	}
	
	
	//数据库备份
	public function backup(){
	   $type=input("at");
       $name=input("name");	
	   $sql= new Backup();  	   
       $db= new DbManage();
       switch ($type)
        {
        case "backup": //备份		  
		  $db->backup();		 
		  break;     	
        default: //获取数据库文件列表
			$list = $sql->dataList();	
			$this->assign("list",$list);   
            return $this->fetch(); 
          
        }
	}
	
	//还原备份
	public function importsql(){
	   $type=input("at");
       $name=input("name");	
	   $sql= new Backup();  	   
       $db= new DbManage();	  
       switch ($type)
        {
        case "import": //还原备份  
		  $db->restore($this->root.$name);		 
		  break;     	
        default: //获取备份文件列表			
			$list = scandir($this->root);
			array_splice($list, 0, 2); //array_splice删除数组并索引重组			
			$this->assign("list",$list);   
            return $this->fetch(); 
          
        }
	}
	
	//删除备份
	public function delsql(){
		$name = input('name');		
		unlink($this->root.$name);
		return json(['status'=>'y','info'=>'删除成功']);		
	}
	
	
	//下载备份
	public function downloadsql(){		
		$name = input('name');
		$file = $this->root.$name;
		if ( file_exists ( $file )) { 
			header ( 'Content-Description: File Transfer' ); 
			header ( 'Content-Type: application/octet-stream' ); 
			header ( 'Content-Disposition: attachment; filename=' . basename ( $file )); 
			header ( 'Content-Transfer-Encoding: binary' ); 
			header ( 'Expires: 0' ); 
			header ( 'Cache-Control: must-revalidate' ); 
			header ( 'Pragma: public' ); 
			header ( 'Content-Length: ' . filesize ( $file )); 
			ob_clean (); 
			flush (); 
			readfile ( $file ); 
			exit;       
		}
	}	
	
	
}