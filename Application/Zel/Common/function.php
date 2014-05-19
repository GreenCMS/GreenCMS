<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-5-10
 * Time: 上午9:47
 */


function get_next_post2($post_id, $post_cat)
{

    $where ["cat_id"] = $post_cat [0]["cat_id"];
    $where ["post_id"] = array('gt', $post_id);
    $next_post_id = D('Post_cat')->field('post_id')->where($where)->find();
    $post = D('Posts', 'Logic')->detail($next_post_id["post_id"], false);

     if (!$post) return null;

    $res = ' <a href="' . U('Zel/Post/' . $post['post_type'], array('info' => $post['post_id'])) . '">' . is_top($post['post_top']) . $post['post_title'] . '</a>';
    return $res;
}

function get_previous_post2($post_id, $post_cat)
{
    $where ["cat_id"] = $post_cat [0]["cat_id"];
    $where ["post_id"] = array('lt', $post_id);
    $next_post_id = D('Post_cat')->field('post_id')->where($where)->find();
    $post = D('Posts', 'Logic')->detail($next_post_id["post_id"], false);
    if (!$post) return null;

    $res = ' <a href="' . U('Zel/Post/' . $post['post_type'], array('info' => $post['post_id'])) . '">' . is_top($post['post_top']) . $post['post_title'] . '</a>';
    return $res;
}


/**
 * 面包屑
 * @param $type
 * @param string $info
 * @param string $ul_attr
 * @param string $li_attr
 * @param string $separator
 * @param string $init
 * @return string
 */
function get_breadcrumbs2($type, $info = '', $ul_attr = ' class="breadcrumbs "',
                          $li_attr = '', $separator = ' <li>  &gt;&gt; </li>'
    , $init = '首页')
{

    $res = '<ul class="breadcrumbs">
            <li><a href="' . U("/zel") . '">' . $init . '</a></li>
           ';
    if ($type == 'cats') {
        $Cat = D('Cats', 'Logic');
        $cat = $Cat->getFather($info);
        $res .= extra_father2($cat, $separator);
    } elseif ($type == 'tags') {
        $Tag = D('Tags', 'Logic');
        $tag = $Tag->detail($info, false);
        $res .= $separator . '<li><a href="' . U('Zel/Tag/detail', array('info' => $tag['tag_id'])) . '">' . $tag['tag_name'] . '</a></li>';

    } elseif ($type == 'single') {

    } elseif ($type == 'page') {

    } else {
        $res .= $separator . ' <li>' . $type . '</li>';
    }

    $res .= '</ul>';
    return $res;
}


/**
 * 展开父类
 * @param $cat
 * @param $separator
 * @return string
 */
function extra_father2($cat, $separator)
{
    $res = '';
    if ($cat['cat_father_detail'] != '') {
        $res .= extra_father2(($cat['cat_father_detail']), $separator);
    }

    $res .= $separator . '<li><a href="' . U('Zel/Cat/detail', array('info' => $cat['cat_id'])) . '">' . $cat['cat_name'] . '</a></li>';
    return $res;

}



