<?php
/**
 * Created by Green Studio.
 * File: AuthController.class.php
 * User: TianShuo
 * Date: 14-4-22
 * Time: 下午6:14
 */
/**
 * Created by Green Studio.
 * File: AuthController.class.php
 * User: TianShuo
 * Date: 14-4-22
 * Time: 下午6:14
 */

namespace Api\Controller;


class AuthController extends ApiBaseCOntroller
{

//    public function login()
    private function login() //暂不开放
    {

        $map = array();
        $map['user_login'] = I('post.username');
        $map['user_status'] = array('gt', 0);

        $authInfo = RBAC::authenticate($map);

        if (false === $authInfo) {
            $this->jsonReturn(0, '帐号不存在或已禁用！');
        } else {
            if ($authInfo['user_pass'] != encrypt(I('post.password'))) {
                $this->jsonReturn(0, '密码错误或者帐号已禁用');
            }
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['user_id'];
            if ($authInfo['user_login'] == get_opinion('Admin')) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }

            // 缓存访问权限
            RBAC::saveAccessList();


            if ($authInfo['user_session'] != '') {
                $user_session = $authInfo['user_session'];
            } else if ($authInfo['user_session'] == '') {
                $user_session = D('User', 'Logic')->genHash($authInfo);
            }

            $this->jsonReturn(1, '登录成功！', $user_session);
        };

    }

    public function relogin()
    {

    }

    public function logout()
    {

    }

    public function oauth()
    {

    }
}