<?php
/**
 * Created by Green Studio.
 * File: SystemController.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 下午5:28
 */

namespace Admin\Controller;

use Common\Util\File;

class SystemController extends AdminBaseController
{
    //TODO Upgrade
    //TODO Email mail()

    public function index()
    {

        $this->assign('users_can_register', get_opinion('users_can_register'));
        $this->display();
    }

    public function post()
    {
        $this->assign('feed_open', get_opinion('feed_open'));
        $this->display();
    }


    public function saveHandle()
    {
        $this->saveConfig();
        $this->success('配置成功');
    }

    public function kvset()
    {


        $this->display();
    }

    public function kvsetHandle()
    {
        $this->saveKv();
        $this->success('配置成功');
    }


    public function url()
    {
        //普通模式0, PATHINFO模式1, REWRITE模式2, 兼容模式3
        $url_mode = array(0 => '普通模式', 1 => 'PATHINFO模式', 2 => 'REWRITE模式', 3 => '兼容模式');
        $home_post_model = array('native'                   => '原生模式',
                                 'year/month/day/post_id'   => '年/月/日/post_id',
                                 'year/month/day/post_name' => '年/月/日/post_name',
                                 'year/month/post_id'       => '年/月/post_id',
                                 'year/month/post_name'     => '年/月/post_name',
                                 'year/post_id'             => '年/post_id',
                                 'year/post_name'           => '年/post_name');

        $home_tag_model = array('native' => '原生模式', 'slug' => 'slug短语');
        $home_cat_model = array('native' => '原生模式', 'slug' => 'slug短语');

        $this->assign('home_post_model', gen_opinion_list($home_post_model, get_opinion('home_post_model', true)));
        $this->assign('home_tag_model', gen_opinion_list($home_tag_model, get_opinion('home_tag_model', true)));
        $this->assign('home_cat_model', gen_opinion_list($home_cat_model, get_opinion('home_cat_model', true)));

        $this->assign('url_mode', gen_opinion_list($url_mode, (int)get_opinion('home_url_model', true)));

        $this->display();
    }


    public function email()
    {
        $this->assign('send_mail', C('send_mail'));
        $this->display();
    }


    public function safe()
    {
        $this->assign('db_fieldtype_check', C('db_fieldtype_check'));
        $this->assign('LOG_RECORD', C('LOG_RECORD'));
        $this->assign('SHOW_CHROME_TRACE', C('SHOW_CHROME_TRACE'));
        $this->assign('SHOW_PAGE_TRACE', C('SHOW_PAGE_TRACE'));

        $this->display();
    }

    public function checkupdate()
    {
        $Update = new \Common\Event\UpdateEvent();
        $Update->check();


    }

    public function update()
    {


        if (IS_POST) {
            $version = I('post.version');
            $url = Server_API . 'api/update/' . $version;
            $json = json_decode(file_get_contents($url), true);

            $this->assign('versions', $json);
            $this->assign('action', '选择升级版本');
            $this->display('update_s2');

        } else {
            $this->display();


        }
    }

