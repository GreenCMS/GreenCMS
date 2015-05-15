<?php
/**
 * 全新链接函数
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-6-11
 * Time: 下午10:45
 */

/**
 * GreenCMS 全新get_url 函数
 * 得到真实的URL 可以替换 U方法
 * @param string $url "模块名/控制器/操作"
 * @param string $vars 参数 array("参数名"=>'参数值')
 * @param bool $suffix 后缀 .html
 * @param bool $domain 是否显示完整域名
 * @param string $group 缺省分组名 默认是Home/
 * @return string 获得的链接地址
 */
function get_url($url = '', $vars = '', $suffix = true, $domain = false, $group = 'Home/')
{
    $url_arr = preg_split('/\//', $url);

    $URL_MODEL_TEMP = (int)get_opinion('home_url_model');

    if (sizeof($url_arr) == 2) {
        $url_return = U($group . $url, $vars, $suffix, $domain);
    } else {
        $url_return = U($url, $vars, $suffix, $domain);
    }

    if ($URL_MODEL_TEMP == 2 && $group == 'Home/') {
        $url_return = str_replace('/home', '', $url_return);
    }

    return $url_return;

}

/**
 *  从Menu得到真实的URL 可以替换 U方法
 * @param array $menu_item
 * @param bool $is_home
 * @return mixed|string
 */
