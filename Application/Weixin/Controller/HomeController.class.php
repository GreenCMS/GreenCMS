<?php
/**
 * Created by Green Studio.
 * File: HomeController.class.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 下午5:05
 */

namespace Weixin\Controller;



class HomeController extends WeixinBaseController
{
    public function index()
    {
        $this->display();
    }

    public function changePass()
    {
        $this->display();
    }

    public function changePassHandle()
    {

        if (I('post.password') != I('post.rpassword')) {
            $this->error('两次密码不同');
        }

        $uid = get_current_user_id();

        $UserEvent = new \Common\Event\UserEvent();
        $changePasswordRes = $UserEvent->changePassword($uid, I('post.opassword'), I('post.password'));

        $this->json2Response($changePasswordRes);

    }

}