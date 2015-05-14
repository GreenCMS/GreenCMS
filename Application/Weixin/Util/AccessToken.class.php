<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-10-23
 * Time: 下午9:25
 */

namespace Weixin\Util;


use Common\Util\Curl;
use Think\Log;

/**
 * Class AccessToken
 * @package Weixin\Util
 */
class AccessToken
{


    /**
     * AccessAPI
     * @var string
     */
    private static $AccessTokenURL = 'https://api.weixin.qq.com/cgi-bin/token';


    /**
     * 获取当前使用的AccessToken
     * @return bool|mixed|null
     */
    public function getAccessToken()
    {

        //使用缓存保存access_token , access_token有效时间是7200秒 , 防止超时保守起见设定 6000 秒
        if (S('access_token') == '' || S('access_token') == false) {
            $access_token = $this->_fetchAccessToken();
            if ($access_token) {
                S('access_token', $access_token, 6000);
                return $access_token;
            } else {
                Log::write("Error occurred when fetch AccessToken");
                return false;
            }
        } else {
            return S('access_token');
        }
    }


    /**
     * 获取Access Token
     * @return null | string
     */
    private function _fetchAccessToken()
    {

        $Curl = new Curl();

        $params['grant_type'] = 'client_credential';
        $params['appid'] = get_opinion('Weixin_appid');
        $params['secret'] = get_opinion('Weixin_secret');


        $accessToken = $Curl->callApi($this::$AccessTokenURL, $params, 'GET');

        if (!isset($accessToken['access_token'])) {
            Log::write("Error occurred when call AccessToken Api");
            return null;
        }

        return $accessToken['access_token'];
    }


}