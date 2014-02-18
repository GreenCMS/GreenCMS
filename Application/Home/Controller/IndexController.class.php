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


        foreach ($wordpress_channel->children() as $child) {
            //      dump($child);
            // echo $child->getName() . "\n";
        }


        //  print_array(object_to_array($wordpress_channel));
        // $this->display();
    }


    public function catImport()
    {

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