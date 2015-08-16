<?php
/**
 * Created by PhpStorm.
 * User: TianShuo
 * Date: 2015/8/12
 * Time: 17:11
 */

namespace Admin\Controller;
use Common\Logic\UserLogic;
use Oauth\Logic\User_snsLogic;
use Common\Event\CountEvent;

class MemberController extends AdminBaseController
{


    /**
     * 修改密码页面
     */
    public function changePass()
    {
        $this->display('changepass');
    }

    /**
     * 修改密码处理
     */
    public function changepassHandle()
    {

        if (I('post.password') != I('post.rpassword')) {
            $this->error('两次密码不同');
        }

        $uid = $this->_currenUserId();

        $UserLogic = new UserLogic();

        $res = $UserLogic->changePassword($uid, I('post.opassword'), I('post.password'));

        $this->array2Response($res);

    }


    /**
     * 用户信息
     */
    public function profile()
    {
        $uid = $this->_currenUserId();

        $CountEvent = new CountEvent();

        $user = D('User', 'Logic')->detail($uid);

        $this->assign("PostCount", $CountEvent->getPostCount(array("user_id" => $uid)));

        $this->assign('user', $user);

        $this->display();


    }

    /**
     * 用户信息信息保存
     * @param $uid int user_id
     */
    public function profileHandle($uid)
    {
        $this->_checkCurrentUser($uid);

        $UserLogic = new UserLogic();

        $post_data = I('post.');

        $res = $UserLogic->update($uid, $post_data);

        $this->array2Response($res);

    }

    /**
     * 社交账号绑定
     */
    public function sns()
    {
        $User_snsLogic = new  User_snsLogic();

        $User_snsSINA = $User_snsLogic->detailByUID(get_current_user_id(), 'SINA');

        if ($User_snsSINA['User']['user_id'] == get_current_user_id()) {
            $this->assign("SINA", '<a class="btn btn-primary" href="' . U('Oauth/Sns/logout', array('type' => 'sina')) . '">解除微博登陆绑定</a>');

        } else {
            $this->assign("SINA", '<a class="btn btn-primary" href="' . U('Oauth/Sns/login', array('type' => 'sina')) . '">微博登陆绑定</a>');

        }

        $this->display();

    }


}