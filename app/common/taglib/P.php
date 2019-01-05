<?php
namespace app\common\taglib;
use think\template\TagLib;
use think\Config;
// +----------------------------------------------------------------------
// +----------------------------------------------------------------------
class P extends TagLib{
    /**
     * 定义标签列表
     */
    protected $tags   =  [
        'sql'        => ['attr' => 'sql,key,id,mod,cachetime,empty', 'close'=>1], // psk sql 万能标签
		'cate'      => ['attr' => 'nid,num,name', 'close' => 1],
		'one'       => ['attr' => 'aid,name', 'close' => 1],
		'link'      => ['attr' => 'num,name', 'close' => 1],
		'cateone'   => ['attr' => 'cid,field,len', 'close' => 0],
        'jone'      => ['attr' => 'cid,aid,field', 'close' => 0],
        'navone'    => ['attr' => 'nid,field,len', 'close' => 0],
        'arclist'    => ['attr' => 'key,id,mid,nid,cid,nocid,flag,noflag,isimg,empty,titlelen,infolen,row,limit,orderby,mod,name,offset', 'close'=>1],
    ];
    

    /**
     * 自动识别构建变量，传值可以使变量也可以是值
     * @access private
     * @param string $value 值或变量
     * @return string
     */
    private function varOrvalue($value)
    {
        $flag  = substr($value, 0, 1);
        if ('$' == $flag || ':' == $flag) {
            $value = $this->autoBuildVar($value);
        } else {
            $value = str_replace('"', '\"', $value);
            $value = '"' . $value . '"';
        }

        return $value;
    }

	
    public function tagSql($tag, $content)
    {

       $sql = $tag['sql']; // sql 语句
       $sql  = $this->varOrvalue($sql);
       $key    = !empty($tag['key']) ? $tag['key'] : 'i';
       $mod    = isset($tag['mod']) ? $tag['mod'] : '2';
       $id  =  !empty($tag['id']) ? $tag['id'] : 'field';// 返回的变量
       $cachetime  =  !empty($tag['cachetime']) ? $tag['cachetime'] : '';// 缓存时间
       $empty  = isset($tag['empty']) ? $tag['empty'] : '';
       $empty  = htmlspecialchars($empty);
      
       $parseStr = '<?php ';
       $parseStr .= ' $tagSql = new \app\common\taglib\p\TagSql;';
       $parseStr .= ' $_result = $tagSql->getSql('.$sql.', "'.$cachetime.'");';

       $parseStr .= 'if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $' . $key . ' = 0; $e = 1;';
       $parseStr .= ' $__LIST__ = $_result;';

       $parseStr .= 'if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("' . $empty . '");';
       $parseStr .= 'else: ';
       $parseStr .= 'foreach($__LIST__ as $key=>$' . $id . '): ';
       $parseStr .= '$mod = ($e % ' . $mod . ' );';
       $parseStr .= '$' . $key . '= intval($key) + 1;?>';
       $parseStr .= $content;
       $parseStr .= '<?php ++$e; ?>';
       $parseStr .= '<?php endforeach; endif; else: echo htmlspecialchars_decode("' . $empty . '");endif; ?>';


       if (!empty($parseStr)) {
        return $parseStr;
       }
       return;
    }	

