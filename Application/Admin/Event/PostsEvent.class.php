<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-5-25
 * Time: 下午8:09
 */

namespace Admin\Event;


class PostsEvent
{

    public function get_tpl_type_list()
    {

        $tpl_static_path = WEB_ROOT . 'Public/' . get_kv('home_theme') . '/';
        if (file_exists($tpl_static_path . 'theme.xml')) {
            $theme = simplexml_load_file($tpl_static_path . '/theme.xml');
            $tpl_type = (object_to_array($theme->post));
            $tpl_type_list = array();
            foreach ($tpl_type as $key => $value) {
                $tpl_type_list[$value['tpl']] = $value['name'];
            }
        } else {
            $tpl_type_list = array(
                "single" => "文章",
                "page" => "页面"
            );
        }

        return $tpl_type_list;

    }


    function restore_from_cookie()
    {

        $post = json_decode(gzuncompress(cookie('post_add')), true);

        foreach ($post['post_tag'] as $key => $value) {
            unset($post['post_tag'][$key]);
            $post['post_tag'][$key]['tag_id'] = $value;
        }
        foreach ($post['post_cat'] as $key => $value) {
            unset($post['post_cat'][$key]);
            $post['post_cat'][$key]['cat_id'] = $value;
        }
        return $post;

    }
} 