function get_url_by_menu($menu_item = array(), $is_home = false)
{

    if ($menu_item['menu_function'] == 'direct' || $menu_item['menu_function'] == 'native') {
        $real_url = $menu_item['menu_url'];
    } elseif ($menu_item['menu_function'] == 'none') {
        $real_url = '#';
    } elseif ($menu_item['menu_function'] == 'in_site') {
        $real_url = get_opinion('site_url') . $menu_item['menu_url'];
    } else {

        /**
         * $menu_item 保存post single page 文章链接时，参数使用json格式
         */
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

    return $real_url;
}

/**
 * 新版采用传递post 方式而不是使用by id方式，提高非native模式下性能
 * @param array $post
 * @param string $group 分组
 * @return string 文章链接
 */
function get_post_url($post, $group = '')
{
    $home_post_model = get_opinion('home_post_model');

    if (!is_array($post)) {
        $post = D('Posts', 'Logic')->detail($post, false, array(), true);
    }

    if ($home_post_model == 'native') {
        $URL = get_url($group . "Post/" . $post['post_type'], array('info' => $post['post_id']));

    } else {

        $URL_HTML_SUFFIX = '.' . get_opinion('URL_HTML_SUFFIX');
        C('URL_HTML_SUFFIX', '');

        $url_base = get_url($group . "Post/" . $post['post_type'], array(), false);

        if ($home_post_model === 'post_id') {
            $URL = $url_base . '/' . $post['post_id'];
        } else if ($home_post_model === 'post_name') {
            $URL = $url_base . '/' . $post['post_name'];
        } else if ($home_post_model === 'year/month/post_name') {
            $URL = $url_base . '/' . getTimestamp($post['post_date'], 'year') . '/' . getTimestamp($post['post_date'], 'month') . '/' . $post['post_name'];
        } else if ($home_post_model === 'year/month/post_id') {
            $URL = $url_base . '/' . getTimestamp($post['post_date'], 'year') . '/' . getTimestamp($post['post_date'], 'month') . '/' . $post['post_id'];
        } else if ($home_post_model === 'year/post_id') {
            $URL = $url_base . '/' . getTimestamp($post['post_date'], 'year') . '/' . $post['post_id'];
        } else if ($home_post_model === 'year/post_name') {
            $URL = $url_base . '/' . getTimestamp($post['post_date'], 'year') . '/' . $post['post_name'];
        } else if ($home_post_model === 'year/month/day/post_id') {
            $URL = $url_base . '/' . getTimestamp($post['post_date'], 'year') . '/' . getTimestamp($post['post_date'], 'month') . '/' . getTimestamp($post['post_date'], 'day') . '/' . $post['post_id'];
        } else if ($home_post_model === 'year/month/day/post_name') {
            $URL = $url_base . '/' . getTimestamp($post['post_date'], 'year') . '/' . getTimestamp($post['post_date'], 'month') . '/' . getTimestamp($post['post_date'], 'day') . '/' . $post['post_name'];
        } elseif ($home_post_model == 'absolute') {
            $URL = $post['post_url'];
            if ($URL == '') {
                $URL = get_url($group . "Post/" . $post['post_type'], array('info' => $post['post_id']));
            }
        } else {
            $URL = $url_base . '/' . $post['post_id'];
        }
        $URL = $URL . $URL_HTML_SUFFIX;

    }

    return $URL;
}

/**
 * 获取标签链接
 * @param string | array $tag id或者数组
 * @param string $group 分组
 * @return string
 */
function get_tag_url($tag, $group = '')
{

    $home_tag_model = get_opinion('home_tag_model');
    $Tags = D('Tags', 'Logic');

    if (!is_array($tag)) {

        if ($home_tag_model == 'native') {
            $tmp['tag_id'] = $tag;
            $tag = $tmp;

        } else {
            $tag = $Tags->cache(true)->detail($tag);
        }

    }

    if ($home_tag_model === 'native') {
        $URL = get_url($group . 'Tag/detail', array("info" => $tag['tag_id']));
    } else {
        $URL_HTML_SUFFIX = '.' . get_opinion('URL_HTML_SUFFIX');
        C('URL_HTML_SUFFIX', '');

        if ($home_tag_model == 'ID') {
            $URL = get_url($group . '/Tag') . '/' . $tag['tag_id'];
        } else if ($home_tag_model === 'slug') {
            $tag = $Tags->detail($tag['tag_id']);
            $URL = get_url($group . '/Tag') . '/' . $tag['tag_slug'];
        } else {
            $URL = get_url($group . 'Tag/detail', array("info" => $tag['tag_id']));
        }
        $URL = str_replace('//', '/', $URL) . $URL_HTML_SUFFIX;

    }

    return $URL;

}

/**
 * 获取分类链接
 * @param string | array $cat id或者数组
 * @param string $group 分组
 * @return string 链接
 */
function get_cat_url($cat, $group = '')
{
    $home_cat_model = get_opinion('home_cat_model');
    $Cats = D('Cats', 'Logic');

    if (!is_array($cat)) {
        if ($home_cat_model == 'native') {
            $tmp['cat_id'] = $cat;
            $cat = $tmp;
        } else {
            $cat = $Cats->cache(true)->detail($cat);
        }

    }

    if ($home_cat_model == 'native') {
        $URL = get_url($group . 'Cat/detail', array("info" => $cat['cat_id']));
    } else {

        $URL_HTML_SUFFIX = '.' . get_opinion('URL_HTML_SUFFIX');
        C('URL_HTML_SUFFIX', '');

        if ($home_cat_model == 'ID') {
            $URL = get_url($group . '/Cat') . '/' . $cat['cat_id'];
        } else if ($home_cat_model == 'slug') {
            $URL = get_url($group . '/Cat') . '/' . $cat['cat_slug'];
        } else {
            $URL = get_url($group . 'Cat/detail', array("info" => $cat['cat_id']));
        }

        $URL = str_replace('//', '/', $URL) . $URL_HTML_SUFFIX;

    }
    return $URL;
}

/**
 * 获取频道链接
 * @param $cat
 * @param string $group
 * @return mixed|string
 */
function get_channel_url($cat, $group = '')
{

    if (is_array($cat)) {
        $cat = $cat['cat_id'];
    }

    $URL = get_url($group . 'Cat/channel', array("info" => $cat));

    return $URL;

}


function get_deep_url($cat, $group = '')
{

    if (is_array($cat)) {
        $cat = $cat['cat_id'];
    }

    $URL = get_url($group . 'Cat/deep', array("info" => $cat));

    return $URL;

}


/**
 * 获取插件链接
 * @param $url
 * @param array $param
 * @param string $group
 * @internal param $cat
 * @return mixed|string
 */
function get_addon_url($url, $param = array(), $group = 'Home')
{

    $URL_HTML_SUFFIX = get_opinion('URL_HTML_SUFFIX');
    C('URL_HTML_SUFFIX', '');

    $url_arr = preg_split('/\//', $url);

    $param['action'] = $url_arr[2];

    $url = U($group . '/' . $url_arr[0] . '/' . $url_arr[1], $param);

    C('URL_HTML_SUFFIX', $URL_HTML_SUFFIX);

    return $url;
}


/**
 * 获取带时间链接的 年月日
 * @param $Timestamp
 * @param string $type
 * @return string
 */
function get_time_url($Timestamp, $type = 'single')
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
