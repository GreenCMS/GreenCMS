<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: HomeBaseController.class.php
 * User: Timothy Zhang
 * Date: 14-1-11
 * Time: 下午1:48
 */

namespace Home\Controller;

use Common\Controller\BaseController;
use Think\Hook;
use Common\Util\File;

/**
 * Home模块基础类控制器
 * Class HomeBaseController
 * @package Home\Controller
 */
abstract class HomeBaseController extends BaseController
{

    /**
     * Home模块基础类控制器构造
     */
    function __construct()
    {
        parent::__construct();

//        $this->customConfig();
        $this->themeConfig();


    }


    /**
     * 判断变量是否为空为空输出404页
     * @function 是否为空
     *
     * @param $info
     * @param string $message
     */
    public function if404($info, $message = "")
    {
        if (empty($info))
            $this->error404($message);
    }


    /**
     * 显示404页
     * @function 404 ERROR 需要显示错误的信息
     *
     * @param string $message
     */
    public function error404($message = "非常抱歉，你需要的页面暂时不存在，可能它已经躲起来了。.")
    {
        $this->assign("message", $message);

        if (File::file_exists(T('Home@Index/404'))) {
            $this->display('Index/404');
        } else {
            $this->show($message);
        }

        Hook::listen('app_end');
        die();
    }


}