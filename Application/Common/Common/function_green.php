<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-11
 * Time: 下午10:45
 */

/**
 * 全新get_url 函数
 * 得到真实的URL 可以替换 U方法
 * @param string $url
 * @param string $vars
 * @param bool $suffix
 * @param bool $domain
 * @param string $group
 * @return mixed|string
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

    if ($URL_MODEL_TEMP == 2 && $group == 'Home/') $url_return = str_replace('/home', '', $url_return);

    return $url_return;

}




/**
 *  同样得到真实的URL 可以替换 U方法
 * @param array $menu_item
 * @param bool $is_home
 * @return mixed|string
 */
function get_url_by_menu($menu_item = array(), $is_home = false)
{

    if ($menu_item['menu_function'] == 'direct') {
        $real_url = $menu_item['menu_url'];
    } elseif ($menu_item['menu_function'] == 'none') {
        $real_url = '#';
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
 * @param $post
 */
function get_post_url($post){

    $home_post_model = get_opinion('home_post_model');
    if ($home_post_model == 'native') {
        $URL = getURL("Post/" . $post['post_type'], array('info' =>  $post['post_id']));

    }


}

