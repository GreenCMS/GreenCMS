<?php
/**
 * Created by Green Studio.
 * File: TextEvent.class.php
 * User: TianShuo
 * Date: 14-2-25
 * Time: 下午8:33
 */

namespace Weixin\Event;


use Weixin\Controller\WeixinCoreController;

/**
 * Class TextEvent
 * @package Weixin\Event
 */
class TextEvent extends WeixinCoreController
{


    /**
     * @param string $method
     * @param array $args
     * @return array
     */
    public function __call($method, $args)
    {
        $keyword = $method;
        $data = $args[0];


        if ($keyword == "hi" || $keyword == "hello" || $keyword == "你好" || $keyword == "您好") {
            $contentStr = "欢迎使用,回复help获得使用帮助"; // .$toUsername
        } else if (strtolower($keyword) == "help" || $keyword == '帮助') {
            $contentStr = get_opinion('weixin_help', true, "帮助:");
        } else {
            $CustomTextEvent = new CustomTextEvent();
            $reply = $CustomTextEvent->$keyword($data);
            return $reply;
        }

        $reply = array(
            $contentStr,
            'text'
        );


        return $reply;


    }




}