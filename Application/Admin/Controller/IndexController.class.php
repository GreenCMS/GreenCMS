<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: Timothy Zhang
 * Date: 14-1-25
 * Time: 上午10:38
 */

namespace Admin\Controller;

use Common\Event\AccessEvent;
use Common\Event\CountEvent;
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
        $CountEvent = new CountEvent();


        $this->assign("PostCount", $CountEvent->getPostCount());
        $this->assign("UserCount", $CountEvent->getUserCount());


        if(get_opinion("oem_info",false,'original')!='original' ){
            $this->display("oem");
        }else{
            $this->display();
        }

    }


    /**
     * 返回home
     */
    public function main()
    {
        $this->redirect('Home/Index/index');
    }


    public function checkTodo()
    {
        $checkTodo = S("checkTodo");
        if (empty($checkTodo)) {


            $check_res = "";

            $AccessEvent = new AccessEvent();
            $UpdateEvent = new UpdateEvent();

            if ($UpdateEvent->check()) {
                $check_res .= '<li><a href="' . U("Admin/System/update") . '"><i class="fa fa-laptop"></i> 发现新的可升级版本</a></li>';
            }


            if (!$AccessEvent->checkAccess()) {
                $check_res .= '<li><a href="' . U("Admin/Access/rolelist") . '"><i class="fa fa-laptop"></i> 需要重建角色权限！</a></li>';

            }

            if (!$AccessEvent->checkNode()) {
                $check_res .= '<li><a href="' . U("Admin/Access/nodelist") . '"><i class="fa fa-laptop"></i> 需要重建节点！</a></li>';


            }

            if ($check_res == "") {
                $check_res = "none";
            }


            S("checkTodo", $check_res);


            die($check_res);

        } else {

            die(S("checkTodo"));

        }
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
        $CountEvent = new CountEvent();


        $uid = get_current_user_id();
        $user = D('User', 'Logic')->detail($uid);

        $this->assign("PostCount", $CountEvent->getPostCount(array("user_id"=>$uid)));

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