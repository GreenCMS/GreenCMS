<?php
/**
 * Created by Green Studio.
 * File: LoginController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:39
 */

namespace Admin\Controller;

use Common\Controller\BaseController;
use Org\Util\Rbac;

/**
 * Class LoginController
 * @package Admin\Controller
 */
class LoginController extends BaseController
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

        $this->customConfig();

    }

    public function vertify()
    {

        $config = array(
            'fontSize' => 20,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => true,
        );


        $Verify = new \Think\Verify($config);
        $Verify->entry();
    }

    /**
     *
     */
    public function _before_index()
    {
//        $user_session = cookie('user_session');
        $map['user_session'] = cookie('user_session');

        $UserEvent = new \Common\Event\UserEvent();
        $loginRes = $UserEvent->auth($map);
        $loginResArray = json_decode($loginRes, true);

        if ($loginResArray['status'] == 1) {
            $authInfo = D('User', 'Logic')->where($map)->find();
            $log['log_user_id'] = $authInfo['user_id'];
            $log['log_user_name'] = $authInfo['user_login'];
            $log['log_password'] = $authInfo['user_pass'];
            $log['log_ip'] = get_client_ip();
            $log['log_status'] = 2;

            D('login_log')->data($log)->add();

            /**
             * 自动恢复原状态
             */
            $httpReferer = $_SERVER['HTTP_REFERER'];
            $parsedHttpReferer = parse_url($httpReferer);
            $httpQuery = $parsedHttpReferer['query'];
            parse_str($httpQuery, $parsedHttpQuery);


            if ($parsedHttpQuery['m'] == 'admin' && $parsedHttpQuery['c'] != 'login' && $parsedHttpQuery['c'] != 'index' && $parsedHttpQuery['c'] != '' && $parsedHttpQuery['a'] != '') {
                $this->redirect('Admin/' . $parsedHttpQuery['c'] . '/' . $parsedHttpQuery['a'] . '');
            } else {
                $this->redirect('Admin/Index/index');
            }

        }


    }

    /**
     *
     */
    public function index()
    {


        $this->display();
    }

    /**
     *
     */
    public function login()
    {
        // $ipLocation = new IpLocation();
        // $ip_info = $ipLocation->getIpInfo();

        if (get_opinion('vertify_code', true, true)) {
            $verify = new \Think\Verify();

            if (!$verify->check(I('post.vertify'))) {
                $this->error("验证码错误",U('Admin/Login/index'));
            }
        }
        $map = array();
        $map['user_login'] = I('post.username');
        $map['user_status'] = array('gt', 0);
        $map['user_pass'] = encrypt(I('post.password'));

        $UserEvent = new \Common\Event\UserEvent();
        $loginRes = $UserEvent->auth($map);
        $this->json2Response($loginRes);

    }


    public function register()
    {
        $user_can_regist = get_opinion('user_can_regist', true, 1);
        if ($user_can_regist) {
            $this->display();
        } else {
            $this->error("不开放注册");
        }
    }

    public function registerHandle()
    {

        $user_can_regist = get_opinion('user_can_regist', true, 1);
        if ($user_can_regist) {
            $username = I('post.username');
            $nickname = I('post.nickname');
            $password = I('post.password');
            $email = I('post.email');
            if (!($username && $nickname && $password && $email)) {
                $this->error("字段不能为空");
            }

            $UserEvent = new \Common\Event\UserEvent();
            $registerRes = $UserEvent->register($username, $nickname, $password, $email);
            $this->json2Response($registerRes);


        } else {
            $this->error("不开放注册");

        }


    }


    /**
     *
     */
    public function forgetpassword()
    {
        $this->display();
    }

    /**
     *
     */
    public function forgetpasswordHandle()
    {
        $verify = new \Think\Verify();

        if (!$verify->check(I('post.vertify'))) {
            $this->error("验证码错误",U('Admin/Login/forgetpassword'));
        }

        if (IS_POST) {

            $email = I('post.email');

            $UserEvent = new \Common\Event\UserEvent();
            $forgetPasswordRes = $UserEvent->forgetPassword($email);
            $this->json2Response($forgetPasswordRes);

        }


    }

    /**
     *
     */
    public function logout()
    {
        $UserEvent = new \Common\Event\UserEvent();
        $logoutRes = $UserEvent->logout();
        $this->json2Response($logoutRes);

    }
}