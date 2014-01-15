<?php
/**
 * Created by Green Studio.
 * File: LinksLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:29
 */

namespace Home\Logic;
use \Think\Model;

class LinksLogic extends Model {

    public function addLink($data) {
        $Links = D("Links"); // 实例化User对象

        if ($Links->data($data)->add()) {
            return  true;
        }else {
            return  false;
        }
    }

    public function getList($limit=10, $tag=1 ,$order= 'link_sort desc ,link_id asc') {

        $link_list = D( 'Links' )->where($tag)->order ($order )->limit ( $limit )->select ();

        return $link_list;
    }


    public function del($id) {

        if (D( 'Links' )->where(array('link_id'=>$id))->delete()) {
            return  true;
        }else {
            return  false;
        }

    }

    public function detail($id) {

        $link_list = D( 'Links' )->where(array('link_id'=>$id))->find();

        return $link_list;
    }


}