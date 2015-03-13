<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: EmptyController.php
 * User: Timothy Zhang
 * Date: 14-4-6
 * Time: 下午8:43
 */
/**
 * Created by GreenStudio GCS Dev Team.
 * File: EmptyController.php
 * User: Timothy Zhang
 * Date: 14-4-6
 * Time: 下午8:43
 */

namespace Home\Controller;

/**
 * 空控制器当访问出错时调用
 * Class EmptyController
 * @package Home\Controller
 */

class EmptyController extends HomeBaseController
{
    /**
     * 空控制器实现
     * @param $method
     * @param $args
     * @return mixed|void
     * @internal param $null
     */
    public function __call($method, $args)
    {

        $pluginRes = $this->anonymousPlugin(CONTROLLER_NAME, ACTION_NAME, I('get.action'));

        if ($pluginRes) {
        } else {
            $this->error404();
        }

    }


}