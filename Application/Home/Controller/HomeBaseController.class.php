<?php
/**
 * Created by Green Studio.
 * File: HomeBaseController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:48
 */

namespace Home\Controller;
use Common\Controller\BaseController;
use Think\Hook;

/**
 * Class HomeBaseController
 * @package Home\Controller
 */
abstract class HomeBaseController extends BaseController
{

    /**
     * 构造
     */
    function __construct()
    {
        parent::__construct();

        $this->customConfig();

    }



    /**
     * @function 是否为空
     *
     * @param $info
     * @param string $message
     */
    public function if404($info, $message = "")
    {
        Hook::listen('home_if404');

        if (empty($info)) $this->error404($message);
    }


    /**
     * @function 404 ERROR
     *
     * @param string $message
     */
    public function error404($message = "非常抱歉，你需要的页面暂时不存在，可能它已经躲起来了。.")
    {
        Hook::listen('home_error404');

        $this->assign("message", $message);
        $this->display('Index/404');
        Hook::listen('app_end');
        die();
    }


}