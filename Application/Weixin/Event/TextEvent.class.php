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
        } else if (strtolower($keyword) == "weather" || preg_match('/weather([^<>]+)/', $keyword)) {
            $keyword = trim(substr($keyword, 7));
            $contentStr = $this->getWeather($keyword);
        } else {
            //$contentStr = $this->wechat($data["Content"]);
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

    /**
     * @param $keyword
     * @return mixed
     */
    public function getWeather($keyword)
    {
        $resultStr = "";
        if ($keyword == "") {
            $keyword = "南京";
        }

        $apihost = "http://api2.sinaapp.com/";
        $apimethod = "search/weather/?";
        $apiparams = array(
            'appkey' => "0020120430",
            'appsecert' => "fa6095e113cd28fd",
            'reqtype' => "text"
        );
        $apikeyword = "&keyword=" . urlencode($keyword);
        $apicallurl = $apihost . $apimethod . http_build_query($apiparams) . $apikeyword;

        $weatherJson = file_get_contents($apicallurl);

        die($weatherJson);

        $weather = json_decode($weatherJson, true);
        $contentStr = $weather ['text'] ['content'];

        return $contentStr;
    }



    /**
     * @param $position
     * @return mixed
     */
    public function poi($position)
    {

        $url = 'http://api.map.baidu.com/geocoder/v2/?ak=96a6bf4739da4e7c5bf6e916ff1ad51c';

        $location = $position['Location_X'] . ',' . $position['Location_Y'];
        $infourl = $url . '&location=' . $location . '&output=json&pois=1';
        $JsonInfo = file_get_contents($infourl);
        $poiInfo = json_decode($JsonInfo, true);
        $city = $poiInfo['result']['addressComponent']['city'];
        $length = mb_strlen($city, 'UTF8') - 1;
        $city = mb_substr($city,
            0, $length, 'UTF8');

        $contentStr = $this->getWeather($city);

        return $contentStr;

    }







}