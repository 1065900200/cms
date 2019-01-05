<?php

namespace rbac;

class TREE
{
	static public $treeList=array(); //存放无限级分类结果
	
	public function create($data, $pid=0)
	{
		foreach($data as $key => $value)
		{
			if($value['pid']==$pid)
			{
				self::$treeList[]=$value;
				unset($data[$key]);
				self::create($data,$value['id']);
			}
		}
		return self::$treeList;
	}
}