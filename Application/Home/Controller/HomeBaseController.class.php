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
use Common\Logic\LinksLogic;
use Common\Logic\PostsLogic;

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

        $lockFile = WEB_ROOT . 'Data/Install/install.lock';
        if (!file_exists($lockFile)) {
            $this->redirect('Install/Index/index');
        }


        $this->customConfig();
        $this->loadTheme();

    }


    public function loadTheme()
    {
        $theme_name = get_kv('home_theme');
         if ($theme_name != '')
            $this->theme($theme_name);
    }

    /**
     * @function 是否为空
     *
     * @param $info
     * @param string $message
     */
    public function if404($info, $message = "")
    {
        if (empty($info)) $this->error404($message);
    }


    /**
     * @function 404 ERROR
     *
     * @param string $message
     */
    public function error404($message = "非常抱歉，你需要的页面暂时不存在，可能它已经躲起来了。.")
    {
        $this->assign("message", $message);
        $this->display('Index/404');
        die();
    }


}