<?php
namespace app\pskcms\controller;
use think\Controller;
use app\pskcms\controller\Base;
use rbac\TREE;

/**【权限管理】**/

class Rbac extends Base
{
    public function _initialize()
    {
        parent::_initialize();  //RBAC 验证接口初始化
    }
	
	//用户列表
	public function user(){
		$list = db('user')->select();
		if($list)foreach($list as $key=>$v){
			$list[$key]['rolename'] = db('role')->where('id',$v['role'])->value('name');
		}
		$this->assign("list",$list);		
		return $this->fetch(); 
	}
	
	//检测用户名
	public function checkuser(){
		$where['username'] = input('param');
		$id = input('id');		
		if($id!=""){
			$where['id'] = array('neq',$id);
		}	
		$res = db('user')->where($where)->find();		
		if($res){
			return json(['status'=>'n','info'=>'用户名已存在！']);
		}else{
			return json(['status'=>'y','info'=>'']);
		}
	}
	
	//添加编辑用户
	public function adduser(){
		$id = input('id');
		$role = db('role')->where('status=1')->select();
		if($_POST){
			$m = db('user');
			$data=input("post.");		
			if($id==""){
				if($data['password']!="") { $data['password'] = md5($data['password']);}				
				$id = $m->insertGetId($data);
				db('role_user')->insert(['user_id'=>$id,'role_id'=>$data['role']]);
				return json(['status'=>'y','info'=>'添加成功']);
			}else{
				$username = db('user')->where('id',$id)->value('username');				
				if(session('username')!= 'admin' && $username != session('username')){
					return json(['status'=>'0','info'=>'只难修改自已的信息']);
				}				
				if($data['password']!="") { $data['password'] = md5($data['password']); }
				if($data['password']=="") { unset($data['password']); }
				$d['role_id']= $data['role'];
				db('role_user')->where('user_id',$id)->update($d);
				if($m->where('id',$id)->update($data)!==false)
				return json(['status'=>'y','info'=>'修改成功']);
			}						
		}else{
			if($id!=""){
				$one = db('user')->where('id',$id)->find();
				$this->assign("v",$one);
			}
			$this->assign("role",$role);
			return $this->fetch();
		}
	}
	
	//删除用户
	public function deluser(){
		$id = input('id');
		if($id==session(config('USER_AUTH_KEY'))){ 
			return json(['status'=>'n','info'=>'不能删除自己呢。']);	
		}
		if(db('user')->where('id',$id)->value('username')=='admin'){			
			return json(['status'=>'n','info'=>'不能删除超级管理员']);	
		}
		
		db('user')->where('id',$id)->delete();
		db('role_user')->where('user_id',$id)->delete();
		return json(['status'=>'y','info'=>'删除成功']);		
	}
	
	
	//角色列表
	public function role(){
		$list = db('role')->select();		
		$this->assign("list",$list);		
		return $this->fetch(); 
	}
	
	//角色名检测
	public function checkrole(){
		$where['name'] = input('param');
		$id = input('id');		
		if($id!=""){
			$where['id'] = array('neq',$id);
		}		
		$res = db('role')->where($where)->find();
		if($res){
			return json(['status'=>'n','info'=>'角色名称已存在！']);
		}else{
			return json(['status'=>'y','info'=>'']);
		}
	}
	
	//添加编辑角色
	public function addrole(){
		$id = input('id');
		if($_POST){
			$m = db('role');
			$data=input("post.");
			if($id==""){				
				$m->insert($data);
				return json(['status'=>'y','info'=>'添加成功']);
			}else{			
				if($m->where('id',$id)->update($data)!==false)
				return json(['status'=>'y','info'=>'修改成功']);
			}						
		}else{
			if($id!=""){
				$one = db('role')->where('id',$id)->find();
				$this->assign("v",$one);
			}
			return $this->fetch();
		}
	}
	
	//删除角色
	public function delrole(){
		$id = input('id');
		if(db('user')->where('role',$id)->find()){			
			return json(['status'=>'n','info'=>'角色已分配给用户不能删除']);	
		}
		db('role')->where('id',$id)->delete();
		db('access')->where('role_id',$id)->delete();
		return json(['status'=>'y','info'=>'删除成功']);		
	}
	
		
	//节点列表
	public function node(){
	   $node= db('node')->order('sort,id')->select();
	   $list = TREE::create($node);
	   $this->assign("list",$list);	
	   return $this->fetch();
	}	
	
	//添加节点
	public function addnode(){
		$id = input('id');		
		if($_POST){
			$m = db('node');
			$data=input("post.");
			if($id==""){				
				$m->insert($data);
				return json(['status'=>'y','info'=>'添加成功']);
			}else{			
				if($m->where('id',$id)->update($data)!==false)
				return json(['status'=>'y','info'=>'修改成功']);
			}						
		}else{
			$node = db('node')->where('level!=3')->order("sort,id")->select();
			$node = TREE::create($node);
			if($id!=""){
				$one = db('node')->where('id',$id)->find();
				$this->assign("v",$one);
			}
			$this->assign("node",$node);
			return $this->fetch();
		}
	}
	
	//删除节点
	public function delnode(){
		$id = input('id');
		if(db('node')->where('pid',$id)->find()){			
			return json(['status'=>'n','info'=>'该控制器下已有方法，无法删除']);	
		}
		db('node')->where('id',$id)->delete();
		db('access')->where('node_id',$id)->delete();
		return json(['status'=>'y','info'=>'删除成功']);		
	}
	
	
	//权限分配
	public function access(){
		$role_id = input('id');		
		if($_POST){
			$d = input('post.');
			if(empty($d['node_id'])){ 
				return json(['status'=>'n','info'=>'请选择要分配的权限']);
			}			
			db('access')->where('role_id',$d['role_id'])->delete();			
			foreach($d['node_id'] as $key=>$v){
				$data['role_id']=$d['role_id'];
				$data['node_id']=$v;
				db('access')->insert($data);					
			}
			return json(['status'=>'y','info'=>'权限分配成功']);
		}else{			
			$node = db('node')->order('sort,id')->select(); 
			$access = db('access')->where('role_id',$role_id)->select();			
			if($access){
			  foreach($node as $key=>$v){
				foreach($access as $key1=>$v1){
					if($v1['node_id']==$v['id']){
						$node[$key]['flag']=1;
					}
				}
			  }
			}
			$list = TREE::create($node);			
			$this->assign("list",$list);
			$this->assign("id",$role_id);
			return $this->fetch();
		}
	}
	
	
	
}
