<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: UserEvent.class.php
 * User: Timothy Zhang
 * Date: 14-2-17
 * Time: 上午11:49
 */

namespace Common\Event;

use Common\Controller\BaseController;
use Common\Logic\UserLogic;
use Common\Util\GreenMail;
use Org\Util\Rbac;

/**
 * 用户事件
 * Class UserEvent
 * @package Common\Event
 */
class UserEvent extends BaseController
{

    /**
     * 用户忘记密码找回
     * @param $username
     * @return string
     */
    public function forgetPassword($username)
    {

        $User = new UserLogic();
        $GreenMail =new GreenMail();

        $userDetail = $User->where(array('user_login' => $username))->find();

        if (!$userDetail) {
            return $this->jsonResult(0, "不存在用户");
        }

        $new_pass = encrypt($userDetail['user_session']);
        $User->where(array('user_email' => $userDetail['user_email']))->data(array('user_pass' => $new_pass))->save();

        $res =  $GreenMail->sendMail( $userDetail['user_email'], "", "用户密码重置", "新密码: " . $userDetail['user_session']);

        if ($res['statue']) {
            return $this->jsonResult(1, "新密码的邮件已经发送到注册邮箱");

        } else {
            return $this->jsonResult(0, "请检查邮件发送设置".$res['info'] );

        }
    }


    /**
     * 认证用户，传入where查询 $map['user表字段']
     * @param $map
     * @return string
     */
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
            return $this->jsonResult(0, "用户名或者密码错误");

        } else {
            $_SESSION[C('USER_AUTH_KEY')] = $authInfo['user_id'];
            if ($authInfo['user_login'] == get_opinion('Admin') || $authInfo['user_id'] == 1) {
                $_SESSION[C('ADMIN_AUTH_KEY')] = true;
            }

            //记住我
            if (I('post.remember') == 1) {
                if ($authInfo['user_session'] != '') {
                    cookie('user_session', $authInfo['user_session'], 3600 * 24 * 30);
                } else if ($authInfo['user_session'] == '') {
                    $user_session = D('User', 'Logic')->genHash($authInfo);
                    cookie('user_session', $user_session, 3600 * 24 * 30);
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

            return $this->jsonResult(1, "登录成功", U("Admin/Index/index"));

        };

    }


    /**
     * 退出
     * @return string
     */
    public function logout()
    {

        $User = new UserLogic();
        $authInfo = $User->detail(session(C('ADMIN_AUTH_KEY')));

        $User->genHash($authInfo);
        cookie('user_session', null);

        session_unset();
        session_destroy();

        return $this->jsonResult(1, "退出成功", U("Admin/Login/index"));

    }


    /**
     * 注册用户
     * @param $username
     * @param $nickname
     * @param $password
     * @param $email
     * @return string
     */
    public function register($username, $nickname, $password, $email)
    {

        $new_user_role = get_opinion('new_user_role', true, 5);
        $new_user_statue = get_opinion('new_user_statue', true, 1);

        $User = new UserLogic();

        $userDetail = $User->where(array('user_login' => $username))->select();

        if ($userDetail != '') {
            return $this->jsonResult(0, "用户名已存在");
        } else {
            // 组合用户信息并添加

            $newUserDetail = array(
                'user_login' => $username,
                'user_nicename' => $nickname,
                'user_pass' => encrypt($password),
                'user_email' => $email,
                'user_status' => $new_user_statue,

                // 'logintime'=>time(),
                // 'loginip'=>get_client_ip(),
                // 'lock'=>$_POST['lock']
            );
            // 添加用户与角色关系

            $newUserDetail ['user_level'] = $new_user_role;

            $Role_users = D('Role_users');
            if ($new_id = $User->add($newUserDetail)) {

                $role = array(
                    'role_id' => $new_user_role,
                    'user_id' => $new_id
                );
                if ($Role_users->add($role)) {
                    return $this->jsonResult(1, "注册成功！", U('Admin/Access/index'));

                } else {
                    return $this->jsonResult(0, "注册成功，添加用户权限失败！");
                }
            } else {
                return $this->jsonResult(0, "注册用户失败");

            }

        }

    }
}