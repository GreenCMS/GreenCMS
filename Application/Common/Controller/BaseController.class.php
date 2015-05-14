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
abstract class BaseController extends Controller
{

    function __construct()
    {
        parent::__construct();

    }

    /**
     * 判断是否为Sae平台
     */
    function isSae()
    {
        if (defined('SAE_TMP_PATH')) {
            $this->error("当前功能不支持SAE下使用");
        }
    }

    /**
     * 获取主题个性设置
     * 缓存key ： $theme_name . '_theme_config'
     */
    protected function themeConfig()
    {
        $theme_name = get_kv('home_theme');
        if (S($theme_name . '_theme_config')) {
            //有缓存
            C('theme_config', S($theme_name . '_theme_config'));
        } else {
            $theme = D("Theme")->field('theme_config')->where(array("theme_name" => $theme_name))->find();
            $theme = json_decode($theme['theme_config'], true);
            $theme = $theme['kv'];

            $theme_config = array();
            foreach ($theme as $key => $value) {
                $theme_config[$key] = $value['value'];
            }

            S($theme_name . '_theme_config', $theme_config, DEFAULT_EXPIRES_TIME);
            C('theme_config', $theme_config);

        }

    }


    /**
     * 自动访问插件
     * @param null $_addons 插件
     * @param null $_controller 控制器
     * @param null $_action 操作
     * @return bool
     */
    protected function anonymousPlugin($_addons = null, $_controller = null, $_action = null)
    {

        if ($_action == null) {
            return false;
        }

        if (get_opinion('URL_CASE_INSENSITIVE')) {
            $_addons = ucfirst(parse_name($_addons, 1));
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
     * 获取当前用户信息
     */
    protected function _currentUser()
    {
        $user_id = ( int )$_SESSION [get_opinion('USER_AUTH_KEY')];
        $user = D('User', 'Logic')->cache(true, 10)->detail($user_id);
        $this->assign('user', $user);
    }


    /**
     * 检查当前用户id和传递id是否相同
     * @param $uid
     */
    protected function _checkCurrentUser($uid)
    {
        if ($uid != get_current_user_id()) {
            $this->error("不合法的操作");
        }
    }

    /**
     * 获取当前用户id
     */
    protected function _currenUserId()
    {
        return get_current_user_id();
    }


    //================================================
    //=============跳转控制============================
    //================================================


    /**
     * 简化tp json返回
     * @param int $status
     * @param string $info
     * @param string $url
     */
    function jsonReturn($status = 1, $info = '', $url = '')
    {
        die(json_encode(array("status" => $status, "info" => $info, "url" => $url)));
    }

    function jsonResult($status = 1, $info = '', $url = '')
    {
        return json_encode(array("status" => $status, "info" => $info, "url" => $url));
    }

    function json2Response($json)
    {
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

    function array2Response($resArray)
    {
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


    /**
     * 通过$res判断结果返回success或者error
     * @param mixed $res 结果集
     * @param string $message 信息前面附加信息
     */
    protected function _jumpByRes($res, $message = "")
    {
        if ($res) {
            $this->success($message . "更新成功");
        } else {
            $this->error($message . "更新失败");
        }
    }


}