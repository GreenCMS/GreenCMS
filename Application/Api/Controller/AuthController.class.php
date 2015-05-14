<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: AuthController.class.php
 * User: Timothy Zhang
 * Date: 14-4-22
 * Time: 下午6:14
 */
/**
 * Created by GreenStudio GCS Dev Team.
 * File: AuthController.class.php
 * User: Timothy Zhang
 * Date: 14-4-22
 * Time: 下午6:14
 */

namespace Api\Controller;


use Common\Util\Rbac;

class AuthController extends ApiBaseCOntroller
{

//    public function login()
    private function login() //暂不开放
    {

        $map = array();
        $map['user_login'] = I('post.username');
        $map['user_status'] = array('gt', 0);

        $map = array();
        $map['user_login'] = I('post.username');
        $map['user_status'] = array('gt', 0);
        $map['user_pass'] = encrypt(I('post.password'));

        $UserEvent = new \Common\Event\UserEvent();
        $loginRes = $UserEvent->auth($map);

    }

    public function relogin()
    {

    }

    public function logout()
    {

    }

    public function oauth()
    {

    }
}