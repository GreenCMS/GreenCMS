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

    public function detail($id, $relation = true){
        /* 获取分类信息 */
        $map = array();
        $map['cat_id'] = $id;
        return  D('Cats')->where($map)->relation($relation)->find();
    }

    public function getFather($id = 0){
            $info = $this->detail($id);

            if($info['cat_father']!=0){
                $info['cat_father_detail']=$this->getFather( $info['cat_father']);
            }
            return $info;

    }

    public function getChildren($id = 0){
        if($id){
            $info = $this->getChild($id);
            if(sizeof($info)==1){
                $info[0]['cat_children']=$this->getChild($info[0]['cat_id']);
            }else{
                foreach($info as $key => $value ){
                    $info[$key]['cat_children']=$this->getChildren($value['cat_id']);
                }
            }
            return $info;
        }
        return false;
    }

    public function getChild($id = 0 ){
        if($id){
            $info =  D('Cats')->where(array("cat_father"=>$id))->select();
            if($info!=null) return $info;
        }
        return false;
    }

    public function getPosts($cat_id) {
        $cat = D ( 'Post_cat' )->field ( 'post_id' )->where ( array (
            'cat_id' => $cat_id
        ) )->select ();
        return $cat;
    }

    public function getPostsByCat($cat_id, $num = 5) {
        $cat=$this->getPostIdsByCat($cat_id);
        $posts = D ( 'Posts','Logic' )->getList ( 'single','post_id desc', $num, true,'publish', $cat );

        return $posts;
    }


    public function getPostIdsByCat($cat_id) {
        $cat=$this->getPosts($cat_id);

        foreach ( $cat as $key => $value ) {
            $cat [$key] = $cat [$key] ['post_id'];
        }

        return $cat;
    }


}