    /**
     * arclist标签解析 获取指定文档列表（兼容tp的volist标签语法）
     * 格式：
     * {p:arclist mid='1' nid='1' cid='1' row='10' titlelen='30' orderby ='id desc' flag='' infolen='160' empty='' id='field' mod='' }
     *  {$field.title}
     *  {$field.mid}
     * {/p:arclist}
     * @access public
     * @param array $tag 标签属性
     * @param string $content 标签内容
     * @return string|void
     */
    public function tagArclist($tag, $content)
    {
        $mid     = !empty($tag['mid']) ? $tag['mid'] : '';
        $mid  = $this->varOrvalue($mid);

        $nid     = !empty($tag['nid']) ? $tag['nid'] : '';
        $nid  = $this->varOrvalue($nid);

        $cid     = !empty($tag['cid']) ? $tag['cid'] : '';
        $cid  = $this->varOrvalue($cid);

        $name   = !empty($tag['name']) ? $tag['name'] : '';
        $id     = isset($tag['id']) ? $tag['id'] : 'field';
        $key    = !empty($tag['key']) ? $tag['key'] : 'i';
        $empty  = isset($tag['empty']) ? $tag['empty'] : '';
        $empty  = htmlspecialchars($empty);
        $mod    = isset($tag['mod']) ? $tag['mod'] : '2';
        $isimg    = isset($tag['isimg']) ? $tag['isimg'] : '0';
        $orderby    = isset($tag['orderby']) ? $tag['orderby'] : '';
        $flag    = isset($tag['flag']) ? $tag['flag'] : '';
        $noflag    = isset($tag['noflag']) ? $tag['noflag'] : '';
        $titlelen = !empty($tag['titlelen']) && is_numeric($tag['titlelen']) ? intval($tag['titlelen']) : 100;
        $infolen = !empty($tag['infolen']) && is_numeric($tag['infolen']) ? intval($tag['infolen']) : 160;
        $offset = !empty($tag['offset']) && is_numeric($tag['offset']) ? intval($tag['offset']) : 0;
        $row = !empty($tag['row']) && is_numeric($tag['row']) ? intval($tag['row']) : 10;
        if (!empty($tag['limit'])) {
            $limitArr = explode(',', $tag['limit']);
            $offset = !empty($limitArr[0]) ? intval($limitArr[0]) : 0;
            $row = !empty($limitArr[1]) ? intval($limitArr[1]) : 0;
        }

	
        $parseStr = '<?php ';
        $parseStr .= '$mids = '.$mid.';';
        $parseStr .= '$nids = '.$nid.';';
        $parseStr .= '$cids = '.$cid.';';

        if ($name) { // 从模板中传入数据集
            $symbol     = substr($name, 0, 1);
            if (':' == $symbol) {
                $name = $this->autoBuildVar($name);
                $parseStr .= '$_result=' . $name . ';';
                $name = '$_result';
            } else {
                $name = $this->autoBuildVar($name);
            }

            $parseStr .= 'if(is_array(' . $name . ') || ' . $name . ' instanceof \think\Collection || ' . $name . ' instanceof \think\Paginator): $' . $key . ' = 0; $e = 1;';
            // 设置了输出数组长度
            if (0 != $offset || 'null' != $row) {
                $parseStr .= '$__LIST__ = is_array(' . $name . ') ? array_slice(' . $name . ',' . $offset . ',' . $row . ', true) : ' . $name . '->slice(' . $offset . ',' . $row . ', true); ';
            } else {
                $parseStr .= ' $__LIST__ = ' . $name . ';';
            }

        }else{
            $parseStr .= ' $param = array(';
            $parseStr .= '      "mid"=> $mids,';
            $parseStr .= '      "nid"=> $nids,';
            $parseStr .= '      "cid"=> $cids,';
            $parseStr .= '      "flag"=> "'.$flag.'",';
            $parseStr .= '      "noflag"=> "'.$noflag.'",';
            $parseStr .= '      "isimg"=> "'.$isimg.'",';
            $parseStr .= ' );';
            
            $parseStr .= ' $TagArclist = new \app\common\taglib\p\TagArclist;';
            $parseStr .= ' $_result = $TagArclist->getArclist($param,"'.$row.'", "'.$orderby.'");';

            $parseStr .= 'if(is_array($_result) || $_result instanceof \think\Collection || $_result instanceof \think\Paginator): $' . $key . ' = 0; $e = 1;';
            $parseStr .= ' $__LIST__ = $_result;';
        }

		

        $parseStr .= 'if( count($__LIST__)==0 ) : echo htmlspecialchars_decode("' . $empty . '");';
        $parseStr .= 'else: ';
        $parseStr .= 'foreach($__LIST__ as $key=>$' . $id . '): ';
        $parseStr .= '$' . $id . '["title"] = msubstr($' . $id . '["title"], 0, '.$titlelen.',"utf-8", false);';
        $parseStr .= '$' . $id . '["note"] = strip_tags($' . $id . '["note"]);';
        $parseStr .= '$' . $id . '["note"] = msubstr($' . $id . '["note"], 0, '.$infolen.',"utf-8", true);';
        $parseStr .= '$mod = ($e % ' . $mod . ' );';
        $parseStr .= '$' . $key . '= intval($key) + 1;?>';
        $parseStr .= $content;
        $parseStr .= '<?php ++$e; ?>';
        $parseStr .= '<?php endforeach; endif; else: echo htmlspecialchars_decode("' . $empty . '");endif; ?>';
        
       if (!empty($parseStr)) {
       return $parseStr;
       }
       return;
    }


	/**
     * cate 分类列表 可传入参数：nid 栏目id , num 显示数量
     */
    public function tagcate($tag, $content)
    {       
        $name 	 = $tag['name'];
		$where= 'pid = 0 and nid = '.$tag['nid'].' and isshow=1';		
		$sort = 'sort,id';
		$num  = $tag['num']?$tag['num']:10;		
        $parse = '<?php ';
        $parse .= '$test_arr=db("cate")->where("'.$where.'")->order("'.$sort.'")->limit("'.$num.'")->select();'; 
        $parse .= '$__LIST__ = $test_arr;';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }

	
	 /**
     * article 单篇文章 可传入参数：id 文章ID 
     */
    public function tagone($tag, $content)
    {       
        $name 	 = $tag['name'];
		$where   = 'id = '.$tag['aid']; 
        $parse = '<?php ';
        $parse .= '$test_arr=db("article")->where("'.$where.'")->select();'; 	
        $parse .= '$__LIST__ = $test_arr;';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }
	
