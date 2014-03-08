<?php

function getRealURL($menu_item = array(), $is_home = false)
{

     if ($menu_item['menu_function'] == 'direct') {
        $real_url = $menu_item['menu_url'];
    } elseif ($menu_item['menu_function'] == 'none') {
        $real_url = '#';
    } else {

        if (json_decode($menu_item['menu_url']) != null) {
            $url = json_decode($menu_item['menu_url']);
        } else {
            $url = $menu_item['menu_url'];
        }

        if (is_array($url)) {
            $real_url = call_user_func_array($menu_item['menu_function'], $url);
        } else {

            $real_url = call_user_func($menu_item['menu_function'], $url);
        }
    }

    //if($is_home)
    // $real_url=str_replace('m=admin','m=home',$real_url);
    return $real_url;
}

// 路由动态获取url
function getSingleURLByID($ID, $type = 'single')
{
    $Posts = D('Posts');

    $url_base = get_url("Post/" . $type);

    $home_post_model = get_kv('home_post_model');

    if ($home_post_model === 'native') {
        $URL = get_url("Post/" . $type, array('info' => $ID));

    } else {

        C('URL_HTML_SUFFIX', '');
        if ($home_post_model === 'post_id') {
            $URL = $url_base . '/' . $ID;
        } else if ($home_post_model === 'post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . $posts ['post_name'];
        } else if ($home_post_model === 'year/month/post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $posts ['post_name'];
        } else if ($home_post_model === 'year/month/post_id') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $ID;
        } else if ($home_post_model === 'year/post_id') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $ID;
        } else if ($home_post_model === 'year/post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $posts ['post_name'];
        } else if ($home_post_model === 'year/month/day/post_id') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $ID;
        } else if ($home_post_model === 'year/month/day/post_name') {
            $posts = $Posts->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $posts ['post_name'];
        } else {
            $URL = $url_base . '/' . $ID;
        }
    }
    return $URL;
}

// 路由动态获取url
function getTagURLByID($ID)
{
    $home_tag_model = get_kv('home_tag_model');

    $Tags = D('Tags', 'Logic');
    if ($home_tag_model === 'native') {
        $URL = get_url('Tag/detail', array("info" => $ID));
    } elseif ($home_tag_model === 'ID') {
        $URL = get_url('Tag') . '/' . $ID;
    } else if ($home_tag_model === 'slug') {
        $tag = $Tags->detail($ID);
        $URL = get_url('Tag') . '/' . $tag ['tag_slug'];
    } else {
        $URL = get_url('Tag') . '/' . $ID;
    }

    return $URL;
}

function getCatURLByID($ID)
{
    $home_cat_model = get_kv('home_cat_model');

    $Tags = D('Cats', 'Logic');
    if ($home_cat_model == 'native') {
        $URL = get_url('Cat/detail', array("info" => $ID));
    } elseif ($home_cat_model == 'ID') {
        $URL = get_url('Cat') . '/' . $ID;
    } else if ($home_cat_model == 'slug') {
        $cat = $Tags->detail($ID);
        $URL = get_url('Cat') . '/' . $cat ['cat_slug'];
    } else {
        $URL = get_url('Cat') . '/' . $ID;
    }


    return $URL;
}