    public function updateHandle()
    {

        $version = I('get.version');
        $now_version = get_opinion('software_build', true);
        $url = Server_API . 'api/update/' . $now_version;
        $json = json_decode(file_get_contents($url), true);
        $target_version_info = ($json['file_list'][$version]);
        if (!empty($target_version_info)) {

            $file_downloaded = WEB_CACHE_PATH . $target_version_info['file_name'];
            $file = file_get_contents($target_version_info['file_url']);
            File::writeFile($file_downloaded, $file);

            //todo 系统备份
            $System = new \Common\Event\SystemEvent();
            //$System->backupFile();

            $zip = new \ZipArchive; //新建一个ZipArchive的对象
            if ($zip->open($file_downloaded) === true) {
                $zip->extractTo(WEB_ROOT); //假设解压缩到在当前路径下images文件夹内
                $zip->close(); //关闭处理的zip文件
                File::delFile($file_downloaded);

            } else {
                $this->error('文件损坏');
            }


            set_opinion('software_version', $target_version_info['version_to']);
            set_opinion('software_build', $target_version_info['build_to']);
            $this->success('升级成功' . $target_version_info['build_to']);

        } else {


            $this->error('升级出错');
        }


    }

//
//    public function updateHandle()
//    {
//        header("ContentType:text/html;charset:utf8");
//
//
//        if (!$_GET ['backupall'] && !$_GET ['backupall']) {
//            $this->error('未选择任何备份目标');
//        }
//
//        $date = date('YmdHis');
//        $logcontent = 'GreenCMS在线更新日志###';
//        $logcontent .= '更新时间:' . date('Y-m-d H:i:s') . '###';
//        $logcontent .= '系统原始版本:' . C('SOFT_VERSION') . '###';
//
//        $backup_file = isset ($_GET ['backupall']) ? $_GET ['backupall'] : 0;
//        $backupsql = isset ($_GET ['backupsql']) ? $_GET ['backupsql'] : 0;
//        $logcontent .= '正在执行系统版本检测...###';
//        G('run1');
//
//        $msg = File::readFile('http://greencms.xjh1994.com/update.php?version=' . substr(C('SOFT_VERSION'), -8));
//        // $msg = 1;
//        $msg = $msg != 0 && $msg != 1 ? 2 : $msg;
//        if ($msg == 0)
//            //  $this->error('当前系统已经是最新版!');
//
//        $nowversion = File::readFile('http://greencms.xjh1994.com/update.php?fullversion=1');
//        // $nowversion = '2.0 Alpha build 20131122';
//
//        if ($msg == 2)
//            //  $this->error('更新检测失败!');
//
//        $updateurl = File::readFile('http://greencms.xjh1994.com/update.php?updateurl=1');
//
//        $logcontent .= '系统更新版本:' . $nowversion . '###';
//        $logcontent .= '系统版本检测完毕,区间耗时:' . G('run1', 'end1') . 's' . '###';
//
//        // 清理缓存
//        $logcontent .= '清理系统缓存...###';
//        G('run2');
//        $this->clear();
//        $logcontent .= '清理系统缓存完毕!,区间耗时:' . G('run2', 'end2') . 's' . ' ###';
//
//        import('@.ORG.PclZip');
//
//        File::makeDir(System_Backup_PATH);
//        File::makeDir(System_Backup_PATH . $date);
//        if ($backup_file == 1) {
//            // 备份整站
//            $logcontent .= '开始备份整站内容...###';
//            G('run3');
//            $backup_all_file = System_Backup_PATH . $date . '/backupall.zip';
//            /*
//            $zip = new PclZip ($backupallurl);
//            $zip->create('App,Data/Backup,Data/DBbackup,Data/Log,install,index.php,admin.php');
//            */
//            $zip = new \ZipArchive;
//            $res = $zip->open($backup_all_file, \ZipArchive::CREATE);
//            $zip->addFile(__ROOT__ . 'index.php');
//            $zip->close();
//
//            $logcontent .= '成功完成整站数据备份,备份文件路径:<a href=\'' . $backup_all_file . '\'>' . $backup_all_file . '</a>, 区间耗时:' . G('run3', 'end3') . 's' . ' ###';
//        }
//
//        if ($backupsql == 1) {
//            // 备份数据库
//            $logcontent .= '准备执行数据库备份...###';
//            G('run4');
//            $backupsqlurl = $this->backupsql($date);
//            $logcontent .= '成功完成系统数据库备份,备份文件路径:' . $backupsqlurl . ', 区间耗时:' . G('run4', 'end4') . 's' . ' ###';
//        }
//
//        // 获取更新包
//        $logcontent .= '开始获取远程更新包...###';
//        G('run5');
//        $path = './Data/Backup/' . $date;
//        $updatedzipurl = $path . '/update.zip';
//        //File::write_file($updatedzipurl, fopen_url($updateurl));
//        $logcontent .= '获取远程更新包成功,更新包路径:<a href=\'' . __ROOT__ . ltrim($updatedzipurl, '.') . '\'>' . $updatedzipurl . '</a>' . '区间耗时:' . G('run5', 'end5') . 's' . '###';
//
//        // 解压缩更新包
//        $logcontent .= '更新包解压缩...###';
//        G('run6');
//        $zip = new PclZip ($updatedzipurl);
//        $zip->extract(PCLZIP_OPT_PATH, './');
//        $logcontent .= '更新包解压缩成功...' . '区间耗时:' . G('run6', 'end6') . 's' . '###';
//
//        // 更新数据库
//        $updatesqlurl = './update.sql';
//        if (is_file($updatesqlurl)) {
//            $logcontent .= '更新数据库开始...###';
//            G('run7');
//            if (file_exists($updatesqlurl)) {
//                $rs = new Model ();
//                $sql = File::read_file($updatesqlurl);
//                $sql = str_replace("\r\n", "\n", $sql);
//                foreach (explode(";\n", trim($sql)) as $query) {
//                    $rs->query(trim($query));
//                }
//            }
//            unlink($updatesqlurl);
//            $logcontent .= '更新数据库完毕...' . '区间耗时:' . G('run7', 'end7') . 's' . '###';
//        }
//
//        // 系统版本号更新
//        G('run8');
//        $config = File::read_file(CONF_PATH . '/config_system.php');
//        $config = str_replace(C('SOFT_VERSION'), $nowversion, $config);
//        File::write_file(CONF_PATH . '/config_system.php', $config);
//        $logcontent .= '更新系统版本号,记录更新日志,日志文件路径:<a href=\'' . __ROOT__ . '/Data/Log/' . $date . '/log.txt\'>./Data/Log/' . $date . '/log.txt</a>,';
//        $logcontent .= '区间耗时:' . G('run8', 'end8') . 's';
//
//        // 记录更新日志
//        File::mk_dir(LOG_PATH);
//        File::mk_dir(LOG_PATH . $date);
//        File::write_file(LOG_PATH . $date . '/log.txt', $logcontent);
//
//        // 跳转到更新展示页面
//        $this->success('更新完毕!', U('Admin/System/over', array("date" => $date)));
//    }

