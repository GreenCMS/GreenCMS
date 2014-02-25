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

    /**
     *  [0] => array(13) {
    ["log_id"] => string(2) "40"
    ["MsgId"] => string(10) "5214124766"
    ["FromUserName"] => string(28) "oLXjgjiWeAS1gfe4ECchYewwoyTc"
    ["ToUserName"] => string(15) "gh_bea8cf2a04fd"
    ["CreateTime"] => string(10) "1392724755"
    ["Content"] => string(4) "test"
    ["MsgType"] => string(4) "text"
    ["Location_X"] => string(0) ""
    ["Location_Y"] => string(0) ""
    ["Scale"] => string(0) ""
    ["Label"] => string(0) ""
    ["PicUrl"] => string(0) ""
    ["user"] => NULL
    }
     */
    public function index()
    {
        $Weixinlog = D('Weixinlog');
        $message_list = $Weixinlog->order('CreateTime desc')->relation(true)->select();

        foreach ($message_list as $key => $value) {
            if ($value['user'] == null) {
                unset($message_list[$key]);
            }
        }

        $this->assign('message_list', $message_list);
        $this->display();

    }


    public function send()
    {
        $Users = D('Weixinuser');
        $user_list = $Users->select();


        $user_option = '';

        foreach ($user_list as $value) {
            $user_option .= '<option value="' . $value['openid'] . '"';

            if (I('get.openid') != '' && $value['openid'] == I('get.openid')) {
                $user_option .= 'selected="selected"';
            }

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