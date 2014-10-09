<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-10-9
 * Time: 下午11:10
 */

namespace Common\Event;


use Common\Logic\PostsLogic;

class CountEvent
{


    public function getPostCount()
    {

        $PostsLogic = new PostsLogic();
        $post_count = $PostsLogic->countAll("single", array("post_status" => "publish"));

        return $post_count;

    }


    public function getUserCount()
    {
        $user_count = D('User')->count();
        return $user_count;

    }


} 