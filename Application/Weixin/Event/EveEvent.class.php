<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-25
 * Time: 下午11:57
 */

namespace Weixin\Event;


use Weixin\Controller\WeixinCoreController;

class EveEvent extends WeixinCoreController
{


    public function subscribe($data)
    {


        $reply = array(
            C('Weixin_reply_subscribe'),
            'text'
        );

        return $reply;

    }

    public function unsubscribe($data)
    {
        $reply = array(
            C('Weixin_reply_unsubscribe'),
            'text'
        );

        return $reply;

    }

    public function scan($data)
    {
        $reply = array(
            "通过扫描二维码关注",
            'text'
        );

        return $reply;

    }

    public function CLICK($data)
    {
        $Buttom = new ButtomEvent();
       // $reply = $Buttom->$data ['EventKey']();

        $reply = array(
            "CLICK",
            'text'
        );
        return $reply;

    }

    public function LOCATION($data)
    {
        $reply = array(
            "使用LOCATION服务",
            'text'
        );


      //  return $reply;

    }


    /**
     * @param string $method
     * @param array $args
     * @return array
     */
    public function __call($method, $args)
    {
        $key = $method;
        $data = $args[0];

        $reply = array(
            "error : unknown Event" . $key,
            'text'
        );

        return $reply;

    }
} 