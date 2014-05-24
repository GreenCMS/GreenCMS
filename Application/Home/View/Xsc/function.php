<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-5-20
 * Time: 下午8:00
 */
function hello_world()
{
    dump("自定义函数test");
}


//缩略post_img
/**
 * 缩略图获取
 * @param $post
 * @return string
 */
function get_post_img($post)
{

    if (!empty($post['post_img'])) {
        return $post['post_img'];
    } else {
        $content = $post['post_content'];
        preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
        $n = count($strResult[1]);
        $random = mt_rand(1, 20);
        if ($n > 0) {
            return $strResult[1][0];
        } else {
            return get_opinion('site_url') . '/Public/share/img/random/tb' . $random . '.jpg';

        }
    }
}
