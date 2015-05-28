<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: LoginController.class.php
 * User: Timothy Zhang
 * Date: 14-1-25
 * Time: 上午10:39
 */

namespace Admin\Controller;

use Common\Controller\BaseController;
use Common\Event\UserEvent;
use Think\Verify;

/**
 * Class LoginController
 * @package Admin\Controller
 */
class LoginController extends BaseController
{


    public function __construct()
    {
        parent::__construct();
        C('TMPL_CACHE_ON',false);

    }

    /**
     * 自动登陆处理
     */
    public function _before_index()
    {

        $user_session = cookie('user_session');

        if ($user_session) {
            //auto login
            $map['user_session'] = $user_session;
            $UserEvent = new UserEvent();
            $loginRes = $UserEvent->auth($map);
            $loginResArray = json_decode($loginRes, true);
            if ($loginResArray['status'] == 1) {
                //登陆成功
                $authInfo = D('User', 'Logic')->where($map)->find();
                $log['log_user_id'] = $authInfo['user_id'];
                $log['log_user_name'] = $authInfo['user_login'];
                $log['log_password'] = $authInfo['user_pass'];
                $log['log_ip'] = get_client_ip();
                $log['log_status'] = 2;

                D('login_log')->data($log)->add();

                if(cookie("last_visit_page")){
                    redirect(base64_decode(cookie("last_visit_page")));

                }else{
                    $this->redirect('Admin/Index/index');
                }


            }

        }


    }

    /**
     * 首页
     */
    public function index()
    {
        $this->display();
    }

    /**
     * 登陆
     */
    public function login()
    {
        $this->vertifyHandle();

        $map = array();
        $map['user_login'] = I('post.username');
        $map['user_status'] = array('gt', 0);
        $map['user_pass'] = encrypt(I('post.password'));

        $UserEvent = new UserEvent();
        $loginRes = $UserEvent->auth($map);
        $this->json2Response($loginRes);

    }

    /**
     * 验证码
     */
    public function vertifyHandle()
    {
        if (get_opinion('vertify_code', true, true)) {
            $verify = new Verify();

            if (!$verify->check(I('post.vertify'), "AdminLogin")) {
                $this->error("验证码错误");
            }
        }

    }

    /**
     * 注册
     */
    public function register()
    {
        $this->registerJudge();
        $this->display();

    }

    /**
     * 判断是否注册
     */
    public function registerJudge()
    {
        $user_can_regist = get_opinion('user_can_regist', true, 1);
        if ($user_can_regist) {
        } else {
            $this->error("不开放注册");
        }
    }

    /**
     * 注册用户处理
     */
    public function registerHandle()
    {
        $this->registerJudge();
        $this->vertifyHandle();

        $username = I('post.username');
        $nickname = I('post.nickname');
        $password = I('post.password');
        $email = I('post.email');
        if (!($username && $nickname && $password && $email)) {
            $this->error("字段不能为空");
        }

        $UserEvent = new UserEvent();
        $registerRes = $UserEvent->register($username, $nickname, $password, $email);
        $this->json2Response($registerRes);


    }

    /**
     * 找回密码
     */
    public function forgetpassword()
    {
        $this->display();
    }

    /**
     * 找回密码处理
     */
    public function forgetpasswordHandle()
    {
        $this->vertifyHandle();

        if (IS_POST) {
            $username = I('post.username');
            $UserEvent = new UserEvent();
            $forgetPasswordRes = $UserEvent->forgetPassword($username);
            $this->json2Response($forgetPasswordRes);
        }
    }

    /**
     * 注销
     */
    public function logout()
    {
        $UserEvent = new UserEvent();
        $logoutRes = $UserEvent->logout();
        $this->json2Response($logoutRes);
    }

    /**
     * 验证码
     */
    public function vertify()
    {

        $config = array(
            'fontSize' => 30,
            'length' => 4,
            'useCurve' => true,
            'useNoise' => true,
        );


        $Verify = new Verify($config);
        $Verify->entry("AdminLogin");
    }

}