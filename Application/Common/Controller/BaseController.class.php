<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: BaseController.class.php
 * User: Timothy Zhang
 * Date: 14-1-11
 * Time: 下午1:44
 */
namespace Common\Controller;
use Think\Controller;
use Think\Hook;

/**
 * GreenCMS基类控制器
 * Class BaseController
 * @package Common\Controller
 */

abstract class BaseController extends Controller {
	/**
	 *
	 */
	function __construct() {
		parent::__construct();

	}

	/**
	 * 获取kv
	 * @return array|mixed
	 */
	function getKvs() {
		$kv_array = S('kv_array');

		if ($kv_array && APP_Cache) {
			$res_array = $kv_array;
		} else {

			$Kvs = D('Kv')->where(1)->select();

			$res_array = array();
			foreach ($Kvs as $kv) {
				$res_array[$kv['kv_key']] = $kv['kv_value'];
			}

			if (APP_Cache) {S('kv_array', $res_array);
			}
		}

		C('kv', $res_array);
		return $res_array;
	}

	/**
	 * 用户存放在数据库中的配置，覆盖config中的
	 */
	function customConfig() {
		$customConfig = S('customConfig');
		if ($customConfig && APP_Cache) {
			$options = $customConfig;
		} else {
			$options = D('Options')->select();

			if (APP_Cache) {S('customConfig', $options);
			}
		}

		foreach ($options as $config) {
			C($config['option_name'], $config['option_value']);
		}
	}

	/**
	 * 判断是否为Sae平台
	 */
	function isSae() {
		if (defined('SAE_TMP_PATH')) {
			$this->error("当前功能不支持SAE下使用");
		}
	}

	/**
	 * 简化tp json返回
	 * @param int $status
	 * @param string $info
	 * @param string $url
	 */
	function jsonReturn($status = 1, $info = '', $url = '') {
		die(json_encode(array("status" => $status, "info" => $info, "url" => $url)));
	}

	function jsonResult($status = 1, $info = '', $url = '') {
		return json_encode(array("status" => $status, "info" => $info, "url" => $url));
	}

	function json2Response($json) {
		$resArray = json_decode($json, true);

		if ($resArray['status'] == 1) {
			if ($resArray['url'] != '') {
				$this->success($resArray['info'], $resArray['url'], false);
			} else {
				$this->success($resArray['info']);

			}
		} else {
			$this->error($resArray['info']);
		}
	}



    protected function themeConfig()
    {
        $theme_name=get_kv('home_theme');
        if (S($theme_name.'_theme_config')) {
            //有缓存
            C('theme_config',S($theme_name.'_theme_config'));
        } else {
            $theme = D("Theme")->field('theme_config')->where(array("theme_name" => $theme_name))->find();
            $theme = json_decode($theme['theme_config'], true);
            $theme = $theme['kv'];

            $theme_config = array();
            foreach ($theme as $key => $value) {
                $theme_config[$key] = $value['value'];
            }

            S($theme_name.'_theme_config', $theme_config, DEFAULT_EXPIRES_TIME);
            C('theme_config', $theme_config);

        }

    }




    protected  function anonymousPlugin($_addons = null, $_controller = null, $_action = null) {

        if ($_action == null) {
            return false;
        }

        if (get_opinion('URL_CASE_INSENSITIVE')) {
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



    /**
     *
     */
    protected function _currentUser()
    {
        $user_id = ( int )$_SESSION [get_opinion('USER_AUTH_KEY')];
        $user = D('User', 'Logic')->cache(true)->detail($user_id);
        $this->assign('user', $user);
    }

}