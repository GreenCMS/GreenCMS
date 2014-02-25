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

class TextEvent extends WeixinCoreController
{

    public function wechat($keyword)
    {

    }

    public function weather($keyword)
    {
        $resultStr = "";
        if ($keyword == "") {
            $keyword = "南京";
            $resultStr .= "没有指定城市，默认显示南京";
        }
        $apihost = "http://api2.sinaapp.com/";
        $apimethod = "search/weather/?";
        $apiparams = array(
            'appkey'    => "0020120430",
            'appsecert' => "fa6095e113cd28fd",
            'reqtype'   => "text"
        );
        $apikeyword = "&keyword=" . urlencode($keyword);
        $apicallurl = $apihost . $apimethod . http_build_query($apiparams) . $apikeyword;
        $weatherJson = file_get_contents($apicallurl);
        $weather = json_decode($weatherJson, true);
        $contentStr = $weather ['text'] ['content'];

        return $contentStr;
    }


}