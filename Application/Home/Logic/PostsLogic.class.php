<?php
/**
 * Created by Green Studio.
 * File: PostsLogic.class.php
 * User: TianShuo
 * Date: 14-1-15
 * Time: 下午11:59
 */

namespace Home\Logic;
use \Think\Model\RelationModel;

class PostsLogic extends RelationModel{



    public function detail($param, $type = 'single', $status = 'publish', $relation = true) {
        $info ['post_id|post_name'] = urlencode ( urldecode ( $param ) );
        $info ['post_type'] = $type;
        $info ['post_status'] = $status;

           //TODO $relation
        $post_res = D('Posts')->where ( $info )->relation ( $relation )->find ();
        return $post_res;
    }


    public function getList( $type = 'single',$order = 'post_id desc', $limit = 20,$relation = true, $post_status='publish',$ids=array()){
        $info ['post_type'] = $type;
        $info ['post_status'] = $post_status;

        if (!empty($ids)) {
            $info['post_id']  = array('in',$ids);
          }

        $post_list =  D('Posts')->where ( $info )->order ( ' post_top desc ,' . $order )->limit ( $limit )->relation ( $relation )->select ();

        return $post_list;
    }

    public function countAll($type = 'single',$post_status= 'publish') {
        $info ['post_status'] = $post_status;
        $info ['post_type'] = $type;

        $count = $this->where ( $info )->count ();

        return $count;
    }

    public function preDel($id) {
        $info ['post_id'] = $id;

        $data= array('post_status'=>'preDel');
        if ($this->where ( $info )->setField($data))
            return true;
        else
            return false;
    }


    public function del($id) {
        $info ['post_id'] = $id;

        if ($this->where ( $info )->relation ( true )->delete ())
            return true;
        else
            return false;
    }


    public function viewInc($id) {
        $info ['post_id'] = $id;

        if ($this->where ( $info )->setInc('post_view_count'))
            return true;
        else
            return false;
    }



}