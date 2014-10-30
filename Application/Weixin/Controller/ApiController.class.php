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
use Weixin\Event\ButtomEvent;
use Weixin\Event\EveEvent;
use Weixin\Event\PoiEvent;
use Weixin\Event\TextEvent;
use Weixin\Util\ThinkWechat;

/***
 * 微信模块所使用的服务API
 * Class ApiController
 * @package Weixin\Controller
 */
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
                '图像已接受',
                'text'
            );

        } elseif ('location' == $data ['MsgType']) {

            $TextEvent = new PoiEvent();

            $contentStr = $TextEvent->process($data);

            $reply = array(
                $contentStr,
                'text'
            );

        } elseif ('event' == $data ['MsgType']) {

            $event=$data ['Event'];

            $EveEvent = new EveEvent();
            $reply = $EveEvent->$event($data);


        } else {
            $reply = array(
                "error : unknown MsgType" . $data ['MsgType'],
                'text'
            );
        }
        return $reply;
    }


}