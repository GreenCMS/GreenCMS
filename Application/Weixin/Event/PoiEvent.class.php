<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-25
 * Time: 下午11:30
 */

namespace Weixin\Event;


use Weixin\Controller\WeixinCoreController;

class PoiEvent extends WeixinCoreController
{

    /**
     * @param $position
     * @return mixed
     * sample
     *
     * {
     * "status":"OK",
     * "result":{
     * "location":{
     * "lng":116.327159,
     * "lat":39.990912
     * },
     * "formatted_address":"北京市海淀区中关村南一街7号平房-4号",
     * "business":"中关村,北京大学,五道口",
     * "addressComponent":{
     * "city":"北京市",
     * "district":"海淀区",
     * "province":"北京市",
     * "street":"中关村南一街",
     * "street_number":"7号平房-4号"
     * },
     * "cityCode":131
     * }
     * }
     */
    public function process($position)
    {

        $url = 'http://api.map.baidu.com/geocoder?';

        $location = $position['Location_X'] . ',' . $position['Location_Y'];
        $infourl = $url . '&location=' . $location . '&output=json&pois=1';
        $JsonInfo = file_get_contents($infourl);
        $poiInfo = json_decode($JsonInfo, true);
        //    $city = $poiInfo['result']['addressComponent']['city'];
        //  $length = mb_strlen($city, 'UTF8') - 1;
        //    $city = mb_substr($city,            0, $length, 'UTF8');

        if ('OK' == $poiInfo['status']) {
            $contentStr = "您的位置: " . $poiInfo['result']['formatted_address'];
        } else {
            $contentStr = '解析地址失败';
        }

        return $contentStr;

    }

} 