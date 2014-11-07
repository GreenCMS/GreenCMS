<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-8-16
 * Time: 上午7:54
 */

namespace Weixin\Event;


class CustomTextEvent {


    /**
     * @param string $method
     * @param array $args
     * @return array
     */
    public function __call($method, $args)
    {
        $keyword = $method;
        $data = $args[0];

        $contentStr = $this->record($keyword);

        $reply = array(
            $contentStr,
            'text'
        );


        return $reply;


    }




    /**
     * @param $keyword
     * @return string
     */
    public function record($keyword)
    {

        $contentStr = '您的留言: '.$keyword.' 我们已经收到';
        return $contentStr;

    }




} 