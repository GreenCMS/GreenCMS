<?php

namespace Addons\join2011\Controller;

use Home\Controller\AddonsController;

class Join2011Controller extends AddonsController
{

    function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        if (IS_POST) {

            redirect(addons_url('Join2011://Join2011/step2'));


            if (empty($_POST['ksh'])) $this->error("请填写考生号", U('Index'));

            $Stu = D('Stu');

            $contion['ksh'] = $_POST['ksh'];
            //print_array($Stu->where($contion)->select());
            if ($Stu->where($contion)->find()) {

                session('ksh', $_POST['ksh']);
                redirect(U('Index/step2'));

            } else {
                $this->error("您不在初选名单", U('Index'));
            }


        } else {


            $this->display(T('Addons://Join2011@Join2011/index'));

        }


    }


    public function step2()
    {


        $Stu = D('Stu');
        $contion['ksh'] = $_SESSION ['ksh'];
      //  if ($Stu->where($contion)->find()) {
            $stu = $Stu->where($contion)->find();

            $this->assign('stu', $stu);




            $this->display(T('Addons://Join2011@Join2011/step2'));



        // } else {
       //     $this->error("您不在初选名单", U('Home/Index/index'));
      //  }


    }


    public function step3()
    {
        if (IS_POST) {
            if ($_POST['if2011'] != '是') $this->error("您已放弃报名", U('Index'));

            //print_array($_POST);

            $Bmb = D('Bmb');
            $data['ksh'] = $_SESSION ['ksh'];
            $data['score'] = $_POST ['score'];
            $data['jsscore'] = $_POST ['jsscore'];
            $data['phone'] = $_POST ['phone'];

            if ($Bmb->data($data)->add()) {
                session('ksh', '');
                $this->success('报名成功', U('Index'));

            } else {
                session('ksh', '');
                $this->error("报名失败，请重新报名", U('Index'));

            }


        } else {

            $this->error("您不可以直接访问这个页面", U('Index'));

        }

    }


    public function test()
    {

        $this->show(' ');


    }


}
