<?php
/**
 * Created by Green Studio.
 * File: UserController.class.php
 * User: TianShuo
 * Date: 14-2-20
 * Time: 下午5:40
 */

namespace Weixin\Controller;


class UserController extends WeixinBaseController
{

    public function test()
    {

        $User = new \Weixin\Event\UserEvent();
        $User->sendMessage('ovTdAuEr7c7CNNgOfw6lT8Kp1010','TEST主动发送');

    }

    public function index()
    {
//        $this->updateall();
        $Users = D('Weixinuser');
        $user_list = $Users->select();

        $this->assign('user_list', $user_list);
        $this->display();
    }

    public function updateall()
    {

        $User = new \Weixin\Event\UserEvent();

        $res = $User->getUserList();

        dump($res);

        foreach ($res as $openid) {
            $ifuser = D('Weixinuser')->where(array('openid' => $openid))->find();
            if ($ifuser) {
                //TODO 更新用户
                $data = $User->getUserDetail($openid);
                unset($data['openid']);
                D('Weixinuser')->where(array('openid' => $openid))->data($data)->save();
            } else {
                $data = $User->getUserDetail($openid);
                D('Weixinuser')->data($data)->add();
            }
//            dump($data);
//            dump(D('Weixinuser')->getlastsql());

        }


    }
}