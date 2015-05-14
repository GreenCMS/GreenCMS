<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-5-20
 * Time: 下午8:00
 * 一切为了学生,为了一切学生,为了学生一切
 */
function hello_world()
{
    dump("自定义函数test");
}


function get_post_icon($post)
{

    $now_date = strtotime(date("y-m-d h:i:s"));
    $post_date = strtotime( $post['post_date']);

    $days=ceil(($now_date-$post_date)/86400);

    if($days>3){
        return "<i> · </i>&nbsp;";
    }else{
        return  '<img src="' . get_opinion('site_url') . '/Public/Xsc/images/new.png"  style="width:14px;height:14px;float:left; padding-top:5px;">&nbsp;';
    }

}


function get_active($current_id ,$class_id)
{
    dump("自定义函数test");
}



