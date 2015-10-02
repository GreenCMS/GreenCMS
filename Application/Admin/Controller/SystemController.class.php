<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: SystemController.class.php
 * User: Timothy Zhang
 * Date: 14-1-26
 * Time: 下午5:28
 */

namespace Admin\Controller;

use Common\Event\SystemEvent;
use Common\Event\UpdateEvent;
use Common\Util\File;
use Common\Util\GreenMail;
use Think\Storage;

/**
 * Class SystemController
 * @package Admin\Controller
 */
class SystemController extends AdminBaseController
{

    /**
     *
     */
    public function index()
    {
        $this->display();
    }


    public function attach()
    {
        $this->display();
    }


    public function user()
    {
        $role_list = array_column_5(D('Role')->select(), 'name', 'id');
        $this->assign('user_can_regist', get_opinion('user_can_regist', true, 1));
        $this->assign('new_user_role', gen_opinion_list($role_list, get_opinion('new_user_role', true, 5)));

        $this->display();

    }

    /**
     *
     */
    public function post()
    {
        $this->display();
    }


    /**
     *
     */
    public function saveHandle()
    {
        $this->saveConfig();
        $this->success('配置成功');
    }

    /**
     *
     */
    public function kvset()
    {


        $this->display();
    }

    /**
     *
     */
    public function kvsetHandle()
    {
        $this->saveKv();
        $this->success('配置成功');
    }


    /**
     *
     */
    public function url()
    {
        //普通模式0, PATHINFO模式1, REWRITE模式2, 兼容模式3
        $url_model = get_opinion('url_model0');
        $home_post_model = get_opinion('post_model');
        $home_tag_model = get_opinion('tag_model');
        $home_cat_model = get_opinion('cat_model');

        $this->assign('home_post_model', gen_opinion_list($home_post_model, get_opinion('home_post_model', true)));
        $this->assign('home_tag_model', gen_opinion_list($home_tag_model, get_opinion('home_tag_model', true)));
        $this->assign('home_cat_model', gen_opinion_list($home_cat_model, get_opinion('home_cat_model', true)));

        $this->assign('url_mode', gen_opinion_list($url_model, (int)get_opinion('home_url_model', true)));

        $this->display();
    }


    /**
     * 邮箱配置
     */
    public function email()
    {
        $this->assign('mail_method', get_opinion('mail_method'));
        $this->display();
    }

    /**
     * 邮箱发送测试
     */
    public function emailSendTest()
    {
        $this->assign('action', '邮件发送测试');

        if (IS_POST) {

            $send_to = I('post.to_mail');

            $subject = "GreenCMS测试邮件";
            $body = "测试邮件通过" . get_opinion('mail_method') . '模式发送';
            $Mail = new GreenMail();
            $res = $Mail->sendMail($send_to, "GreenCMS Test Team", $subject, $body);

            $this->assign("config", $Mail->config);
            $this->assign("res", $res);
            $this->display('emailRes');

        } else {
            $this->display('emailTest');
        }

    }


    /**
     *
     */
    public function safe()
    {
        $this->display();
    }

    /**
     *
     */
    public function checkupdate()
    {
        $Update = new UpdateEvent();
        $Update->check();
    }

    /**
     *
     */
    public function update()
    {
        $message = "";


        if (IS_POST) {
            $version = I('post.version');
            $url = Server_API . 'api/update/' . $version . '/';
            $json = json_decode(file_get_contents($url), true);
            if (empty($json)) {
                $message .= "连接主升级服务器出错，使用备用服务器<br />";
                // try backup
                $url = Server_API2 . 'api/update/' . $version . '/';
                $json = json_decode(file_get_contents($url), true);
                if (empty($json)) $this->error('连接升级服务器出错');
            }


            $this->assign('versions', $json);
            $this->assign('message', $message);
            $this->assign('action', '选择升级版本');
            $this->display('update_s2');

        } else {
            $this->display();
        }
    }

    /**
     *
     */
    public function updateHandle()
    {

        G("UpdateHandle");

        $message = "";

        $version = I('get.version');
        $now_version = get_opinion('software_build', true);
        $url = Server_API . 'api/update/' . $now_version . '/';
        $json = json_decode(file_get_contents($url), true);
        G("GetJson");

        $message .= "下载Index文件成功,用时 " . G("UpdateHandle", "getJson") . "秒<br />";

        if (empty($json)) {
            $message .= "连接主升级服务器出错，使用备用服务器<br />";
            // try backup
            $url = Server_API2 . 'api/update/' . $now_version . '/';
            $json = json_decode(file_get_contents($url), true);
            G("GetJson");

            if (empty($json)) $this->error('连接升级服务器出错');
        }

        $target_version_info = ($json['file_list'][$version]);
        if (!empty($target_version_info)) {

            File::mkDir(WEB_CACHE_PATH);
            G("WebCache");
            $message .= "清空WEB_CACHE_PATH,用时 " . G("GetJson", "WebCache") . "秒<br />";


            $file_downloaded = WEB_CACHE_PATH . $target_version_info['file_name'];
            $file = file_get_contents($target_version_info['file_url']);

            if (File::writeFile($file_downloaded, $file)) {
                G("DownFile");

                $message .= "下载升级文件成功,用时 " . G("WebCache", "DownFile") . "秒<br />";
            } else {
                $this->error('下载文件失败');
            }

            //calculate md5 of file

            $file_md5 = md5_file($file_downloaded);
            G("MD5");
            $message .= "文件MD5值: $file_md5 ,用时 " . G("DownFile", "MD5") . "秒<br />";

            $System = new SystemEvent();
            //$System->backupFile();
            G("BackupFile");
            $message .= "系统备份已跳过 ,用时 " . G("MD5", "BackupFile") . "秒<br />";

            $zip = new \ZipArchive; //新建一个ZipArchive的对象
            if ($zip->open($file_downloaded) === true) {
                $zip->extractTo(WEB_ROOT); //假设解压缩到在当前路径下/文件夹内
                $zip->close(); //关闭处理的zip文件
                File::delFile($file_downloaded);
                G("UnzipFile");
                $message .= "解压成功 ,用时 " . G("BackupFile", "UnzipFile") . "秒<br />";

                $System->clearCacheAll();
                $message .= "清空缓存成功 <br />";

            } else {
                $this->error('文件损坏');
            }

            $old_build = get_opinion('software_build');
            $new_build = $target_version_info['build_to'];

            set_opinion('software_version', $target_version_info['version_to']);
            set_opinion('software_build', $target_version_info['build_to']);
            set_opinion('db_build', $target_version_info['build_to']);

            if (File::file_exists(Upgrade_PATH . 'init.php')) {
                include(Upgrade_PATH . 'init.php');
                if (function_exists("upgrade_" . $old_build . "_to_" . $new_build)) {
                    $fuction_name = "upgrade_" . $old_build . "_to_" . $new_build;
                    G("FunctionStart");

                    call_user_func($fuction_name);
                    G("FunctionEnd");

                    $message .= "处理升级函数 ,用时 " . G("FunctionStart", "FunctionEnd") . "秒 <br />";

                }
            }


            $this->updateComplete('升级成功' . $target_version_info['build_to'] . "<br />" . $message);
        } else {


            $this->error('升级出错');
        }


    }


