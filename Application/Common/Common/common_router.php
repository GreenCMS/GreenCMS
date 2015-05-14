<?php

/**
 * @param $Timestamp
 * @param string $need
 *
 * @return mixed
 */
function getTimestamp($Timestamp, $need = 'timestamp')
{
    $array = explode("-", $Timestamp);
    $year = $array [0];
    $month = $array [1];

    $array = explode(":", $array [2]);
    $minute = $array [1];
    $second = $array [2];

    $array = explode(" ", $array [0]);
    $day = $array [0];
    $hour = $array [1];

    $timestamp = mktime($hour, $minute, $second, $month, $day, $year);

    if ($need === 'hour') {
        return $hour;
    } else if ($need === 'minute') {
        return $minute;
    } else if ($need === 'second') {
        return $second;
    } else if ($need === 'month') {
        return $month;
    } else if ($need === 'day') {
        return $day;
    } else if ($need === 'year') {
        return $year;
    } else {
        return date($need, $timestamp);
    }

}

/**
 * 获取带时间链接的 年月日
 * @param $Timestamp
 * @param string $type
 * @return string
 */
function getTimeURL($Timestamp, $type = 'single')
{
    $array = explode("-", $Timestamp);
    $year = $array [0];
    $month = $array [1];

    $array = explode(":", $array [2]);
    $minute = $array [1];
    $second = $array [2];

    $array = explode(" ", $array [0]);
    $day = $array [0];
    $hour = $array [1];

    $url = '';
    $url .= '<a href="' . getURL('Archive/' . $type, array('year' => $year)) . '">' . $year . '</a>';
    $url .= '-<a href="' . getURL('Archive/' . $type, array('year' => $year, 'month' => $month)) . '">' . $month . '</a>';
    $url .= '-<a href="' . getURL('Archive/' . $type, array('year' => $year, 'month' => $month, 'day' => $day)) . '">' . $day . '</a>';

    return $url;

}


/**
 * 得到真实的URL 可以替换 U方法
 * @param string $url
 * @param string $vars
 * @return mixed|string
 */
function getURL($url = '', $vars = '')
{
    $url_arr = preg_split('/\//', $url);

    $URL_MODEL_TEMP = (int)get_opinion('home_url_model');

    if (sizeof($url_arr) == 2) {
        $url_return = U('Home/' . $url, $vars, $suffix = true, $domain = false);
    } else {
        $url_return = U($url, $vars, $suffix = true, $domain = false);
    }

    if ($URL_MODEL_TEMP == 2) $url_return = str_replace('/home', '', $url_return);


    return $url_return;

}


/**
 *  同样得到真实的URL 可以替换 U方法
 * @param array $menu_item
 * @param bool $is_home
 * @return mixed|string
 */
function getRealURL($menu_item = array(), $is_home = false)
{
    if ($menu_item['menu_function'] == 'direct' || $menu_item['menu_function'] == 'native') {
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


/**
 * 动态获取文章url
 * @param $ID
 * @param string $type
 * @return mixed|string
 */
function getSingleURLByID($ID, $type = 'single')
{
    $Posts = D('Posts', 'Logic');
    $home_post_model = get_opinion('home_post_model');

    if ($home_post_model == 'native') {
        $URL = getURL("Post/" . $type, array('info' => $ID));

    } else {
        $URL_HTML_SUFFIX = '.' . get_opinion('URL_HTML_SUFFIX');
        C('URL_HTML_SUFFIX', '');

        $url_base = getURL("Post/" . $type);

        if ($home_post_model === 'post_id') {
            $URL = $url_base . '/' . $ID;
        } else if ($home_post_model === 'post_name') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . $posts ['post_name'];
        } else if ($home_post_model === 'year/month/post_name') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $posts ['post_name'];
        } else if ($home_post_model === 'year/month/post_id') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . $ID;
        } else if ($home_post_model === 'year/post_id') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $ID;
        } else if ($home_post_model === 'year/post_name') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . $posts ['post_name'];
        } else if ($home_post_model === 'year/month/day/post_id') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $ID;
        } else if ($home_post_model === 'year/month/day/post_name') {
            $posts = $Posts->cache(true)->detail($ID);
            $URL = $url_base . '/' . getTimestamp($posts ['post_date'], 'year') . '/' . getTimestamp($posts ['post_date'], 'month') . '/' . getTimestamp($posts ['post_date'], 'day') . '/' . $posts ['post_name'];
        } elseif ($home_post_model == 'absolute') {
            $post = $Posts->cache(true)->detail($ID);
            $URL = $post['post_url'];
            if ($URL == '') {
                $URL = get_url("Post/" . $post['post_type'], array('info' => $post['post_id']));
            }
        } else {
            $URL = $url_base . '/' . $ID;
        }
        $URL = $URL . $URL_HTML_SUFFIX;
    }
    return $URL;
}

/**
 * 动态获取页面url
 * @param $ID
 * @param string $type
 */
function getPageURLByID($ID, $type = 'page')
{
    getSingleURLByID($ID, $type);

}

/**
 * 动态获取标签url
 * @param $ID
 * @return mixed|string
 */
function getTagURLByID($ID)
{
    $home_tag_model = get_opinion('home_tag_model');

    $Tags = D('Tags', 'Logic');
    if ($home_tag_model === 'native') {
        $URL = getURL('Tag/detail', array("info" => $ID));
    } else {
        $URL_HTML_SUFFIX = '.' . get_opinion('URL_HTML_SUFFIX');
        C('URL_HTML_SUFFIX', '');


        if ($home_tag_model === 'ID') {
            $URL = getURL('/Tag') . '/' . $ID;
        } else if ($home_tag_model === 'slug') {
            $tag = $Tags->detail($ID);
            $URL = getURL('/Tag') . '/' . $tag ['tag_slug'];
        } else {
            $URL = getURL('Tag/detail', array("info" => $ID));
        }
        $URL = str_replace('//', '/', $URL) . $URL_HTML_SUFFIX;

    }

    return $URL;
}

/**
 * 动态获取分类url
 * @param $ID
 * @return mixed|string
 */
function getCatURLByID($ID)
{
    $home_cat_model = get_opinion('home_cat_model');

    $Tags = D('Cats', 'Logic');
    if ($home_cat_model == 'native') {
        $URL = getURL('Cat/detail', array("info" => $ID));
    } else {

        $URL_HTML_SUFFIX = '.' . get_opinion('URL_HTML_SUFFIX');
        C('URL_HTML_SUFFIX', '');


        if ($home_cat_model == 'ID') {
            $URL = getURL('/Cat') . '/' . $ID;
        } else if ($home_cat_model == 'slug') {
            $cat = $Tags->detail($ID);
            $URL = getURL('/Cat') . '/' . $cat ['cat_slug'];
        } else {
            $URL = getURL('Cat/detail', array("info" => $ID));
        }


        $URL = str_replace('//', '/', $URL) . $URL_HTML_SUFFIX;

    }


    return $URL;
}


/**
 * 动态获取分类url
 * @param $ID
 * @return mixed|string
 */
function getChannelURLByID($ID)
{
    //$home_cat_model = get_opinion('home_cat_model');

    $URL = getURL('Cat/channel', array("info" => $ID));


    return $URL;
}

