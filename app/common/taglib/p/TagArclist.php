<?php

namespace app\common\taglib\p;

/**
 * SQL万能标签
 */
class TagArclist extends Base
{
    //初始化
    protected function _initialize()
    {
        parent::_initialize();
    }


    public function getArclist($param = array(),  $limit = 15, $orderby = ''){
        $result = false;

        if(empty($param['mid']) && empty($param['nid']) && empty($param['cid'])){
            echo '标签arclist报错：请添加模块id或导航id或分类id。';
            return false;
        }else{
            // 查询条件
            $condition = array();
            
            //模型id
            if(!empty($param['mid'])){
                array_push($condition, "mid = '".$param['mid']."'");
            }

            //导航id
            if(!empty($param['nid'])){
				array_push($condition, "nid IN ('".$param['nid']."')");
            }

            //分类id
            if(!empty($param['cid'])){
                array_push($condition, "cid IN ('".$param['cid']."')");
            }

            //是否包含缩略图
            if(!empty($param['isimg'])){
                array_push($condition, "img <>''");
            }


            //推荐位
            if (!empty($param['flag'])) {
                $flag_arr = explode(",", $param['flag']);
                $where_or_flag = array();
                foreach ($flag_arr as $k2 => $v2) {
                    array_push($where_or_flag, "".$v2." = 1");
                }
                if (!empty($where_or_flag)) {
                    $where_flag_str = " (".implode(" OR ", $where_or_flag).") ";
                    array_push($condition, $where_flag_str);
                }
            }

            //不包含推荐位
            if (!empty($param['noflag'])) {
                $flag_arr = explode(",", $param[$key]);
                $where_or_flag = array();
                foreach ($flag_arr as $nk2 => $nv2) {
                    array_push($where_or_flag, "".$v2." = 0");
                }
                if (!empty($where_or_flag)) {
                    $where_flag_str = " (".implode(" OR ", $where_or_flag).") ";
                    array_push($condition, $where_flag_str);
                }
            } 

            $where_str = "";
            if (0 < count($condition)) {
                $where_str = implode(" AND ", $condition);
            }


            // 给排序字段加上表别名
            switch ($orderby) {
                case 'views_desc':
                    $orderby = 'views desc';
                break;

                case 'views_asc':
                    $orderby = 'views desc';
                break;

                case 'time_desc':
                    $orderby = 'datetime desc';
                break;
                case 'time_asc':
                    $orderby = 'datetime desc';
                break;


                case 'id_desc':
                    $orderby = 'id desc';
                break;
                case 'id_asc':
                    $orderby = 'id desc';
                break;

                case 'rand':
                    $orderby = 'rand()';
                break;
                default:
                {
                    if (empty($orderby)) {
                        $orderby = 'sort desc, id desc';
                    } elseif (trim($orderby) != 'rand()') {
                        $orderbyArr = explode(',', $orderby);
                        foreach ($orderbyArr as $key => $val) {
                            $val = trim($val);
                            if (preg_match('/^([a-z]+)\./i', $val) == 0) {
                                $val = ''.$val;
                                $orderbyArr[$key] = $val;
                            }
                        }
                        $orderby = implode(',', $orderbyArr);
                    }
                }
                break;
            }

            $result = db('article')->where($where_str)->order($orderby)->limit($limit)->select();
        
            if(!empty($result) && is_array($result)){
                foreach ($result as $key => $val) {
                    $nav = db('nav')->where("id",$val['nid'])->where("isshow",1)->field("title,entitle,url")->find(); 
                    $cate = db('cate')->where("id",$val['cid'])->where("isshow",1)->field("title,entitle,url")->find(); 

                    /*导航链接*/
                    if (!empty($nav['url'])) {
                        $val['navurl'] = $nav['url'];
                    } else {
                        $val['navurl'] = $nav['entitle'];
                    }

                    $val['navtitle'] = $nav['title'];
                    /*--end*/

                    /*分类链接*/
                     if (!empty($cate['url'])) {
                        $val['cateurl'] = $cate['url'];
                    } else {
                        $val['cateurl'] = $cate['entitle'];
                    }
                    $val['catetitle'] = $cate['title'];
                    /*--end*/
                    $result[$key] = $val;
                }
            }

        }
		//print_r($result);
        return $result;
    }
    



}