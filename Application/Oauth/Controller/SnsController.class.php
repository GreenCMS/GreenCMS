<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-6-1
 * Time: 下午10:05
 */

namespace Oauth\Controller;

use Common\Event\UserEvent;
use \Extend\ThinkSDK\ThinkOauth;
use \Oauth\Event\TypeEvent;
use \Oauth\Logic\User_snsLogic;
use Org\Util\Rbac;

/**
 * Class SnsController
 * @package Oauth\Controller
 */
class SnsController extends OauthBaseController
{


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
        include_once(Extend_PATH . 'ThinkSDK/ThinkOauth.class.php');
    }

    /**
     * 登录地址
     * @param null $type
     */
    public function login($type = null)
    {
        empty ($type) && $this->error('参数错误');

        // 加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);

        // 跳转到授权页面
        redirect($sns->getRequestCodeURL());
    }

    /**
     * 授权回调地址
     * @param null $type
     * @param null $code
     */
    public function callback($type = null, $code = null)
    {
        (empty ($type) || empty ($code)) && $this->error('参数错误');

        // 加载ThinkOauth类并实例化一个对象
        $sns = ThinkOauth::getInstance($type);

        // 腾讯微博需传递的额外参数
        $extend = null;
        if ($type == 'tencent') {
            $extend = array(
                'openid' => I('get.openid'),
                'openkey' => I('get.openkey')
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

//            echo("<h1>恭喜！使用 {$type} 用户登录成功</h1><br>");
//            echo("授权信息为：<br>");
//            dump($token);
//            echo("当前登录用户信息为：<br>");
//            dump($user_info);

            $user_id = (int)$_SESSION [C('USER_AUTH_KEY')];

            if ($user_info ['type'] == 'SINA') {

                /**
                 * 发布微博
                 */

//                $url = "https://api.weibo.com/2/statuses/update.json";
//                $content['status'] = "test to connect";
//                $content['access_token'] = $token['access_token'];
//                $query = http_build_query($content, '', '&');
//
//                $res = simple_post($url, $query);
//                dump($res);
                $data = $token;
                $data ['user_id'] = $user_id;
                $data ['type'] = $user_info ['type'];
                //增加过期时间，便于提醒
                $data ['expires_time'] = date('Y-m-d H:i:s', time() + (int)$token['expires_in']);

                $User_sns = new User_snsLogic();

                if ($user_id != null) {
                    //用户已登陆
                    $open_user_info = $User_sns->detailByUID($user_id, $type);

                    if (!empty($open_user_info)) {
                        //已绑定
                        //TODO 重新绑定
                        $this->success('已绑定' . $type);
                    } else {
                        //未绑定
                        $res = $User_sns->data($data)->add();
                        if ($res) {
                            $this->success($type . "绑定成功", U('Admin/Index/sns'));

                        } else {
                            $this->error($type . "绑定失败");
                        }

                    }


                } else {
                    //未登陆
                    $open_user_info = $User_sns->detailByOID($token ['openid'], $type);
                    if (!empty($open_user_info)) {
                        //已绑定
                        //TODO 开始登陆
                        $map = array();
                        $map['user_id'] = $open_user_info['User']['user_id'];
                        $map['user_login'] = $open_user_info['User']['user_login'];

                        $UserEvent = new UserEvent();
                        $UserEvent->auth($map);
                        $loginRes = $UserEvent->auth($map);
                        $this->json2Response($loginRes);

                    } else {
                        //未绑定
                        $this->error('登录失败,尚未绑定。请登录之后绑定帐号', U('Admin/Index/index'));
                    }

                }

            }else  if ($user_info ['type'] == 'GREENCMS'){


            } else {

                $this->error('非法调用');

            }

        }
    }

} 