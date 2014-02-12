<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-1-25
 * Time: 上午10:38
 */

namespace Admin\Controller;

class IndexController extends AdminBaseController
{
    public function index()
    {


        $this->display();
    }


    public function main()
    {
        $this->redirect("Home/Index/index");
    }


    public function changePass()
    {

        $this->display();
    }

    public function changePassHandle()
    {
        $user = D('User', 'Logic');
        $user->user_id = (int)$_SESSION [C('USER_AUTH_KEY')];
        $user->user_pass = encrypt($_POST['password']);

        if ($user->save()) {
            $this->success('密码修改成功', U("Admin/Login/logout"), false);
        } else {
            $this->error('密码修改失败');

        }
    }


}