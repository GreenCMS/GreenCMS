<?php

namespace Addons\Join2011\Controller;

use Addons\Join2011\Join2011Addon;
use Home\Controller\AddonsController;

class Join2011Controller extends AddonsController {

	public $_config = array();

	function __construct() {
		parent::__construct();

		$Join2011Addon = new Join2011Addon();
		$_config       = $Join2011Addon->getConfig();

		if ($_config['openReg'] == 0) {

			$this->error("报名通道目前关闭", U('Home/Index/index'));
		}

		if ((strtotime($_config['endTime'])-time()) < 0) {

			$this->error("报名已经截至", U('Home/Index/index'));

		}

	}

	public function index() {
		if (IS_POST) {

			if (empty($_POST['ksh'])) {$this->error("请填写考生号", addons_url('Join2011://Join2011/index'));
			}

			$Stu = D('Stu');

			$contion['ksh'] = $_POST['ksh'];
			//print_array($Stu->where($contion)->select());
			if ($Stu->where($contion)->find()) {

				session('ksh', $_POST['ksh']);
				redirect(addons_url('Join2011://Join2011/step2'));

			} else {
				$this->error("您不在初选名单", addons_url('Join2011://Join2011/index'));
			}

		} else {

			$this->assign("ksh", $_SESSION['ksh']);

			$this->display(T('Addons://Join2011@Join2011/index'));

		}

	}

	public function step2() {

		$Stu            = D('Stu');
		$contion['ksh'] = $_SESSION['ksh'];

		if ($Stu->where($contion)->find()) {
			$stu = $Stu->where($contion)->find();

			$this->assign('stu', $stu);

			$this->display(T('Addons://Join2011@Join2011/step2'));

		} else {

			$this->error("您不在初选名单", addons_url('Join2011://Join2011/index'));
		}

	}

	public function step3() {
		if (IS_POST) {
			if ($_POST['if2011'] != '是') {$this->error("您已放弃报名", addons_url('Join2011://Join2011/index'));
			}

			//print_array($_POST);
			$Stu = D('Stu');
			$Bmb = D('Bmb');

			$data['ksh']     = $_SESSION['ksh'];
			$data['score']   = $_POST['score'];
			$data['jsscore'] = $_POST['jsscore'];
			$data['phone']   = $_POST['phone'];

			if ($Stu->where(array('ksh' => $_SESSION['ksh']))->find()) {
			} else {
				$this->error("您不在初选名单", addons_url('Join2011://Join2011/index'));
			}

            if ($Bmb->where(array('ksh' => $_SESSION['ksh']))->find()) {
                $this->error("您已经报名", addons_url('Join2011://Join2011/index'));
            }


			if ($Bmb->data($data)->add()) {
				session('ksh', '');

				$this->success('报名成功', U('Home/Index/index'));

			} else {


				$this->error("报名失败，请重新报名", addons_url('Join2011://Join2011/index'));

			}

		} else {

			$this->error("您不可以直接访问这个页面", addons_url('Join2011://Join2011/index'));

		}

	}

	public function test() {

		$this->show(' ');

	}

}
