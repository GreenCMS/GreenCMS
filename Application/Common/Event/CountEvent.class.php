<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-9
 * Time: 下午11:10
 */

namespace Common\Event;


use Common\Logic\PostsLogic;

class CountEvent
{


    public function getPostCount($info = array())
    {
        $info['post_status'] = "publish";
        $PostsLogic = new PostsLogic();
        $post_count = $PostsLogic->countAll("single", $info);
        return $post_count;
    }


    public function getUserCount()
    {
        $user_count = D('User')->count();
        return $user_count;

    }


} 