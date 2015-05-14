<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: WordpressEvent.class.php
 * User: Timothy Zhang
 * Date: 14-2-20
 * Time: 上午11:23
 */

namespace Common\Event;

use Common\Util\File;
use SimpleXMLElement;
use Think\Log;


/**
 * Wordpress导入工具 Beta
 * Class WordpressEvent
 * @package Common\Event
 */
class WordpressEvent
{

    /**
     * @var null
     */
    private $file;

    /**
     * @param null $file
     */
    function __construct($file = null)
    {
        $this->file = $file;
    }


    /**
     * @param $filename
     */
    public function postImport($filename)
    {

        if (!file_exists($filename)) exit();


        $file_content = File::readFile($filename);
        $wordpress_xml = new \SimpleXMLElement($file_content);

        // $wordpress_xml = simplexml_load_file($filename);

        $namespaces = $wordpress_xml->getNamespaces(true);
        $wordpress_channel = $wordpress_xml->channel;
        foreach ($namespaces as $key => $value) {
            $wordpress_channel->registerXPathNamespace($key, $value);
        }


        $items = $wordpress_channel->xpath('item');


        foreach ($items as $key => $value) {
            $post_cat_temp = array();
            $post_tag_temp = array();


            $value_wp = $value->children('wp', true);

            $value_wp = object_to_array($value_wp);

            if ($value_wp["post_type"] == 'post') {
                $post_content = $value->children('content', true);
                $post_content = (simplexml_load_string($post_content->asXML(), 'SimpleXMLElement', LIBXML_NOCDATA));


                $value = object_to_array($value);
                //   dump($value);
                //   dump($value_wp);


                $post_temp = array();
                $post_temp['user_id'] = 1;
                $post_temp['post_content'] = $post_content->__toString();
                $post_temp['post_id'] = (int)$value_wp['post_id'];
                $post_temp['post_title'] = $value['title'];
                $post_temp['post_status'] = 'publish';
                $post_temp['post_date'] = $value_wp['post_date'];
                $post_temp['post_modified'] = $value_wp['post_date'];
                $post_temp['post_type'] = 'single';
                $post_temp['post_name'] = $value_wp['post_name'];


                $tag_cat = $value['category'];
                foreach ($tag_cat as $key => $value) {
                    if ($value["@attributes"]["domain"] == 'category') {
                        $nicename = D('Cats', 'Logic')->detail($value["@attributes"]["nicename"]);
                        $cat_id = (int)$nicename['cat_id'];
                        array_push($post_cat_temp, $cat_id);
                    } elseif ($value["@attributes"]["domain"] == 'post_tag') {
                        $nicename = D('Tags', 'Logic')->detail($value["@attributes"]["nicename"]);
                        $tag_id = (int)$nicename['tag_id'];
                        array_push($post_tag_temp, $tag_id);
                    } else {
                        Log::record('No match ');
                    }

                }

                $post_id = D('Posts', 'Logic')->data($post_temp)->add();

                Log::record('插入ID为' . $post_id . "的文章");

                foreach ($post_cat_temp as $cat_id) {

                    Log::record('插入ID为' . $post_id . "的文章关联CAT ID为:" . $cat_id);

                    D('Post_cat')->data(array('post_id' => $post_id, 'cat_id' => $cat_id))->add();

                }

                foreach ($post_tag_temp as $tag_id) {
                    Log::record('插入ID为' . $post_id . "的文章关联TAG ID为:" . $tag_id);

                    D('Post_tag')->data(array('post_id' => $post_id, 'tag_id' => $tag_id))->add();

                }


            }


        }


    }

    /**
     * @param $filename
     */
    public function tagImport($filename)
    {
        if (!file_exists($filename)) exit();

        $file_content = File::readFile($filename);

        $wordpress_xml = new \SimpleXMLElement($file_content);
        $wordpress_channel = $wordpress_xml->channel->children('wp', true);

        foreach ($wordpress_channel as $key => $value) {

            if ($value->tag_slug != '') {
                $value->tag_name = (simplexml_load_string($value->tag_name->asXML(), 'SimpleXMLElement', LIBXML_NOCDATA));

                $item = object_to_array($value);
                $tag_temp = array();
                $tag_temp['tag_id'] = $item['term_id'];
                $tag_temp['tag_slug'] = $item['tag_slug'];
                $tag_temp['tag_name'] = $item['tag_name'];
                // dump($tag_temp);
                D('Tags', 'Logic')->data($tag_temp)->add();

            }


        }


    }


    /**
     * @param $filename
     */
    public function catImport($filename)
    {
        if (!file_exists($filename)) exit();

        $file_content = File::readFile($filename);

        $wordpress_xml = new \SimpleXMLElement($file_content);
        $wordpress_channel = $wordpress_xml->channel->children('wp', true);


        foreach ($wordpress_channel as $key => $value) {

            if ($value->category_nicename != '') {
                $value->cat_name = (simplexml_load_string($value->cat_name->asXML(), 'SimpleXMLElement', LIBXML_NOCDATA));
                $item = object_to_array($value);

                $cat_temp = array();
                $cat_temp['cat_id'] = $item['term_id'];
                $cat_temp['cat_slug'] = $item['category_nicename'];
                $cat_temp['cat_name'] = $item['cat_name'];
                $cat_father = D('Cats', 'Logic')->detail($item['category_parent']);
                $cat_temp['cat_father'] = (int)$cat_father['cat_id'];

                D('Cats', 'Logic')->data($cat_temp)->add();

            }


        }
    }

}