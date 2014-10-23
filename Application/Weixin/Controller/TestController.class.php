<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 14-10-23
 * Time: 下午9:23
 */

namespace Weixin\Controller;


use Common\Util\Curl;
use Think\Model;
use Weixin\Util\AccessToken;
use Weixin\Util\Media;
use Weixin\Util\Menu;
use Weixin\Util\SendMsg;
use Weixin\Util\UserManagemant;

class TestController extends WeixinCoreController
{

    public function media()
    {

        $Media = new Media();
        $res = $Media->upload("http://static.cnbetacdn.com/slide/2014/1022/1413990687.jpg", "image");
        dump($res);



        $res =$Media->download($res['media_id']);
        dump($res);

    }


    public function menu()
    {

        $Menu = new Menu();

        $menu_content = $Menu->getMenu();

        dump($menu_content);


    }

    public function userManagemant()
    {

        $UserManagemant = new UserManagemant();

        $res = $UserManagemant->getFansList();

        foreach ($res["data"]['openid'] as $user_openid) {
            dump($UserManagemant->getUserInfo($user_openid));
        }
        dump($res);

        // dump($UserManagemant->createGroup("Timothy"));
        dump($UserManagemant->getGroupList());

        dump($UserManagemant->editUserGroup("oaUKpjkc5GaYdrlU0-IO2D-lpozI", 100));

        dump($UserManagemant->getGroupByOpenId("oaUKpjkc5GaYdrlU0-IO2D-lpozI"));


    }


    public function sendMsg()
    {

        $SendMsg = new SendMsg();
        $SendMsg->text("oaUKpjkc5GaYdrlU0-IO2D-lpozI", "fuck");

    }

    public function getAccessToken()
    {

        $Access = new AccessToken();
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