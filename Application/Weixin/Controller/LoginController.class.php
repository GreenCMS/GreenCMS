<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: LoginController.class.php
 * User: Timothy Zhang
 * Date: 14-2-20
 * Time: 下午5:00
 */

namespace Weixin\Controller;

use Common\Controller\BaseController;
use Org\Util\Rbac;


class LoginController extends BaseController
{

    public function __construct()
    {
        parent::__construct();

//        $this->customConfig();

    }


    public function _before_index()
    {
        $user_session = cookie('user_session');
        if (!empty($user_session)) {
            $authInfo = D('User', 'Logic')->where(array('user_session' => $user_session))->find();

            if (!empty($authInfo)) {
                $_SESSION[get_opinion('USER_AUTH_KEY')] = $authInfo['user_id'];

                if ($authInfo ['user_login'] == get_opinion('ADMIN')) {
                    $_SESSION [get_opinion('ADMIN_AUTH_KEY')] = true;
                }

                $this->redirect('Weixin/Home/index');
            }


        }
    }

    public function index()
    {
        $this->display();
    }

    public function login()
    {
        // $ipLocation = new IpLocation();
        // $ip_info = $ipLocation->getIpInfo();


        $verify = new \Think\Verify();

        if (!$verify->check(I('post.vertify'))) {
            $this->error("验证码错误");
        }


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
            $_SESSION[get_opinion('USER_AUTH_KEY')] = $authInfo['user_id'];
            if ($authInfo['user_login'] == get_opinion('Admin')) {
                $_SESSION[get_opinion('ADMIN_AUTH_KEY')] = true;
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


            $this->success('登录成功！', U("Weixin/Home/index"), false);
        };


    }

    public function logout()
    {
        $User = D('User', 'Logic');
        $authInfo = $User->detail(session(get_opinion('ADMIN_AUTH_KEY')));

        $greencms_hash = $User->genHash($authInfo);
        cookie('user_session', null);

        session_unset();
        session_destroy();

        $this->success('退出成功！', U('Weixin/Login/index'), false);
    }
}