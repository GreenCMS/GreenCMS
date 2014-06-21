<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-20
 * Time: 下午8:48
 */
function hello_world()
{
    dump("组织部自定义函数test");
}


/**
 * 处理文章标题
 * @param $post
 * @return mixed
 */
function process_title($post)
{
    $post_title = $post['post_title'];

    //todo prepare for red color
    $post['post_date'] ;
    time();



    $post_title = mb_substr($post_title, 0, 25, "UTF-8");
    return $post_title;
}
