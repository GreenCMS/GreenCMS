<?php
/**
 * Created by Green Studio.
 * File: LoginController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:39
 */

namespace Admin\Controller;

use Common\Util\IpLocation;
use Org\Util\Rbac;
use Think\Controller;

class LoginController extends Controller
{
    public function _before_index()
    {
        $user_session = cookie('user_session');
        if (!empty($user_session)) {
            $authInfo = D('User', 'Logic')->where(array('user_session' => $user_session))->find();

            if (!empty($authInfo)) {
                $_SESSION[C('USER_AUTH_KEY')] = $authInfo['user_id'];

                if ($authInfo ['user_login'] == get_opinion('ADMIN')) {
                    $_SESSION [C('ADMIN_AUTH_KEY')] = true;
                }

                $this->redirect('Admin/Index/index');
            }


        }
    }

    public function index()
    {
        $this->display();
    }

    public function login()
    {
        $ipLocation = new IpLocation();
        $ip_info = $ipLocation->getIpInfo();

        $map = array();
        $map['user_login'] = $_POST['username'];
        $map['user_status'] = array('gt', 0);

        $authInfo = RBAC::authenticate($map);

        if (false === $authInfo) {
            $this->error('帐号不存在或已禁用！');
        } else {
            if ($authInfo['user_pass'] != encrypt($_POST['password'])) {
                $this->error('密码错误或者帐号已禁用');
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['user_id'];
            if ($authInfo['user_login'] == get_opinion('Admin')) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }

            //记住我
            if (I('post.remember') == 1) {
                if ($authInfo['user_session'] != '') {
                    cookie('user_session', $authInfo['user_session'], 36000);
                } else if ($authInfo['user_session'] == '') {
                    $user_session = D('User', 'Logic')->genHash($authInfo);
                    cookie('user_session', $user_session, 36000);
                }
            }
            // 缓存访问权限
            RBAC::saveAccessList();

            $this->success('登录成功！', U("Admin/Index/index"), false);
        };


    }


    public function forgetPassword()
    {

    }

    public function logout()
    {
        $User = D('User', 'Logic');
        $authInfo = $User->detail(session(C('ADMIN_AUTH_KEY')));

        $greencms_hash = $User->genHash($authInfo);
        cookie('user_session', null);

        session_unset();
        session_destroy();

        $this->success('退出成功！', U('Admin/Login/index'), false);
    }
}