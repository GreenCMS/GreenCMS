<?php
/**
 * Created by Green Studio.
 * File: WeixinBaseController.class.php
 * User: TianShuo
 * Date: 14-2-18
 * Time: 下午2:01
 */

namespace Weixin\Controller;

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


        //使用缓存保存access_token , access_token有效时间是7200秒
        if (S('access_token') == '' || S('access_token') == false) {
            $appid = C('Weixin_appid');
            $secret = C('Weixin_secret');

            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";

            $json = file_get_contents($url);
            $res = json_decode($json, true);
            // dump($res);
            if ($res['errcode'] == 40013) return false;

            S('access_token', $res['access_token'], 7000);

            return $res['access_token'];
        } else {
            return S('access_token');
        }


    }


}