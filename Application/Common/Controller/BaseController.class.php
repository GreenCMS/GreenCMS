<?php
/**
 * Created by Green Studio.
 * File: BaseController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:44
 */
namespace Common\Controller;

use Think\Controller;

/**
 * Class BaseController
 * @package Common\Controller
 */
class BaseController extends Controller
{

    /**
     * 获取配置
     * @return mixed
     */
    function getConfig()
    {
        $options = D('Options')->where(array('autoload' => 'yes'))->select();
        return $options;
    }

    /**
     * 用户存放在数据库中的配置，覆盖config中的
     */
    function customConfig()
    {
        $options = $this->getConfig();
        foreach ($options as $config) {
            C($config['option_name'], $config['option_value']);
        }
    }


    /**
     * check_verify
     */
    function check_verify()
    {
        if (!APP_DEBUG) {
            if ($_SESSION['verify'] != md5(I('post.verify')))
                $this->error('验证码错误！');
        }
    }


    function is_sae()
    {
        if (defined('SAE_TMP_PATH')) {
            $this->error("当前功能不支持SAE下使用");
        }
    }

    function json_return($status=1,$info='',$url='')
    {
        die(json_encode(array("status" => $status, "info" =>$info,"url" =>$url)));
    }
}