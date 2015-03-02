<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: RbacBaseController.class.php
 * User: Timothy Zhang
 * Date: 14-2-20
 * Time: 下午5:02
 */

namespace Weixin\Controller;

use Org\Util\Rbac;

/**
 * Class WeixinBaseController
 * @package Weixin\Controller
 */
class WeixinBaseController extends WeixinCoreController
{
    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->_initialize();

        $this->_currentPostion();
        $this->_currentUser();

//        $this->customConfig();

    }

    /**
     *
     */
    protected function _initialize()
    {
        if (!RBAC::AccessDecision('Weixin')) // AccessDecision中间使用分组名
        {
            // 登录检查
            RBAC::checkLogin();
            // 提示错误信息 无权限
            $this->error(L('_VALID_ACCESS_'));

            // TODO 如何防止循环无权限
        }
    }


    /**
     *
     */
    private function _currentPostion()
    {

        //  echo CONTROLLER_NAME;
        //  echo ACTION_NAME;

        $cache = get_opinion('admin_big_menu');
        foreach ($cache as $big_url => $big_name) {
            if (strtolower($big_url) == strtolower(CONTROLLER_NAME)) {
                $module = $big_name;
                $module_url = U("Weixin/" . "$big_url" . '/index');
            } else {
            }
        }

        $cache = get_opinion('admin_sub_menu');
        foreach ($cache as $big_url => $big_name) {
            if (strtolower($big_url) == strtolower(CONTROLLER_NAME)) {
                foreach ($big_name as $sub_url => $sub_name) {
                    $sub_true_url = explode('/', $sub_url);
                    if (!strcasecmp($sub_true_url [1], strtolower(ACTION_NAME))) {
                        $action = $sub_name;
                        $action_url = U("Weixin/" . "$sub_url");
                    }
                }
            }
        }

        $this->assign('module', $module);
        $this->assign('action', $action);
        $this->assign('module_url', $module_url);
        $this->assign('action_url', $action_url);

    }


    /**
     *
     */
    public function saveConfig()
    {
        $post_data = I('post.');
        foreach ($post_data as $name => $value) {
            set_opinion($name, $value);
        }
    }


}