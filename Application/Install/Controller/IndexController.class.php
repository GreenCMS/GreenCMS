<?php
/**
 * Created by Green Studio.
 * File: IndexController.class.php
 * User: TianShuo
 * Date: 14-2-6
 * Time: 上午10:07
 */

namespace Install\Controller;

use Common\Util\File;
use Think\Controller;
use Think\Hook;
use Think\Think;

class IndexController extends InstallBaseController
{
    public function __construct()
    {
        parent::__construct();

        $lockFile = WEB_ROOT . 'Data/Install/install.lock';

        if (file_exists($lockFile)) {
            $this->error(" 你已经安装过GreenCMS，如果想重新安装，请先删除站点Data/install目录下的 install.lock 文件，然后再安装。");
        }
    }


    public function index()
    {

        $this->redirect('Install/Index/step1');
    }

    public function step1()
    {
        $this->assign('version', GreenCMS_Version);
        $this->assign('build', GreenCMS_Build);

        $this->display();
    }

    public function step2()
    {
        $sp_gd = gdversion();

        $this->assign('sp_name', $_SERVER['SERVER_NAME']);
        $this->assign('os_name', PHP_OS);
        $this->assign('server_software', $_SERVER['SERVER_SOFTWARE']);
        $this->assign('php_version', PHP_VERSION);
        $this->assign('web_root', WEB_ROOT);
        $this->assign('sp_safe_mode', (ini_get('safe_mode') ? '<font color=red>[×]On</font>' : '<font color=green>[√]Off</font>'));
        $this->assign('sp_gd', ($sp_gd > 0 ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>'));
        $this->assign('sp_mysql', (function_exists('mysql_connect') ? '<font color=green>[√]On</font>' : '<font color=red>[×]Off</font>'));


        // $this->assign('sp_host', (empty($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_HOST'] : $_SERVER['REMOTE_ADDR']));


        $test_dirs = array(
            '/',
            '/Core/*',
            '/Public/*',
            '/Application/*',
            '/Data/*',
            '/Upload/*',
            '/Extend/*',
        );

        $this->assign('sp_testdirs', $test_dirs);


        $this->display();

    }


    public function step3()
    {

        if (!empty($_SERVER['REQUEST_URI']))
            $scriptName = $_SERVER['REQUEST_URI'];
        else
            $scriptName = $_SERVER['PHP_SELF'];

        $basepath = __ROOT__;
        $cfg_cmspath = $basepath;


        if (!empty($_SERVER['HTTP_HOST'])) {
            $baseurl = 'http://' . $_SERVER['HTTP_HOST'] . $basepath;
            $cfg_basehost = 'http://' . $_SERVER['HTTP_HOST'];
        } else {
            $baseurl = "http://" . $_SERVER['SERVER_NAME'] . $basepath;
            $cfg_basehost = 'http://' . $_SERVER['SERVER_NAME'];
        }

        $this->assign('cfg_cmspath', $cfg_cmspath);
        $this->assign('baseurl', $baseurl);
        $this->assign('cfg_basehost', $cfg_basehost);
        $this->display();

    }

    public function step4()
    {
        header("Content-type:text/html;charset=utf-8");

        $time = date("Y-m-d H:m:s");

        $db_host = $_POST["db_host"];
        $db_port = $_POST["db_port"];

        $db_user = $_POST ["db_user"];
        $db_password = $_POST["db_password"];

        $db_name = $_POST["db_name"];
        $db_prefix = $_POST["db_prefix"];


        if ($_POST['admin_password'] != $_POST['admin_password2'] || trim($_POST['admin_password']) == '' || trim($_POST['admin_password2']) == '')
            $this->error("两次输入的密码不一致，请重新设定！，或者密码为空");
        else {
            $admin_user = $_POST['admin_user'];
            $admin_password = encrypt($_POST['admin_password']);
            $admin_email = $_POST['admin_email'];
            $user_session = encrypt($admin_user . $admin_password . time());


        }

        $title = $_POST['cfg_title'];
        $site_url = $_POST['cfg_basehost'] . $_POST['cfg_cmspath'];


        if (!testDB(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS))
            $this->error("数据库服务器或登录密码无效，\n\n无法连接数据库，请重新设定！");

        $conn = mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);

        mysql_select_db(SAE_MYSQL_DB, $conn); 

        /*$file = WEB_ROOT . 'Data/Install/db_config_sample.php';

        if (!file_exists($file))
            $this->error('Data/Install/db_config_sample.php文件不存在,请检查');
        $content = File::readFile($file);
        $content = str_replace("~dbhost~", $db_host, $content);
        $content = str_replace("~dbname~", $db_name, $content);
        $content = str_replace("~dbuser~", $db_user, $content);
        $content = str_replace("~dbpwd~", $db_password, $content);
        $content = str_replace("~dbprefix~", $db_prefix, $content);

        if (!File::writeFile(WEB_ROOT . 'db_config.php', $content, 'w+')) {
            $this->error("数据库配置文件写入失败，请您手动根据Data/Install/db_config_sample.php文件在根目录创建文件");
        }*/

        /*$sql_empty = File::readFile(WEB_ROOT . 'Data/Install/greencms_empty.sql');
        insertDB($sql_empty, $conn);

        $sql_empty = File::readFile(WEB_ROOT . 'Data/Install/greencms_init.sql');
        insertDB($sql_empty, $conn);*/

        /**
         * 插入管理员数据&更新配置
         */
        $admin_query = "INSERT INTO `user` (`user_id`, `user_login`, `user_pass`, `user_nicename`, `user_email`,
        `user_url`, `user_registered`, `user_activation_key`, `user_status`,  `user_intro`,
        `user_level`, `user_session`) VALUES(1, '{$admin_user}', '" . $admin_password . "', '管理员', '{$admin_email}',
         '', '{$time}', '', 1, '我是admin，欢迎使用', 2, '{$user_session}');";
        if (!mysql_query($admin_query, $conn)) $this->error(' 插入管理员数据出错');
        $cquery = "Update `options` set option_value='{$title}' where option_name='title';";
        if (!mysql_query($cquery, $conn)) $this->error(' 更新配置数据出错');
        $cquery = "Update `options` set option_value='{$site_url}' where option_name='site_url';";
        if (!mysql_query($cquery, $conn)) $this->error(' 更新配置数据出错');


        $software_version = GreenCMS_Version;
        $software_build = GreenCMS_Build;

        $cquery = "Update `options` set option_value='{$software_version}' where option_name='software_version';";
        if (!mysql_query($cquery, $conn)) $this->error(' 更新配置数据出错');
        $cquery = "Update `options` set option_value='{$software_build}' where option_name='software_build';";
        if (!mysql_query($cquery, $conn)) $this->error(' 更新配置数据出错');


        //TODO       今天就写到这里


        $this->redirect('Install/Index/step5');
    }


    public function step5()
    {

        //A('Install/Test')->init($key = 'zts');

        // $this->init();
        // File::delAll(RUNTIME_PATH);
        // File::delAll(LOG_PATH);
        // File::delAll(WEB_CACHE_PATH);
        // if (File::writeFile(WEB_ROOT . 'Data/Install/install.lock', 'installed', 'w+')) {
        //     $this->success('安装成功,5秒钟返回首页', 'Home/Index/index', 5);
        // }
        $this->success('安装成功,5秒钟返回首页', 'Home/Index/index', 5);

    }

    public function dbTest($db_host, $db_port, $db_user, $db_password)
    {
        if (testDB($db_host . ":" . $db_port, $db_user, $db_password))
            return 'ok';
        else
            return "数据库服务器或登录密码无效，\n\n无法连接数据库，请重新设定！";
    }


}