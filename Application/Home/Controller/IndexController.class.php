<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:40
 */
namespace Home\Controller;

/**
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends HomeBaseController
{

    /**
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 首页
     */
    public function index()
    {

        /**
         * $this->news = D('Cats', 'Logic')->getPostsByCat(16, 7);
        $this->policy = D('Cats', 'Logic')->getPostsByCat(17, 7);
        $this->tech = D('Cats', 'Logic')->getPostsByCat(18, 7);
        $this->college = D('Cats', 'Logic')->getPostsByCat(19, 7);
        $this->school = D('Cats', 'Logic')->getPostsByCat(20, 7);
         */
        $this->news = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->policy = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->tech = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->college = D('Cats', 'Logic')->getPostsByCat(1, 7);
        $this->school = D('Cats', 'Logic')->getPostsByCat(1, 7);

        /**
         *
         */
        $this->display();
    }

    public function test()
    {


    }

    public function tagImport()
    {
        if(!file_exists(WEB_CACHE_PATH . '/wordpress.xml'))exit();


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
            // $cat_temp['cat_father'] = (int)D('Cats', 'Logic')->detail($item['category_parent']) ['cat_id'];
            D('Tags', 'Logic')->data($tag_temp)->add();

            dump($tag_temp);
        }


    }


    public function catImport()
    {
        if(!file_exists(WEB_CACHE_PATH . '/wordpress.xml'))exit();

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
            $cat_temp['cat_father'] = (int)D('Cats', 'Logic')->detail($item['category_parent']) ['cat_id'];
            D('Cats', 'Logic')->data($cat_temp)->add();
        }
    }

}