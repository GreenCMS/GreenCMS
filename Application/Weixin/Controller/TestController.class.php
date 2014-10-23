<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-10-23
 * Time: 下午9:23
 */

namespace Weixin\Controller;


use Common\Util\Curl;
use Weixin\Util\AccessToken;
use Weixin\Util\SendMsg;

class TestController extends WeixinCoreController
{


    public function index()
    {

        $SendMsg=new SendMsg();
        $SendMsg->test();

    }

    public function getAccessToken()
    {

        $Access= new AccessToken();
        $res = $Access->getAccessToken();
        dump($res);


    }


    public function curl()
    {

        $Curl = new Curl();

        $url = "https://api.weixin.qq.com/cgi-bin/token";

        $params['grant_type'] = 'client_credential';
        $params['appid'] = 'wx25dc2ed8888b623e';
        $params['secret'] = 'de7f2f20fcb0870a9c560f3879f62d9c';

        $res = $Curl->callApi($url, $params);
        // $res = $Curl->fetch("http://www.hao123.com");
        dump($res);


    }


}