    public function over()
    {
        $date = isset ($_GET ['date']) ? $_GET ['date'] : 0;
        $dir = SystemBackDir . $date;
        if (!is_dir($dir))
            $this->error('未检测到更新内容!');

        $content = File::read_file(LOG_PATH . $date . '/log.txt');
        $this->assign('log', explode('###', $content));
        $this->action = '更新结果';
        $this->clear();
        $this->display();
    }

    public function backupsql($date)
    {
        // 数据备份
        $rs = new Model ();
        $list = $rs->query("SHOW TABLES FROM " . "`" . C('DB_NAME') . "`");
        $filesize = 2048;
        $file = __ROOT__ . '/Data/DBbackup/';
        $random = mt_rand(1000, 9999);
        $sql = '';
        $p = 1;
        $url = '';
        foreach ($list as $k => $v) {
            $table = current($v);
            // 仅备份当前系统的数据库表
            $prefix = C('DB_PREFIX');
            if (substr($table, 0, strlen($prefix)) == $prefix) {
                $rs = D(str_replace(C('DB_PREFIX'), '', $table));
                $array = $rs->select();
                $sql .= "TRUNCATE TABLE `$table`;\n";
                foreach ($array as $value) {
                    $sql .= $this->insertsql($table, $value);
                    if (strlen($sql) >= $filesize * 1000) {
                        $filename = $file . $date . '_' . date('Ymd') . '_' . $random . '_' . $p . '.sql';
                        $url .= "<a href='{$filename}'>" . $filename . '</a>,';
                        File::write_file($filename, $sql);
                        $p++;
                        $sql = '';
                    }
                }
            }
        }
        if (!empty ($sql)) {
            $filename = $file . $date . '_' . date('Ymd') . '_' . $random . '_' . $p . '.sql';
            $url .= "<a href='{$filename}'>" . $filename . '</a>,';
            File::write_file($filename, $sql);
        }
        return $url;
    }

    // 生成SQL备份语句
    public function insertsql($table, $row)
    {
        $sql = "INSERT INTO `{$table}` VALUES (";
        $values = array();
        foreach ($row as $value) {
            $values [] = "'" . mysql_real_escape_string($value) . "'";
        }
        $sql .= implode(', ', $values) . ");\n";
        return $sql;
    }

    // ajax 设置cookie,下次不再自动提醒更新
    public function applycookie()
    {
        cookie('updatenotice', 1);
    }


    public function info()
    {
        if (function_exists('gd_info')) {
            $gd = gd_info();
            $gd = $gd ['GD Version'];
        } else {
            $gd = "不支持";
        }
        $info = array(
            '操作系统'                 => PHP_OS,
            '主机名IP端口'              => $_SERVER ['SERVER_NAME'] . ' (' . $_SERVER ['SERVER_ADDR'] . ':' . $_SERVER ['SERVER_PORT'] . ')',
            '运行环境'                 => $_SERVER ["SERVER_SOFTWARE"],
            'PHP运行方式'              => php_sapi_name(),
            '程序目录'                 => WEB_ROOT,
            'MYSQL版本'              => function_exists("mysql_close") ? mysql_get_client_info() : '不支持',
            'GD库版本'                => $gd,
            // 'MYSQL版本' => mysql_get_server_info(),
            '上传附件限制'               => ini_get('upload_max_filesize'),
            '执行时间限制'               => ini_get('max_execution_time') . "秒",
            '内存使用状况'               => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '硬盘使用状况'               => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '服务器时间'                => date("Y年n月j日 H:i:s"),
            '北京时间'                 => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),

            'register_globals'     => get_cfg_var("register_globals") == "1" ? '√' : '×',
            'magic_quotes_gpc'     => (1 === get_magic_quotes_gpc()) ? '√' : '×',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? '√' : '×',
        );
        $this->assign('server_info', $info);

        $this->display('info');
    }

    public function green()
    {

        $this->display();


    }


}