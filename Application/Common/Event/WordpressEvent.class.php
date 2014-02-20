<?php
/**
 * Created by Green Studio.
 * File: WordpressEvent.class.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 上午11:23
 */

namespace Common\Event;


class WordpressEvent {

    public function postImport()
    {
        if (!file_exists(WEB_CACHE_PATH . '/wordpress.xml')) exit();


        $wordpress = simplexml_load_file(WEB_CACHE_PATH . '/wordpress.xml');

        $namespaces = $wordpress->getNamespaces(true);
        $wordpress_channel = $wordpress->channel;
        foreach ($namespaces as $key => $value) {
            $wordpress_channel->registerXPathNamespace($key, $value);
        }

        $items = $wordpress_channel->xpath('item');
        foreach ($items as $key => $value) {

            $post_cat_temp = array();
            $post_tag_temp = array();

            $row = (simplexml_load_string($value->asXML()));
            $row->encoded[0] = (simplexml_load_string($row->encoded[0]->asXML(), 'SimpleXMLElement', LIBXML_NOCDATA));

            if ($row->post_type == 'post') {

                $temp = $row->xpath('category');
                $tag_cat = object_to_array($temp);

                foreach ($tag_cat as $key => $value) {
                    if ($value["@attributes"]["domain"] == 'category') {
                        $nicename=D('Cats', 'Logic')->detail($value["@attributes"]["nicename"]);
                        $cat_id = (int)$nicename['cat_id'];
                        array_push($post_cat_temp, $cat_id);
                    } elseif ($value["@attributes"]["domain"] == 'post_tag') {
                        $nicename=D('Tags', 'Logic')->detail($value["@attributes"]["nicename"]);
                        $tag_id = (int)$nicename['tag_id'];
                        array_push($post_tag_temp, $tag_id);
                    } else {
                        echo 'No match';
                    }

                }
//                dump($tag_cat);
            }


            $item = object_to_array($row);
            if ($item['post_type'] == 'post') {

                //dump($item);


                $post_temp = array();
                $post_temp['user_id'] = 1;
                $post_temp['post_content'] = $item['encoded'][0];
                $post_temp['post_id'] = (int)$item['post_id'];
                $post_temp['post_title'] = $item['title'];
                $post_temp['post_status'] = 'publish';
                $post_temp['post_type'] = 'single';
                $post_temp['post_name'] = $item['post_name'];


                $post_id = D('Posts', 'Logic')->data($post_temp)->add();
                echo '插入ID为' . $post_id . "的文章";

                foreach ($post_cat_temp as $cat_id) {

                    echo '插入ID为' . $post_id . "的文章关联CAT ID为:" . $cat_id;

                    D('Post_cat')->data(array('post_id' => $post_id, 'cat_id' => $cat_id))->add();

                }

                foreach ($post_tag_temp as $tag_id) {
                    echo '插入ID为' . $post_id . "的文章关联TAG ID为:" . $tag_id;

                    D('Post_tag')->data(array('post_id' => $post_id, 'tag_id' => $tag_id))->add();

                }

            }


        }


    }

    public function tagImport()
    {
        if (!file_exists(WEB_CACHE_PATH . '/wordpress.xml')) exit();


        $wordpress = simplexml_load_file(WEB_CACHE_PATH . '/wordpress.xml');
        $namespaces = $wordpress->getNamespaces(true);
        $wordpress_channel = $wordpress->channel;
        foreach ($namespaces as $key => $value) {
            $wordpress_channel->registerXPathNamespace($key, $value);
        }

        $category = $wordpress_channel->xpath('wp:tag');
        foreach ($category as $key => $value) {
            $row = (simplexml_load_string($value->asXML()));
            $row->tag_name = (simplexml_load_string($row->tag_name->asXML(), 'SimpleXMLElement', LIBXML_NOCDATA));
            $item = object_to_array($row);
            $tag_temp = array();
            $tag_temp['tag_id'] = $item['term_id'];
            $tag_temp['tag_slug'] = $item['tag_slug'];
            $tag_temp['tag_name'] = $item['tag_name'];
            D('Tags', 'Logic')->data($tag_temp)->add();

            dump($tag_temp);
        }


    }


    public function catImport()
    {
        if (!file_exists(WEB_CACHE_PATH . '/wordpress.xml')) exit();

        $wordpress = simplexml_load_file(WEB_CACHE_PATH . '/wordpress.xml');
        $namespaces = $wordpress->getNamespaces(true);
        $wordpress_channel = $wordpress->channel;
        foreach ($namespaces as $key => $value) {
            $wordpress_channel->registerXPathNamespace($key, $value);
        }

        $category = $wordpress_channel->xpath('wp:category');
        foreach ($category as $key => $value) {
            $row = (simplexml_load_string($value->asXML()));
            $row->cat_name = (simplexml_load_string($row->cat_name->asXML(), 'SimpleXMLElement', LIBXML_NOCDATA));
            $item = object_to_array($row);
            $cat_temp = array();
            $cat_temp['cat_id'] = $item['term_id'];
            $cat_temp['cat_slug'] = $item['category_nicename'];
            $cat_temp['cat_name'] = $item['cat_name'];
            $cat_father=D('Cats', 'Logic')->detail($item['category_parent']) ;
            $cat_temp['cat_father'] = (int)$cat_father['cat_id'];
            D('Cats', 'Logic')->data($cat_temp)->add();
        }
    }

}