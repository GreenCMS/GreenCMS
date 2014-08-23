<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-8-23
 * Time: 上午11:30
 */

namespace Admin\Controller;


class EmptyController extends AdminBaseController {


    /**
     * 空控制器实现
     * @param $method
     * @param $args
     * @return mixed|void
     * @internal param $null
     */
    public function __call($method, $args) {

        $pluginRes = $this->anonymousPlugin(CONTROLLER_NAME, ACTION_NAME, I('get.action'));

        if ($pluginRes) {
        } else {
            $this->show("illegal request");
        }

    }


} 