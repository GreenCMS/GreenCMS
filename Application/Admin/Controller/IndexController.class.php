<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:38
 */

namespace Admin\Controller;
use Think\Storage;

/**
 * Class IndexController
 * @package Admin\Controller
 */
class IndexController extends AdminBaseController
{
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
    public function main()
    {
        $this->redirect(getURL('Index/index'));
    }


    public function checkVersion()
    {
        $UpdateEvent = new \Common\Event\UpdateEvent();
        $cheack_res = $UpdateEvent->check();

        if ($cheack_res) {
            $message =
                '<li><a href="' . U("Admin/System/update") . '"><i class="fa fa-laptop"></i> 发现新的可升级版本</a></li>';
        } else {
            $message = 'none';
        }

        die($message);
    }


    public function ajaxCron()
    {
        die('ok');

    }


    /**
     *
     */
    public function changePass()
    {
        $this->display('changepass');
    }

    /**
     *
     */
    public function changepassHandle()
    {

        if (I('post.password') != I('post.rpassword')) {
            $this->error('两次密码不同');
        }

        $uid=(int)$_SESSION [C('USER_AUTH_KEY')];

        $UserEvent=new \Common\Event\UserEvent();
        $changePasswordRes=$UserEvent->changePassword($uid,I('post.opassword'),I('post.password'));

        $this->json2Response($changePasswordRes);

    }


    public function profile()
    {

        $uid= ( int )$_SESSION [C('USER_AUTH_KEY')];
        $user = D('User', 'Logic')->detail($uid);
        $this->assign('user', $user);
        $this->assign('action', '用户档案');

        $this->display();


    }



    public function sns(){
        $this->display();

    }


    public function updateComplete(){

        $Storage=new Storage();
        $Storage::connect();

        if($Storage::has("UpdateLOG")){
            $update_content=$Storage::read('UpdateLOG');
            $this->assign('update_content',$update_content);
        }

        $this->display("update");

    }



}