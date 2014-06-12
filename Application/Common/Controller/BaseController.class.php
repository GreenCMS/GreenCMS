<?php
/**
 * Created by Green Studio.
 * File: BaseController.class.php
 * User: TianShuo
 * Date: 14-1-11
 * Time: 下午1:44
 */
namespace Common\Controller;
use Think\Hook;
use Think\Controller;

/**
 * GreenCMS基类控制器
 * Class BaseController
 * @package Common\Controller
 */
abstract class BaseController extends Controller
{
    /**
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 获取kv
     * @return array|mixed
     */
    function getKvs()
    {
        $kv_array = S('kv_array');

        if ($kv_array && APP_Cache) {
            $res_array = $kv_array;
        } else {

            $Kvs = D('Kv')->where(1)->select();

            $res_array = array();
            foreach ($Kvs as $kv) {
                $res_array[$kv['kv_key']] = $kv['kv_value'];
            }

            if (APP_Cache) S('kv_array', $res_array);
        }

        Hook::listen('base_getKvs');

        C('kv', $res_array);
        return $res_array;
    }

    /**
     * 用户存放在数据库中的配置，覆盖config中的
     */
    function customConfig()
    {
        $customConfig = S('customConfig');
        if ($customConfig && APP_Cache) {
            $options = $customConfig;
        } else {
            $options = D('Options')->where(array('autoload' => 'yes'))->select();

            if (APP_Cache) S('customConfig', $options);
        }
        foreach ($options as $config) {
            C($config['option_name'], $config['option_value']);
        }

        Hook::listen('base_customConfig');

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

    function json2Response($json){
        $changePasswordResArray=json_decode($json,true);

        if ($changePasswordResArray['status']==1) {
            if( $changePasswordResArray['url']!=''){
                $this->success($changePasswordResArray['info'], $changePasswordResArray['url'], false);
            }else{
                $this->success($changePasswordResArray['info']);

            }
        } else {
            $this->error($changePasswordResArray['info']);
        }
    }

 }