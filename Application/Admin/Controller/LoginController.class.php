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

    }

    public function index()
    {
        $this->display();
    }

    public function login()
    {
        $ipLocation = new IpLocation();
        $ip_info = $ipLocation->getIpInfo();

        print_array($ip_info);

        if (IS_POST) {
            $map = array();
            $map['user_login'] = $_POST['username'];
            $map['user_status'] = array('gt', 0);

            $authInfo = RBAC::authenticate($map);

            print_array($authInfo);die();

            if (false === $authInfo) {
                $this->error('帐号不存在或已禁用！');
            } else {
                if ($authInfo['user_pass'] != encrypt($_POST['password'])) {
                    $this->error('密码错误或者帐号已禁用');
                }
                $_SESSION[C('USER_AUTH_KEY')] = $authInfo['user_id'];
                if ($authInfo['user_login'] == 'admin') {
                    $_SESSION[C('ADMIN_AUTH_KEY')] = true;
                }
                //记住我
                if ($_POST['remember'] == 1) {
                    if ($authInfo['sessioncode'] != '') {
                        cookie('greencms_hash', $authInfo['sessioncode'], 60 * 60 * 24);
                    } else if ($authInfo['sessioncode'] == '') {
                        $user = M('User');
                        $condition['user_id'] = $authInfo['user_id'];
                        $sessioncode = encrypt($user_info['$user_id'] . $user_info['user_pass'] . time());
                        $user->where($condition)->setField('sessioncode', $sessioncode);
                        cookie('greencms_hash', $sessioncode, 60 * 60 * 24);
                    }
                }
                // 缓存访问权限
                RBAC::saveAccessList();
                $this->success('登录成功！', U("Admin/Index/index"), false);
            };
        }


    }

}