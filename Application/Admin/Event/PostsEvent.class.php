<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-5-25
 * Time: 下午8:09
 */

namespace Admin\Event;


/**
 * Class PostsEvent
 * @package Admin\Event
 */
class PostsEvent
{

    /**
     * @return array
     */
    public function getTplList()
    {

        $tpl_static_path = WEB_ROOT . 'Public/' . get_kv('home_theme') . '/';
        if (file_exists($tpl_static_path . 'theme.xml')) {
            $theme = simplexml_load_file($tpl_static_path . '/theme.xml');
            $tpl_type = object_to_array($theme->post);
            $tpl_type_list = array_column_5($tpl_type, 'name', 'tpl');
        } else {
            $tpl_type_list = C('post_tpl');
        }

        return $tpl_type_list;

    }


    /**
     * @return mixed
     */
    public function restoreFromCookie()
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

    public function insertEmpty()
    {


    }
}