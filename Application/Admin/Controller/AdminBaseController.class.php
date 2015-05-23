<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: AdminBaseController.class.php
 * User: Timothy Zhang
 * Date: 14-1-25
 * Time: 上午10:39
 */

namespace Admin\Controller;

use Common\Controller\BaseController;
use Common\Logic\LogLogic;
use Org\Util\Rbac;
use Think\Log;

/**
 * Class AdminBaseController
 * @package Admin\Controller
 */
class AdminBaseController extends BaseController
{

    private $module_name = '';
    private $action_name = '';
    private $group_name = '';


    /**
     *
     */
    public function __construct()
    {

        parent::__construct();

        $this->_initialize();

        $this->_currentPostion();

        $this->_currentUser();

        $this->_recordCurrentPage();

//        $this->customConfig();
        C('TMPL_CACHE_ON',false);

    }

    /**
     *
     */
    protected function _initialize()
    {
        if (!RBAC::AccessDecision('Admin')) // AccessDecision中间使用分组名
        {
            // 登录检查
            RBAC::checkLogin();
            // 提示错误信息 无权限

            $httpReferer = $_SERVER['HTTP_REFERER'];
            $parsedHttpReferer = parse_url($httpReferer);
            $httpQuery = $parsedHttpReferer['query'];
            parse_str($httpQuery, $parsedHttpQuery);


            if ($parsedHttpQuery['a'] == 'login' && $parsedHttpQuery['c'] == 'login') {
                //防止循环跳转
                $UserEvent = new \Common\Event\UserEvent();
                $logoutRes = $UserEvent->logout();
                $this->error(L('_VALID_ACCESS_'));

            } else {
                $this->error(L('_VALID_ACCESS_'));

            }


        }

    }


    /**
     *
     */
    private function _currentPostion()
    {

        //  echo CONTROLLER_NAME;
        //  echo ACTION_NAME;

        $cache = get_opinion('admin_big_menu');
        foreach ($cache as $big_url => $big_name) {
            if (strtolower($big_url) == strtolower(CONTROLLER_NAME)) {
                $module = $big_name;
                $module_url = U("Admin/" . "$big_url" . '/index');
            } else {
            }
        }

        $cache = get_opinion('admin_sub_menu');
        foreach ($cache as $big_url => $big_name) {
            if (strtolower($big_url) == strtolower(CONTROLLER_NAME)) {
                foreach ($big_name as $sub_url => $sub_name) {
                    $sub_true_url = explode('/', $sub_url);
                    if (!strcasecmp($sub_true_url [1], strtolower(ACTION_NAME))) {
                        $action = $sub_name;
                        $action_url = U("Admin/" . "$sub_url");
                    }
                }
            }
        }

        $this->assign('group', '管理');
        $this->assign('module', $module);
        $this->assign('action', $action);
        $this->assign('module_url', $module_url);
        $this->assign('action_url', $action_url);

    }

    private function _recordCurrentPage()
    {
        if (!IS_AJAX) {
            cookie("last_visit_page", base64_encode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING']), 3600 * 24 * 30);
        }
    }

    /**
     *
     */
    public function saveConfig()
    {
        $post_data = I('post.');
        foreach ($post_data as $name => $value) {
            set_opinion($name, $value);
        }
    }

    /*
        public function __destruct()
        {

            $user_id = get_current_user_id();



                $group_level_1 = get_opinion('group_level_1');
                $admin_level_2 = get_opinion('admin_level_2');
                $admin_level_3 = get_opinion('admin_level_3');


                $this->group_name = $group_level_1[MODULE_NAME] ? $group_level_1[MODULE_NAME] : "Admin";
                $this->module_name = $admin_level_2[CONTROLLER_NAME] ? $admin_level_2[CONTROLLER_NAME] : CONTROLLER_NAME;
                $this->action_name = $admin_level_3[CONTROLLER_NAME] [ACTION_NAME] ?
                    $admin_level_3[CONTROLLER_NAME] [ACTION_NAME] : CONTROLLER_NAME . '/' . ACTION_NAME;

            if($user_id){

                $LogLogic = D('Log');

                $log_data['user_id'] = $user_id;
                $log_data['group_name'] = $this->group_name;
                $log_data['module_name'] = $this->module_name;
                $log_data['action_name'] = $this->action_name;
                $log_data['message'] = '';
                $log_data['log_type'] = 1;
                $log_data['user_ip'] = get_client_ip();


                //Log::write( arr2str($log_data));
                $LogLogic->data($log_data)->add();
                //Log::write( $LogLogic->getlastsql());

            }


            parent::__destruct();

        }
    */

    public function isSuperAdmin()
    {
        $uid = ( int )$_SESSION [get_opinion('USER_AUTH_KEY')];
        if ($uid == 1) return true;
        else return false;
    }

    /**
     *
     */
    protected function saveKv()
    {
        S('kv_array', null); //清空缓存

        foreach ($_POST as $key => $value) {
            set_kv($key, $value);
        }


    }

}