	 /**
     * cate 单个分类 可传入参数：cid 分类ID 
     */
    public function tagcateone($tag, $content)
    {       
        $cid  = !empty($tag['cid']) ? $tag['cid'] : '';
        $cid  = $this->varOrvalue($cid);

        $field   = !empty($tag['field']) ? $tag['field'] : '';

        $len = !empty($tag['len']) && is_numeric($tag['len']) ? intval($tag['len']) : 160;

        $parse = '<?php ';
        $parse .= '$cids = ' . $cid . ';';
        $parse .= '$where="id=$cids";';
        $parse .= '$test_arr=db("cate")->where($where)->field("'.$field.'")->find();'; 	
        $parse .= '$__LIST__ = $test_arr;';
        $parse .= '$__LIST__["'.$field.'"] = strip_tags($__LIST__["'.$field.'"]);';
        $parse .= '$__LIST__["'.$field.'"] = msubstr($__LIST__["'.$field.'"], 0, '.$len.',"utf-8", false);';
        $parse .= 'echo $__LIST__["'.$field.'"];';
        $parse .= ' ?>';
        return $parse;
    }
    
    /**
     * article 单篇文章 可传入参数：aid 文章ID 
     */
    public function tagnavone($tag, $content)
    {   
        $nid  = !empty($tag['nid']) ? $tag['nid'] : '';
        $nid  = $this->varOrvalue($nid);

        $field   = !empty($tag['field']) ? $tag['field'] : '';

        $len = !empty($tag['len']) && is_numeric($tag['len']) ? intval($tag['len']) : 160;

        $parseStr = '<?php ';
        $parseStr .= '$nids = ' . $nid . ';';
        $parseStr .= '$where="id=$nids";';
        $parseStr .= '$test_arr=db("nav")->where($where)->field("'.$field.'")->find();';                
        $parseStr .= '$__LIST__ = $test_arr;';
        $parseStr .= '$__LIST__["'.$field.'"] = strip_tags($__LIST__["'.$field.'"]);';
        $parseStr .= '$__LIST__["'.$field.'"] = msubstr($__LIST__["'.$field.'"], 0, '.$len.',"utf-8", false);';
        $parseStr .= 'echo $__LIST__["'.$field.'"];';
        $parseStr .= ' ?>';
   
        return $parseStr;

    }
	/**
     * link 友情连接 可传入参数： num 显示数量
     */
    public function taglink($tag, $content)
    {       
        $name 	 = $tag['name'];		
		$sort = 'id';
		$num  = $tag['num'];		
        $parse = '<?php ';
        $parse .= '$test_arr=db("link")->order("'.$sort.'")->limit("'.$num.'")->select();'; 		
        $parse .= '$__LIST__ = $test_arr;';
        $parse .= ' ?>';
        $parse .= '{volist name="__LIST__" id="' . $name . '"}';
        $parse .= $content;
        $parse .= '{/volist}';
        return $parse;
    }
    
   /**
     * article 单篇文章 可传入参数：aid 文章ID 
     */
    public function tagjone($tag, $content)
    {   
        $cid   = !empty($tag['cid']) ? $tag['cid'] : '';

        $aid  = !empty($tag['aid']) ? $tag['aid'] : '';
        $aid  = $this->varOrvalue($aid);

        $field   = !empty($tag['field']) ? $tag['field'] : '';

        $parseStr = '<?php ';
        $parseStr .= '$aids = '.$aid.';';
        if ($cid) { // 从模板中传入数据集
            $symbol     = substr($cid, 0, 1);
            if (':' == $symbol) {
                $cid = $this->autoBuildVar($cid);
                $parseStr .= '$_result=' . $cid . ';';
                $cid = '$_result';
            } else {
                $cid = $this->autoBuildVar($cid);
            }
            $parseStr .= ' $cids = ' . $cid . ';';
        }

        $parseStr .= '$where="";';

        $parseStr .= 'if($cids):';
        $parseStr .= '$where.="cid=$cids and";';
        $parseStr .= 'endif;';

        $parseStr .= 'if($aid) :';
        $parseStr .= '$where.="id=$aids and";';
        $parseStr .= 'endif;';

       
        $parseStr .= '$where=rtrim($where," and ");';
        $parseStr .= '$test_arr=db("article")->where($where)->field("'.$field.'")->find();'; 				
        $parseStr .= '$__LIST__ = $test_arr;';

        $parseStr .= 'echo $__LIST__["'.$field.'"];';
        $parseStr .= ' ?>';
        return $parseStr;

    }





    
}