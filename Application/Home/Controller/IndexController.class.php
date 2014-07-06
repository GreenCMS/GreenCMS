<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:40
 */
namespace Home\Controller;
use Common\Util\File;
use Think\Hook;

/**
 * 首页控制器
 * Class IndexController
 * @package Home\Controller
 */
class IndexController extends HomeBaseController
{

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 显示首页
     */
    public function index()
    {
        $this->display('index');
    }


    /**
     * 显示首页为空时
     * @param $method
     * @param $args
     */
    public function _empty($method, $args)
    {
        Hook::listen('home_index_empty');


    }

    /**
     * 测试使用
     */
    function test()
    {

//        include(Upgrade_PATH . 'init.php');
//
//        upgrade_20140620_to_20140625();
    }

}