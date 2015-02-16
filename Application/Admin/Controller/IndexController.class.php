<?php
/**
 * Created by GreenStudio GCS Dev Team.
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
use Common\Logic\UserLogic;
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


        if (get_opinion("oem_info", false, 'original') != 'original') {
            $this->display("oem");
        } else {
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

            if (!$UpdateEvent->checkVersion()) {
                $check_res .= '<li><a href="' . U("Admin/System/update") . '"><i class="fa fa-laptop"></i> 数据库中版本号与代码中不一致</a></li>';
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


    public function checkTodoCacheClear()
    {
        S("checkTodo", "");
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

        $uid = $this->_currenUserId();

        $UserLogic = new UserLogic();

        $res = $UserLogic->changePassword($uid, I('post.opassword'), I('post.password'));

        $this->array2Response($res);

    }


    /**
     * 用户信息
     */
    public function profile()
    {
        $CountEvent = new CountEvent();


        $uid = $this->_currenUserId();
        $user = D('User', 'Logic')->detail($uid);

        $this->assign("PostCount", $CountEvent->getPostCount(array("user_id" => $uid)));

        $this->assign('user', $user);
        $this->assign('action', '用户档案');

        $this->display();


    }

    /**
     * 用户信息信息保存
     */
    public function profileHandle($uid)
    {
        $this->_checkCurrentUser($uid);

        $UserLogic = new UserLogic();

        $post_data = I('post.');

        $res = $UserLogic->update($uid, $post_data);

        $this->array2Response($res);

    }

    /**
     * 社交账号绑定
     */
    public function sns()
    {
        $this->display();

    }


}