<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:40
 */
namespace Home\Controller;
use Think\Hook;

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

        Hook::listen('home_index');

        $this->display('index');
    }


    public function _empty($method, $args)
    {
        Hook::listen('home_index_empty');


    }

//    function wordpressImport()
//    {
//        $Wordpress = new \Common\Event\WordpressEvent();
//        $Wordpress->catImport(WEB_CACHE_PATH . '/wordpress.xml');
//        $Wordpress->tagImport(WEB_CACHE_PATH . '/wordpress.xml');
//        $Wordpress->postImport(WEB_CACHE_PATH . '/wordpress.xml');
//
//    }

}