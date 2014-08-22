<?php
/**
 * Created by Green Studio.
 * File: EmptyController.php
 * User: TianShuo
 * Date: 14-4-6
 * Time: 下午8:43
 */
/**
 * Created by Green Studio.
 * File: EmptyController.php
 * User: TianShuo
 * Date: 14-4-6
 * Time: 下午8:43
 */

namespace Home\Controller;

/**
 * 空控制器当访问出错时调用
 * Class EmptyController
 * @package Home\Controller
 */

class EmptyController extends HomeBaseController {
	/**
	 * 空控制器实现
	 * @param $method
	 * @param $args
	 * @return mixed|void
	 * @internal param $null
	 */
	public function __call($method, $args) {

		$pluginRes = $this->anonymousPlugin(CONTROLLER_NAME, ACTION_NAME, I('get.action'));

		if ($pluginRes) {
		} else {
			$this->error404();
		}

	}

	public function anonymousPlugin($_addons = null, $_controller = null, $_action = null) {

		if ($_action == null) {
			return false;
		}

		if (C('URL_CASE_INSENSITIVE')) {
			$_addons     = ucfirst(parse_name($_addons, 1));
			$_controller = parse_name($_controller, 1);
		}

		if (!empty($_addons) && !empty($_controller) && !empty($_action)) {

			$Addons = A("Addons://{$_addons}/{$_controller}")->$_action();
			return true;
		} else {
			return false;
		}

	}

}