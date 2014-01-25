<?php
/**
 * Created by Green Studio.
 * File: AdminBaseController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:39
 */

namespace Admin\Controller;

use Org\Util\Rbac;
use Think\Controller;

class AdminBaseController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->_initialize();
    }

    protected function _initialize()
    {
        if (!RBAC::AccessDecision('Admin')) // AccessDecision中间使用分组名
        {
            // 登录检查
            RBAC::checkLogin();
            // 提示错误信息 无权限
            $this->error(L('_VALID_ACCESS_'));

            // TODO 如何防止循环无权限
        }

    }
}