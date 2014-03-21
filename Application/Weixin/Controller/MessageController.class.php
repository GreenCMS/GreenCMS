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
        $now = strtotime(date("Y-m-d H:i:s", time()));

        $Weixinlog = D('Weixinlog');
        $message_list = $Weixinlog->order('CreateTime desc')->relation(true)->select();

        foreach ($message_list as $key => $value) {
            if ($value['user'] == null) {
                unset($message_list[$key]);
                $Weixinlog->where(array('MsgId' => $value['MsgId']))->delete();
            } else {
                $time_remain = $now - (int)$value['CreateTime'] - 60 * 60 * 24 * 2;
                $time_remain < 0 ? $message_list[$key]['outofdate'] = '0' : $message_list[$key]['outofdate'] = '1'; //是否过期
                $time_remain < 0 ? $message_list[$key]['color'] = 'blue' : $message_list[$key]['color'] = 'red'; //是否过期
                $time_remain < 0 ? $message_list[$key]['msg'] = '回复消息' : $message_list[$key]['msg'] = '不能回复'; //是否过期
                $time_remain < 0 ? $message_list[$key]['msg_url'] =
                    U('Weixin/Message/send', array('openid' => $value['user']['openid'], 'msgid' => $value[MsgId]))
                    : $message_list[$key]['msg_url'] = '#'; //是否过期

            }

        }


        $this->assign('message_list', $message_list);
        $this->display();

    }

    public function del($msgid)
    {
        $Weixinlog = D('Weixinlog');
        $res = $Weixinlog->where(array('MsgId' => $msgid))->delete();

        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }

    }

    public function delAll()
    {
        $Weixinlog = D('Weixinlog');
        $res = $Weixinlog->where(1)->delete();

        if ($res) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }


    public function send()
    {
        $Users = D('Weixinuser');
        $user_list = $Users->relation(true)->select();

        $openid=I('get.openid');

        $user_option = '<option value="all">所有人</option>';

        $now = strtotime(date("Y-m-d H:i:s", time()));


        foreach ($user_list as $value) {
            $time_remain = $now - (int)$value['log'][0]['CreateTime'] - 60 * 60 * 24 * 2;
            if ($openid!= '' && $value['openid'] == $openid && ($time_remain > 0)) {
                $this->error('已经超过48小时,不能回复他了');
            }

            if ($value['log'][0]['CreateTime'] != null && ($time_remain < 0)) {
                $user_option .= '<option value="' . $value['openid'] . '"';
                if ($openid != '' && $value['openid'] == I('get.openid')) {
                    $user_option .= 'selected="selected"';
                }
                $user_option .= '>' . $value['nickname'] . '</option>';
            }
        }


        $this->assign('user_option', $user_option);

        $this->assign('form_action', U('Weixin/Message/sendHandle', array('msgid' => I('get.MsgId', '0'),'msgtype'=>I('get.msgtype', 'text'))));
        $this->assign('action_name', '发送');

        if(I('get.msgtype', 'text')=='text'){
          $this->display('sendtext');
        } elseif(I('get.msgtype')=='image'){
            $this->display('sendimage');

        } elseif(I('get.msgtype')=='news'){
            $this->display('sendnews');

        }
    }


    public function sendHandle($msgid = 0)
    {
        @set_time_limit(0);


        if (I('post.openid') != 'all') {

            $User = new \Weixin\Event\UserEvent();
            $res = $User->sendMessage(I('post.openid'), I('post.content'));
            if ($res['errcode'] == 0) {
                $this->success('发送成功');
            } else {
                $this->error('发送失败' . $res['errmsg']);
            }
        } else {
            $User = new \Weixin\Event\UserEvent();

            $Users = D('Weixinuser');
            $user_list = $Users->relation(true)->select();

            $info = '';
            $now = (int)current_timestamp();
            foreach ($user_list as $value) {
                $time_remain = $now - (int)$value['log'][0]['CreateTime'] - 60 * 60 * 24 * 2;

                if ($value['log'][0]['CreateTime'] != null && ($time_remain < 0)) {
                    $res = $User->sendMessage($value['openid'], I('post.content'));
                    $info .= $value['openid'] . $res['errmsg'];
                }
            }

            $this->success('发送成功:' . $info);

        }
    }
}