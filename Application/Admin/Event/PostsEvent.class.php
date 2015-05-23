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
     * 获取当前主题的模板列表
     * @return array
     */
    public function getTplList()
    {

        if (S("post_tpl")) {
            return S("post_tpl");
        }

        $tpl_static_path = WEB_ROOT . 'Public/' . get_kv('home_theme') . '/';
        if (file_exists($tpl_static_path . 'theme.xml')) {
            $theme = simplexml_load_file($tpl_static_path . '/theme.xml');
            $tpl_type = object_to_array($theme->post);
            $tpl_type_list = array_column_5($tpl_type, 'name', 'tpl');
        } else {
            $tpl_type_list = get_opinion('post_tpl');
        }

        S("post_tpl", $tpl_type_list, 600);

        return $tpl_type_list;
    }


    /**
     * 从Cookie中恢复
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

    /**
     * 插入空文章
     * @return mixed
     */
    public function insertEmpty()
    {

        $post_data['post_type'] = 'single';
        $post_data['post_title'] = '未命名';
        $post_data['post_content'] = ' ';
        $post_data['post_template'] = 'single';
        $post_data['post_name'] = 'title';

        $post_data['post_status'] = 'draft';


        $post_data['post_date'] = date("Y-m-d H:m:s", time());
        $post_data['post_modified'] = date("Y-m-d H:m:s", time());

        $post_data['user_id'] = $_SESSION [get_opinion('USER_AUTH_KEY')];

        $post_data['post_tag'] = I('post.tags', array());
        $post_data['post_cat'] = I('post.cats', array());

        $post_id = D('Posts')->relation(true)->add($post_data);

        return $post_id;

    }


}