    /**
     * 升级完成
     */
    public function updateComplete($message = '')
    {
        $this->assign('action', '升级完成');
        $this->assign('action_name', 'updateComplete');
        $this->assign('message', $message);


        $Storage = new Storage();
        $Storage::connect();

        if ($Storage::has("UpdateLOG")) {
            $update_content = nl2br($Storage::read('UpdateLOG'));
            $this->assign('update_content', $update_content);
        }
        S("checkVersionRes", null);

        $this->display("updatecomplete");

    }


    /**
     *
     */
    public function info()
    {
        if (function_exists('gd_info')) {
            $gd = gd_info();
            $gd = $gd ['GD Version'];
        } else {
            $gd = "不支持";
        }

        $able = get_loaded_extensions();
        $extensions_list = "";
        foreach ($able as $key => $value) {
            if ($key != 0 && $key % 13 == 0) {
                $extensions_list = $extensions_list . '<br />';
            }
            $extensions_list = $extensions_list . "$value&nbsp;&nbsp;";
        }


        $info = array(
            '操作系统' => PHP_OS,
            '主机名IP端口' => $_SERVER ['SERVER_NAME'] . ' (' . $_SERVER ['SERVER_ADDR'] . ':' . $_SERVER ['SERVER_PORT'] . ')',
            '运行环境' => $_SERVER ["SERVER_SOFTWARE"],
            '服务器语言' => getenv("HTTP_ACCEPT_LANGUAGE"),
            'PHP运行方式' => php_sapi_name(),
            '管理员邮箱' => $_SERVER['SERVER_ADMIN'],
            '程序目录' => WEB_ROOT,
            'MYSQL版本' => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
            'GD库版本' => $gd,
            '上传附件限制' => ini_get('upload_max_filesize'),
            'POST方法提交限制' => ini_get('post_max_size'),
            '脚本占用最大内存' => ini_get('memory_limit'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '浮点型数据显示的有效位数' => ini_get('precision'),
            '内存使用状况' => round((@disk_free_space(".") / (1024 * 1024)), 5) . 'M/',
            '已用/总磁盘' => round((@disk_free_space(".") / (1024 * 1024 * 1024)), 3) . 'G/' . round(@disk_total_space(".") / (1024 * 1024 * 1024), 3) . 'G',
            '服务器时间' => date("Y年n月j日 H:i:s 秒"),
            '北京时间' => gmdate("Y年n月j日 H:i:s 秒", time() + 8 * 3600),


            '显示错误信息' => ini_get("display_errors") == "1" ? '√' : '×',
            'register_globals' => get_cfg_var("register_globals") == "1" ? '√' : '×',
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? '√' : '×',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×',


        );
        $this->assign('server_info', $info);
        $this->assign('extensions_list', $extensions_list);

        $this->display('info');
    }

    /**
     *
     */
    public function green()
    {
        $DEFAULT_ADMIN_THEME = array('AdminLTE' => 'AdminLTE', 'Metronic' => 'Metronic');

        $this->assign('DEFAULT_ADMIN_THEME', gen_opinion_list($DEFAULT_ADMIN_THEME, get_opinion('DEFAULT_ADMIN_THEME', true, "Metronic")));

        $this->assign('GreenCMS_Version', GreenCMS_Version);
        $this->assign('GreenCMS_Build', GreenCMS_Build);

        $this->display();


    }


    public function sns()
    {
        $this->display();

    }

    public function phpinfo()
    {
        $this->show(phpinfo());
    }


    public function db()
    {

        $this->assign('db_path', DB_Backup_PATH);
        $this->display();


    }


    public function cache()
    {
        $this->assign('HTML_CACHE_ON', (int)get_opinion('HTML_CACHE_ON', true));
  //        $this->assign('DATA_CACHE_TYPE', gen_opinion_list(get_opinion("cache_type"), get_opinion('DATA_CACHE_TYPE', true, "File")));


        $this->display();
    }


    public function bugs()
    {
        $this->display();
    }


    public function bugsHandle()
    {


        $post_info = I('post.');
        $server_info = get_server_info();
        $post_info['server_info'] = $server_info;
        dump($post_info);

        echo json_encode($post_info);


    }


}