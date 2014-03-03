<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午2:02
 */

namespace Weixin\Controller;

use Weixin\Util\ThinkWechat;

class ApiController extends WeixinCoreController
{

    public function index()
    {

        \Think\Log::record('收到消息' . date('Ymd H:m:s'));

        $weixin = new ThinkWechat (get_opinion('weixin_token'));

        /* 获取请求信息 */
        $data = $weixin->request();

        /* 获取回复信息 */
        list ($content, $type) = $this->reply($data);

        /* 响应当前请求 */
        $weixin->response($content, $type);


    }

    public function test()
    {
        $code = I('get.code');
        $APPID = C('Weixin_appid');
        $SECRET = C('Weixin_secret');

        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$APPID&secret=$SECRET&code=$code&grant_type=authorization_code";
        $res = json_decode(file_get_contents($url),true);

        dump($res);

        $access_token = $res['access_token'];
        $openid = $res['openid'];

        $url2 = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid";

        $res2 = json_decode(file_get_contents($url2),true);
        dump($res2);


        // $this->index();
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
            $res = "可能有点问题，我知道什么什么。。~~~~";

            if (C('Weixin_log')) {
                $Weixinlog = D('Weixinlog');
                $Weixinlog->data($data)->add();
            }

            $keyword = trim($keyword);

            $Text = new \Weixin\Event\TextEvent();

            if ($keyword == "hi" || $keyword == "hello" || $keyword == "你好" || $keyword == "您好") {
                $contentStr = "欢迎使用,回复help获得使用帮助"; // .$toUsername
            } else if ($keyword == "help" || $keyword == "HELP") {
                $contentStr = "欢迎使用会呼吸,使用方法:\r\n";
                $contentStr .= "weather城市 查询天气\r\n";
            } else if ($keyword == "weather" || preg_match('/weather([^<>]+)/', $keyword)) {
                $keyword = trim(substr($keyword, 7));
                $contentStr = $Text->weather($keyword);
            } else {
                $contentStr = $Text->wechat($data);
            }

            $reply = array(
                $contentStr,
                'text'
            );


        } elseif ('image' == $data ['MsgType']) {

            $reply = array(
                'image',
                'text'
            );

        } elseif ('location' == $data ['MsgType']) {

            //http://api.map.baidu.com/geocoder/v2/?ak=96a6bf4739da4e7c5bf6e916ff1ad51c&callback=renderReverse&location=32.106186,118.813850&output=json&pois=1
            $Text = new \Weixin\Event\TextEvent();

            $contentStr = $Text->poi($data);

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
//                $reply = array(
//                    '你点击的按钮'.$data ['EventKey'],
//                    'text'
//                );
            }


        } else {
            exit ();
        }
        return $reply;
    }


//    public function access()
//    {
//        $access = $this->getAccess();
//        dump($access);
//    }
//
//    public function phpinfo()
//    {
//        echo phpinfo();
//    }


}