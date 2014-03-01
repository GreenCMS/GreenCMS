<?php

namespace Weixin\Util;

class ZTSWechat
{

    /**
     * 微信推送过来的数据或响应数据
     *
     * @var array
     * @author ZTS
     * @version 1.0
     */
    private $data = array();

    /**
     * 构造方法，用于实例化微信SDK
     *
     * @param string $data
     *            接受到的$data数组
     */
    public function __construct($data)
    {
        $this->$data = $data;
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

        $response = new ZTSWechat ($position);
        $contentStr = $response->weather($city);

        return $contentStr;

    }


}
