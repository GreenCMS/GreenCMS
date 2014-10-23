<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-10-23
 * Time: 下午9:25
 */

namespace Weixin\Util;


use Common\Util\Curl;

class AccessToken
{


    private static $AccessAPI = 'https://api.weixin.qq.com/cgi-bin/token';


    public function getAccessToken()
    {

        //使用缓存保存access_token , access_token有效时间是7200秒
        if (S('access_token') == '' || S('access_token') == false) {
            $access_token = $this->_fetchAccessToken();
            if ($access_token) {
                S('access_token', $access_token, 7000);
                return $access_token;
            } else {
                return false;
            }
        } else {
            return S('access_token');
        }
    }


    private function _fetchAccessToken()
    {

        $Curl = new Curl();

        $params['grant_type'] = 'client_credential';
        $params['appid'] = C('Weixin_appid');
        $params['secret'] = C('Weixin_secret');


        $accessToken = $Curl->callApi($this::$AccessAPI, $params, 'GET');

        if (!isset($accessToken['access_token'])) {
            return null;
        }

        return $accessToken['access_token'];
    }


}