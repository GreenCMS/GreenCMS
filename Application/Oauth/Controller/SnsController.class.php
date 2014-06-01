<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-1
 * Time: 下午10:05
 */

namespace Oauth\Controller;

use \Extend\ThinkSDK\ThinkOauth;
use \Oauth\Event\TypeEvent;

class SnsController extends OauthBaseController
{


    public function __construct()
    {
        parent::__construct();


        include_once(Extend_PATH . 'ThinkSDk/ThinkOauth.class.php');


    }

    // 登录地址
    public function login($type = null)
    {
        empty ($type) && $this->error('参数错误');

        // 加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);

        // 跳转到授权页面
        redirect($sns->getRequestCodeURL());
    }

    // 授权回调地址
    public function callback($type = null, $code = null)
    {
        (empty ($type) || empty ($code)) && $this->error('参数错误');

        // 加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);

        // 腾讯微博需传递的额外参数
        $extend = null;
        if ($type == 'tencent') {
            $extend = array(
                'openid' => $this->_get('openid'),
                'openkey' => $this->_get('openkey')
            );
        }

        // 请妥善保管这里获取到的Token信息，方便以后API调用
        // 调用方法，实例化SDK对象的时候直接作为构造函数的第二个参数传入
        // 如： $qq = ThinkOauth::getInstance('qq', $token);

        $token = $sns->getAccessToken($code, $extend);
        // 获取当前登录用户信息
        if (is_array($token)) {
            $TypeEvent = new TypeEvent;
            $user_info = $TypeEvent->$type ($token);

            echo("<h1>恭喜！使用 {$type} 用户登录成功</h1><br>");
            echo("授权信息为：<br>");
            dump($token);
            echo("当前登录用户信息为：<br>");
            dump($user_info);


            if ($user_info ['type'] == 'SINA') {

                /**
                 * 发布微博
                 */

                $url = "https://api.weibo.com/2/statuses/update.json";
                $content['status'] = "By GreenCMS v2.1.0601~~~~";
                $content['access_token'] = $token['access_token'];
                $query = http_build_query($content, '', '&');

                $res = simple_post($url, $query);
                dump($res);


//                $data = $token;
//                $data ['user_id'] = Session::get('user_id');
//                $data ['type'] = $user_info ['type'];
//
//                $Useropen = D('Useropen');
//                $check = $Useropen->detailByOpenid($token ['openid']);
//
//                if (empty ($check) && Session::get("user_id") != "") {
//                    $res = $Useropen->addUseropen($data);
//
//                    $this->redirect('Member/login', null, 5, $res ['info']);
//
//                } else {
//                    $User = D('User');
//                    $user = $User->detail($check ['user_id']);
//                    if (empty ($check)) {
//
//                        $this->error('登录失败,尚未绑定。请登录之后绑定帐号', U('Admin/Index/index'));
//                    } else {
//                        $this->redirect('Home/index');
//                    }
//                }

            } else {

                $this->error('非法调用');

            }

        }
    }

} 