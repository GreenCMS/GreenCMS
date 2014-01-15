<?php
/**
 * Created by Green Studio.
 * File: TagsLogic.class.php
 * User: TianShuo
 * Date: 14-1-16
 * Time: 上午12:37
 */

namespace Home\Logic;
use \Think\Model\RelationModel;

class TagsLogic extends RelationModel{

    public function getPostId($tag_id) {

        $tag=D ( 'Post_tag' )->field('post_id')->where(array('tag_id'=>$tag_id))->select();

        foreach ($tag as $key => $value) {
            $tag[$key]=$tag[$key]['post_id'];
        }

        return $tag;
    }
}