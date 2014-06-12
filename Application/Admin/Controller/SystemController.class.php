<?php
/**
 * Created by Green Studio.
 * File: SystemController.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 下午5:28
 */

namespace Admin\Controller;

use Common\Event\SystemEvent;
use Common\Event\UpdateEvent;
use Common\Util\File;

/**
 * Class SystemController
 * @package Admin\Controller
 */
class SystemController extends AdminBaseController
{

    //TODO Email mail()

    /**
     *
     */
    public function index()
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


        $this->assign('auto_channel', get_opinion('auto_channel'));
        $this->assign('api_open', get_opinion('api_open'));
        $this->assign('feed_open', get_opinion('feed_open'));
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
        $url_mode = array(0 => '普通模式', 1 => 'PATHINFO模式', 2 => 'REWRITE模式', 3 => '兼容模式');
        $home_post_model = array('native' => '原生模式',
            'year/month/day/post_id' => '年/月/日/post_id',
            'year/month/day/post_name' => '年/月/日/post_name',
            'year/month/post_id' => '年/月/post_id',
            'year/month/post_name' => '年/月/post_name',
            'year/post_id' => '年/post_id',
            'year/post_name' => '年/post_name');

        $home_tag_model = array('native' => '原生模式', 'slug' => 'slug短语');
        $home_cat_model = array('native' => '原生模式', 'slug' => 'slug短语');

        $this->assign('home_post_model', gen_opinion_list($home_post_model, get_opinion('home_post_model', true)));
        $this->assign('home_tag_model', gen_opinion_list($home_tag_model, get_opinion('home_tag_model', true)));
        $this->assign('home_cat_model', gen_opinion_list($home_cat_model, get_opinion('home_cat_model', true)));

        $this->assign('url_mode', gen_opinion_list($url_mode, (int)get_opinion('home_url_model', true)));

        $this->display();
    }


    /**
     *
     */
    public function email()
    {
        $this->assign('send_mail', C('send_mail'));
        $this->display();
    }


    /**
     *
     */
    public function safe()
    {

        $this->assign('vertify_code', get_opinion('vertify_code', true));
        $this->assign('db_fieldtype_check', C('db_fieldtype_check'));
        $this->assign('LOG_RECORD', C('LOG_RECORD'));
        $this->assign('SHOW_CHROME_TRACE', C('SHOW_CHROME_TRACE'));
        $this->assign('SHOW_PAGE_TRACE', C('SHOW_PAGE_TRACE'));

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


        if (IS_POST) {
            $version = I('post.version');
            $url = Server_API . 'api/update/' . $version . '/';
            $json = json_decode(file_get_contents($url), true);

            $this->assign('versions', $json);
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


        $version = I('get.version');
        $now_version = get_opinion('software_build', true);
        $url = Server_API . 'api/update/' . $now_version . '/';
        $json = json_decode(file_get_contents($url), true);

        $target_version_info = ($json['file_list'][$version]);
        if (!empty($target_version_info)) {
            File::mkDir(WEB_CACHE_PATH);
            $file_downloaded = WEB_CACHE_PATH . $target_version_info['file_name'];
            $file = file_get_contents($target_version_info['file_url']);
            File::writeFile($file_downloaded, $file);

            //todo 系统备份
            $System = new SystemEvent();
            //$System->backupFile();

            $zip = new \ZipArchive; //新建一个ZipArchive的对象
            if ($zip->open($file_downloaded) === true) {
                $zip->extractTo(WEB_ROOT); //假设解压缩到在当前路径下/文件夹内
                $zip->close(); //关闭处理的zip文件
                File::delFile($file_downloaded);
                $System->clearCacheAll();
            } else {
                $this->error('文件损坏');
            }

            $old_build = get_opinion('software_build');
            $new_build = $target_version_info['build_to'];

            set_opinion('software_version', $target_version_info['version_to']);
            set_opinion('software_build', $target_version_info['build_to']);

            if (File::file_exists(Upgrade_PATH . 'init.php')) {
                include(Upgrade_PATH . 'init.php');
                if (function_exists("upgrade_" . $old_build . "_to_" . $new_build)) {
                    $fuction_name = "upgrade_" . $old_build . "_to_" . $new_build;
                    call_user_func($fuction_name);

                }
            }

            $this->success('升级成功' . $target_version_info['build_to'],U('Admin/Index/updateComplete'));

        } else {


            $this->error('升级出错');
        }


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
        $info = array(
            '操作系统' => PHP_OS,
            '主机名IP端口' => $_SERVER ['SERVER_NAME'] . ' (' . $_SERVER ['SERVER_ADDR'] . ':' . $_SERVER ['SERVER_PORT'] . ')',
            '运行环境' => $_SERVER ["SERVER_SOFTWARE"],
            'PHP运行方式' => php_sapi_name(),
            '程序目录' => WEB_ROOT,
            'MYSQL版本' => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
            'GD库版本' => $gd,
            // 'MYSQL版本' => mysql_get_server_info(),
            '上传附件限制' => ini_get('upload_max_filesize'),
            '执行时间限制' => ini_get('max_execution_time') . "秒",
            '内存使用状况' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '硬盘使用状况' => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '服务器时间' => date("Y年n月j日 H:i:s"),
            '北京时间' => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),

            'register_globals' => get_cfg_var("register_globals") == "1" ? '√' : '×',
            'magic_quotes_gpc' => (1 === get_magic_quotes_gpc()) ? '√' : '×',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×',
        );
        $this->assign('server_info', $info);

        $this->display('info');
    }

    /**
     *
     */
    public function green()
    {
        $DEFAULT_ADMIN_THEME = array('AdminLTE' => 'AdminLTE', 'Metronic' => 'Metronic');

        $this->assign('DEFAULT_ADMIN_THEME', gen_opinion_list($DEFAULT_ADMIN_THEME, get_opinion('DEFAULT_ADMIN_THEME', true, "Metronic")));


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

}