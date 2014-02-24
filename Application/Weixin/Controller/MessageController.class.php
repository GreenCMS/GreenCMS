<?php
/**
 * Created by Green Studio.
 * File: MessageController.class.php
 * User: TianShuo
 * Date: 14-2-24
 * Time: 下午10:51
 */

namespace Weixin\Controller;


class MessageController extends WeixinBaseController
{

    public function index()
    {
    }


    public function send()
    {
        $Users = D('Weixinuser');
        $user_list = $Users->select();


        $user_option = '';

        foreach ($user_list as $value) {
            $user_option .= '<option value="' . $value['openid'] . '"';
            $user_option .= '>' . $value['nickname'] . '</option>';

        }


        $this->assign('user_option', $user_option);

        $this->assign('form_action', U('Weixin/Message/sendHandle'));
        $this->assign('action_name', '发送');

        $this->display();
    }


    public function sendHandle()
    {
        $User = new \Weixin\Event\UserEvent();
        $User->sendMessage(I('post.openid'), I('post.content'));

    }


}