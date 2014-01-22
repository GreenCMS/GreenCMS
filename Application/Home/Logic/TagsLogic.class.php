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

/**
 * Class TagsLogic
 * @package Home\Logic
 */
class TagsLogic extends RelationModel {

    /**
     * @param $tag_id 输入tag_id
     * @return mixed 找到的话返回post_id数组集合
     */
    public function getPostId( $tag_id ) {

        $tag=D ( 'Post_tag' )->field( 'post_id' )->where( array( 'tag_id' => $tag_id ) )->select();

        foreach ( $tag as $key => $value ) {
            $tag[$key] = $tag[$key]['post_id'];
        }

        return $tag;
    }
}