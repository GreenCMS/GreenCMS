<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:38
 */

namespace Admin\Controller;

use Common\Event\UpdateEvent;
use Common\Event\UserEvent;
use Think\Storage;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class IndexController extends AdminBaseController
{
    /**
     * 首页基本信息
     */
    public function index()
    {
        $this->display();
    }


    /**
     * 返回home
     */
    public function main()
    {
        $this->redirect('Home/Index/index');
    }


    /**
     * 检查版本
     */
    public function checkVersion()
    {
        $UpdateEvent = new UpdateEvent();
        $cheack_res = $UpdateEvent->check();

        if ($cheack_res) {
            $message =
                '<li><a href="' . U("Admin/System/update") . '"><i class="fa fa-laptop"></i> 发现新的可升级版本</a></li>';
        } else {
            $message = 'none';
        }

        die($message);
    }


    /**
     * ajax定时计划触发
     */
    public function ajaxCron()
    {
        die('ok');

    }


    /**
     * 修改密码
     */
    public function changePass()
    {
        $this->display('changepass');
    }

    /**
     * 修改密码处理
     */
    public function changepassHandle()
    {

        if (I('post.password') != I('post.rpassword')) {
            $this->error('两次密码不同');
        }

        $uid = get_current_user_id();

        $UserEvent = new UserEvent();
        $changePasswordRes = $UserEvent->changePassword($uid, I('post.opassword'), I('post.password'));

        $this->json2Response($changePasswordRes);

    }


    /**
     * 用户信息
     */
    public function profile()
    {

        $uid = get_current_user_id();
        $user = D('User', 'Logic')->detail($uid);
        $this->assign('user', $user);
        $this->assign('action', '用户档案');

        $this->display();


    }


    /**
     * 社交账号绑定
     */
    public function sns()
    {
        $this->display();

    }





}