<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午2:02
 */

namespace Weixin\Controller;

use Think\Log;
use Weixin\Event\TextEvent;
use Weixin\Util\ThinkWechat;

class ApiController extends WeixinCoreController
{

    public function index()
    {

        Log::record('收到消息' . date('Ymd H:m:s') . 'Form:' . get_client_ip());

        $weixin = new ThinkWechat (get_opinion('weixin_token'));

        /* 获取请求信息 */
        $data = $weixin->request();

        /* 获取回复信息 */
        list ($content, $type) = $this->reply($data);

        /* 响应当前请求 */
        $weixin->response($content, $type);

        Log::record('发送消息' . date('Ymd H:m:s'));

    }

    private function reply($data)
    {
        /**
         *
         * @param
         *            $data['Content']输入内容
         * @return $reply数组包括 返回内容和返回类型
         *
         *         <xml>
         *         <ToUserName><![CDATA[toUser]]></ToUserName>
         *         <FromUserName><![CDATA[fromUser]]></FromUserName>
         *         <CreateTime>1348831860</CreateTime>
         *         <MsgType><![CDATA[text]]></MsgType>
         *         <Content><![CDATA[this is a test]]></Content>
         *         <MsgId>1234567890123456</MsgId>
         *         </xml>
         */
        if ('text' == $data ['MsgType']) {
            $keyword = $data ['Content'];

            if (get_opinion('Weixin_log')) {
                $Weixinlog = D('Weixinlog');
                $Weixinlog->data($data)->add();
            }

            $keyword = trim($keyword);

            $TextEvent = new TextEvent();
            $reply = $TextEvent->$keyword($data);




        } elseif ('image' == $data ['MsgType']) {

            $reply = array(
                'image',
                'text'
            );

        } elseif ('location' == $data ['MsgType']) {

            //http://api.map.baidu.com/geocoder/v2/?ak=96a6bf4739da4e7c5bf6e916ff1ad51c&callback=renderReverse&location=32.106186,118.813850&output=json&pois=1
            $TextEvent = new TextEvent();

            $contentStr = $TextEvent->poi($data);

            $reply = array(
                $contentStr,
                'text'
            );

        } elseif ('event' == $data ['MsgType']) {
            if ('subscribe' == $data ['Event']) {
                $reply = array(
                    C('Weixin_reply_subscribe'),
                    'text'
                );
            } elseif ('unsubscribe' == $data ['Event']) {
                $reply = array(
                    C('Weixin_reply_unsubscribe'),
                    'text'
                );
            } elseif ('CLICK' == $data ['Event']) {
                $Buttom = new \Weixin\Event\ButtomEvent();
                $reply = $Buttom->$data ['EventKey']();

            }


        } else {
            $reply = array(
                "error occur",
                'text'
            );
        }
        return $reply;
    }


}