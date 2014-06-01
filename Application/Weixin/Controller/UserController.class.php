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

    public function index()
    {
//        $this->updateall();
        $Users = D('Weixinuser');
        $user_list = $Users->order('subscribe_time')->select();

        $this->assign('user_list', $user_list);
        $this->display();
    }

    public function updatenew()
    {
        @set_time_limit(0);

        $User = new \Weixin\Event\UserEvent();

        $res = $User->renew();


        $this->success('更新成功');
    }


    public function updateall()
    {
        @set_time_limit(0);

        $User = new \Weixin\Event\UserEvent();

        $res = $User->update();

        $this->success('同步成功');

    }

    public function detail($openid)
    {
        $this->assign('action', '用户详细');
        $this->assign('action_url', U('Weixin/User/detail', array('openid' => $openid)));
        $Users = D('Weixinuser');
        $user_detail = $Users->detail($openid, true);
        $this->assign('user_detail', $user_detail);
        $this->display();

    }

    public function chat($openid)
    {
        $Users = D('Weixinuser');
        $user_detail = $Users->detail($openid, false);

        $Weixinlog = D('Weixinlog');
        $Weixinsend = D('Weixinsend');

        $receive = $Weixinlog->field('MsgId,FromUserName as openid,CreateTime as time,Content as content')->
            where(array('FromUserName' => $openid))->select();
        foreach ($receive as $key => $value) {
            $receive[$key]['direction'] = 'in';
            $receive[$key]['headimgurl'] = $user_detail['headimgurl'];
            $receive[$key]['nickname'] =$user_detail['nickname'];

        }
//        dump($receive);
        $send = $Weixinsend->field('MsgId_to as MsgId,openid as openid,UNIX_TIMESTAMP(CreateTime) as time,Content as content')->
            where(array('openid' => $openid))->select();
        foreach ($send as $key => $value) {
            $send[$key]['direction'] = 'out';
            $send[$key]['headimgurl'] = __ROOT__ . '/Public/admin/assets/img/avatar1.jpg';
            $send[$key]['nickname'] ='我';

        }



        $chat = (sysSortArray(array_merge($receive, $send), 'time', "SORT_ASC"));
//        dump($chat);


        $this->assign('chat', $chat);

        $this->assign('action', '用户对话 - To:' . $user_detail['nickname']);
        $this->assign('action_url', U('Weixin/User/chat', array('openid' => $openid)));
        $this->assign('form_url', U('Weixin/User/chatHandle', array('openid' => $openid)));
        $this->assign('user_detail', $user_detail);
        $this->display();
    }

    public function chatHandle($openid)
    {
        $User = new \Weixin\Event\UserEvent();
        $res = $User->sendMessage(I('get.openid'), I('post.chat_content'));

        if ($res['errcode'] == 0) {
            $this->json_return(1, '发送成功'. $res['errmsg']);
        } else {
            $this->json_return(0, '发送失败' . $res['errmsg']);
        }


    }
}