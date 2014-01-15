<?php
/**
 * Created by Green Studio.
 * File: CatsLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:34
 */

namespace Home\Logic;
use \Think\Model\RelationModel;

class CatsLogic extends RelationModel {


    public function detail($id, $field = true){
        /* 获取分类信息 */
        $map = array();
        if(is_numeric($id)){ //通过ID查询
            $map['id'] = $id;
        } else { //通过标识查询
            $map['name'] = $id;
        }
        return $this->field($field)->where($map)->find();
    }


    public function getTree($id = 0, $field = true){
        /* 获取当前分类信息 */
        if($id){
            $info = $this->detail($id);
            $id   = $info['id'];
        }

        /* 获取所有分类 */
        $map  = array('status' => 1);
        $list = $this->field($field)->where($map)->order('sort')->select();

        //TODO 今天就写到这里
        $list = list_to_tree($list, $pk = 'cat_id', $pid = 'pid', $child = '_', $root = $id);

        /* 获取返回数据 */
        if(isset($info)){ //指定分类则返回当前分类极其子分类
            $info['_'] = $list;
        } else { //否则返回所有分类
            $info = $list;
        }

        return $info;
    }


}