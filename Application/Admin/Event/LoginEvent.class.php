<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-2
 * Time: 下午4:20
 */

namespace Admin\Event;

use Common\Controller\BaseController;
use Org\Util\Rbac;


class LoginEvent extends BaseController
{

    public function auth($map)
    {
        $authInfo = RBAC::authenticate($map);
        if (false === $authInfo || $authInfo == null) {
            $log['log_user_id'] = -1;
            $log['log_user_name'] = I('post.username');
            $log['log_password'] = I('post.password');
            $log['log_ip'] = get_client_ip();
            $log['log_status'] = -1;

            D('login_log')->data($log)->add();

            $this->error('用户名或者密码错误！');
        } else {
//            if ($authInfo['user_pass'] != encrypt(I('post.password'))) {
//                $log['log_user_id'] = $authInfo['user_id'];
//                $log['log_user_name'] = I('post.username');
//                $log['log_password'] = I('post.password');
//                $log['log_ip'] = get_client_ip();
//                $log['log_status'] = 0;
//
//                D('login_log')->data($log)->add();
//
//                $this->error('密码错误或者帐号已禁用');
//            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['user_id'];
            if ($authInfo['user_login'] == get_opinion('Admin')) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }

            //记住我
            if (I('post.remember') == 1) {
                if ($authInfo['user_session'] != '') {
                    cookie('user_session', $authInfo['user_session'], 3600000);
                } else if ($authInfo['user_session'] == '') {
                    $user_session = D('User', 'Logic')->genHash($authInfo);
                    cookie('user_session', $user_session, 3600000);
                }
            }
            // 缓存访问权限
            RBAC::saveAccessList();

            $log['log_user_id'] = $authInfo['user_id'];
            $log['log_user_name'] = I('post.username');
            $log['log_password'] = encrypt(I('post.password'));
            $log['log_ip'] = get_client_ip();
            $log['log_status'] = 1;

            D('login_log')->data($log)->add();
            // die(D('login_log')->getlastsql());
            $this->success('登录成功！', U("Admin/Index/index"), false);
        };

    }
}