<?php
/**
 * Created by Green Studio.
 * File: ButtomEvent.class.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午7:21
 */

namespace Weixin\Event;


use Weixin\Controller\WeixinCoreController;

class ButtomEvent extends WeixinCoreController
{

    public function __call($method, $args)
    {
        $where = array('action_name' => $method, 'action_type' => 'click');
        $res = D('Weixinaction')->where($where)->find();

        if (!empty($res)) {
            $reply_id = $res['reply_id'];
            $reply = D('Weixinre')->where(array('wx_re_id' => $reply_id))->find();
            if ($reply['type'] == 'text') {
                $reply = array(
                    htmlspecialchars_decode($reply['content']),
                    'text'
                );
                return $reply;
            } elseif ($reply['type'] == 'news') {

                $articles = array();
                $articles[0] = array(htmlspecialchars_decode($reply['title']), htmlspecialchars_decode($reply['description']), htmlspecialchars_decode($reply['picurl']), htmlspecialchars_decode($reply['url']));

                $reply = array(
                    $articles,
                    'news'
                );
                return $reply;

            } elseif ($reply['type'] == 'image') {
                $reply = array(
                    array($reply['mediaId']),
                    'image'
                );
                return $reply;
            }

        }
        $reply = array(
            '建设中:你点击了未定义回复的按钮,魔术方法回复' . $method,
            'text'
        );
        return $reply;


    }

    public function mtest()
    {
        $articles = array();
        $articles[0] = array('TEST', 'TEST Method', 'http://demo.greencms.net/Public/baracktocat.jpg', 'http://demo.greencms.net');

        $reply = array(
            $articles,
            'news'
        );
        return $reply;


//    public function contact()
//    {
//        $reply = array(
//            '建设中:你点击了按钮' . __FUNCTION__,
//            'text'
//        );
//        return $reply;
//    }


    }


    public function category()
    {
        $articles = array();
        $articles[0] = array('会呼吸迷你陶盆', '会呼吸迷你陶盆', 'http://www.easypot.cn/home/Public/huihuxi/001.jpg', 'http://www.easypot.cn/goods/goods_info.html');
        $articles[1] = array('会呼吸白色陶盆', '会呼吸白色陶盆', 'http://www.easypot.cn/home/Public/huihuxi/002.jpg', 'http://www.easypot.cn/goods/goods_info.html');
        $articles[2] = array('会呼吸彩绘陶盆', '会呼吸彩绘陶盆', 'http://www.easypot.cn/home/Public/huihuxi/003.jpg', 'http://www.easypot.cn/goods/goods_info.html');

        $reply = array(
            $articles,
            'news'
        );
        return $reply;

    }

}