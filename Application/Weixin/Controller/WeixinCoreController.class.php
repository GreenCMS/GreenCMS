<?php
/**
 * Created by Green Studio.
 * File: WeixinBaseController.class.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午2:01
 */

namespace Weixin\Controller;

use Think\Controller;
use Common\Controller\BaseController;

abstract class WeixinCoreController extends BaseController
{


    public function __construct()
    {
        parent::__construct();


        $this->customConfig();

    }

    public function getAccess()
    {
        $appid = C('Weixin_appid');
        $secret = C('Weixin_secret');

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
        // echo $url;
        $json = file_get_contents($url);
        $res = json_decode($json, true);
        // dump($res);

        return $res;

    }


}