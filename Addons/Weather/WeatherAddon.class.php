<?php

// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Author: xiongjun <xiongjunceplj@163.com> 
// +----------------------------------------------------------------------

namespace Addons\Weather;

use Common\Controller\Addon;

/**
 * 系统环境信息插件
 * @author cepljxiongjun
 */
class WeatherAddon extends Addon
{

    public $info = array(
        'name'        => 'Weather',
        'title'       => '天气预报',
        'description' => '天气预报',
        'status'      => 1,
        'author'      => 'cepljxiongjun',
        'version'     => '0.1'
    );

    public function install()
    {
        return true;
    }

    public function uninstall()
    {
        return true;
    }

    public function getAddress()
    {
        $config = $this->getConfig();
        $ip = get_client_ip();
        $address = json_decode(file_get_contents("http://api.map.baidu.com/location/ip?ak=" . $config['ak'] . "&ip=" . $ip . "&coor=bd09ll"));
        if ($address->status == 1) {
             return $location =$config['city'];

        }
        return $location = (string)$address->content->address;
    }

    public function getWeather()
    {
        $config = $this->getConfig();
        $url = "http://api.map.baidu.com/telematics/v2/weather?location=" . $this->getAddress() . "&ak=" . $config['ak'] . "";

        $result = file_get_contents($url);

        $content = simplexml_load_string($result);
        $i = 1;
        foreach ($content->results->result as $result) {
            if ($i > $config['showday'])
                break;
            $list[$i]['date'] = (string)$result->date;
            $list[$i]['weather'] = (string)$result->weather;
            $list[$i]['wind'] = (string)$result->wind;
            $list[$i]['temperature'] = (string)$result->temperature;
            $list[$i]['pictureUrl'] = (string)$result->dayPictureUrl;
            $i++;
        }
        return $list;
    }

    //实现的AdminIndex钩子方法
    public function AdminIndex()
    {
        $config = $this->getConfig();
        $this->assign('addons_config', $config);
        foreach ($config['showplace'] as $k => $v) {
            if ($v == '0' && $config['display'])
                $this->display('widget');
        }
    }

    //实现的pageHeader钩子方法
    public function pageHeader()
    {
        $config = $this->getConfig();
        $this->assign('width', $config['showday'] * 140);
        $this->assign('location', $this->getAddress());
        $this->assign('lists', $this->getWeather());
        $this->assign('addons_config', $config);
        foreach ($config['showplace'] as $k => $v) {
            if ($v == '1' && $config['display'])
                $this->display('weather');
        }
    }
}

