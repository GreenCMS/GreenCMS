<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:38
 */

namespace Admin\Controller;

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
            $message = '<li><!-- start message -->
                                    <a href="' . U("Admin/System/update") . '">
                                        <div class="pull-left">
                                            <img src="' . get_opinion("site_url") . '/public/baracktocat.jpg" class="img-circle" alt="User Image"/>
                                        </div>
                                        <h4>
                                            系统升级
                                            <small><i class="fa fa-clock-o"></i> 刚刚</small>
                                        </h4>
                                        <p>发现新的可升级版本</p>
                                    </a>
             </li>';

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

        $User = D('User', 'Logic');

        $user = $User->detail((int)$_SESSION [C('USER_AUTH_KEY')]);
        if ($user['user_pass'] != encrypt(I('post.opassword'))) {
            $this->error("原用户密码不正确");
        }

        $User->user_id = (int)$_SESSION [C('USER_AUTH_KEY')];
        $User->user_pass = encrypt($_POST['password']);

        if ($User->save()) {
            $this->success('密码修改成功', U("Admin/Login/logout"), false);
        } else {
            $this->error('密码修改失败');
        }
    }


}