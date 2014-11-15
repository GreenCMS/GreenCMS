<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-20
 * Time: 下午8:48
 */
function hello_world()
{
    dump("组织部自定义函数test");
}


/**
 * 处理文章标题
 * // <span class="red"> </span>
 * @param $post
 * @param int $length
 * @return mixed
 */
function process_title($post,$length=25)
{
    $post_title = $post['post_title'];
    $post['post_date'] ;
        $post_title = mb_substr($post_title, 0, $length, "UTF-8");
    if((time()-strtotime($post['post_date']))<3600*24*3){
        $post_title='<span class="red">[新]&nbsp;'.$post_title.'</span>';
    }else{
        $post_title='&nbsp;'.$post_title;
    }

    return $post_title;
}
