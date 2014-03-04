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

    public function add()
    {
//        $data ['option_name'] = 'smtp_user';
//        $data ['option_value'] = '1412128697@qq.com';
        D('Options')->data($data)->add();
    }

    public function index()
    {

        $this->assign('feed_open', get_opinion('feed_open'));
        $this->assign('users_can_register', get_opinion('users_can_register'));
        $this->display();
    }

    public function indexHandle()
    {
        $this->saveConfig();
        $this->success('配置成功');
    }


    public function setEmailConfig()
    {
        $this->assign('send_mail', C('send_mail'));
        $this->display();
    }

    public function setEmailConfigHandle()
    {
        $this->saveConfig();

        $this->success('配置成功');
    }

    public function setSafeConfig()
    {

        $this->assign('db_fieldtype_check', C('db_fieldtype_check'));
        $this->assign('LOG_RECORD', C('LOG_RECORD'));
        $this->assign('SHOW_CHROME_TRACE', C('SHOW_CHROME_TRACE'));

        $this->display();
    }

    public function setSafeConfigHandle()
    {
        $this->saveConfig();

        $this->success('配置成功');
    }

    public function links()
    {
        $this->linklist = D('Links', 'Logic')->getList(1000);

        $this->display();
    }

    public function addlink()
    {

        if (IS_POST) {
            $data = I('post.');
            if ($_FILES['img']['size'] != 0) {


                $config = array(
                    "savePath"   => (Upload_PATH . 'Links/' . date('Y') . '/' . date('m') . '/'),
                    "maxSize"    => 300000, // 单位KB
                    "allowFiles" => array(".jpg", ".png")
                );

                $upload = new \Common\Util\Uploader ("img", $config);

                $info = $upload->getFileInfo();

                $image = new \Think\Image();
                $image->open(WEB_ROOT . $info['url']);
                $image->thumb(200, 150)->save(WEB_ROOT . $info['url']);

                $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $info['url'];

                if ($info["state"] != "SUCCESS") { // 上传错误提示错误信息
                    $this->error('上传失败' . $info['state']);
                } else {
                    unset($data['img']);
                    $data['link_img'] = $img_url;
                }
            }

            if (D('Links', 'Logic')->addLink($data)) {
                $this->success('链接添加成功', U('Admin/System/links'));
            } else {
                $this->error('链接添加失败', U('Admin/System/links'));
            }
        } else {
            $this->assign('imgurl', __ROOT__ . '/Public/share/img/no+image.gif');

            $this->form_url = U('Admin/System/addlink');
            $this->action = '添加链接';
            $this->buttom = '添加';
            $this->display('addlink');
        }
    }

    public function editlink($id)
    {
        if (IS_POST) {
            $data = I('post.');

            if ($_FILES['img']['size'] != 0) {

                $config = array(
                    "savePath"   => (Upload_PATH . 'Links/' . date('Y') . '/' . date('m') . '/'),
                    "maxSize"    => 300000, // 单位KB
                    "allowFiles" => array(".jpg", ".png")
                );

                $upload = new \Common\Util\Uploader ("img", $config);

                $info = $upload->getFileInfo();

                $image = new \Think\Image();
                $image->open(WEB_ROOT . $info['url']);
                $image->thumb(200, 150)->save(WEB_ROOT . $info['url']);

                $img_url = "http://" . $_SERVER['SERVER_NAME'] . str_replace('index.php', '', __APP__) . $info['url'];

                if ($info["state"] != "SUCCESS") { // 上传错误提示错误信息
                    $this->error('上传失败' . $info['state']);
                } else {
                    unset($data['img']);
                    $data['link_img'] = $img_url;
                }
            }

            if (D('Links', 'Logic')->where(array('link_id' => $id))->save($data)
            ) {
                $this->success('链接编辑成功', U('Admin/System/links'));
            } else {
                $this->error('链接编辑失败', U('Admin/System/links'));
            }
        } else {

            $this->form_url = U('Admin/System/editlink', array('id' => $id));
            $link = D('Links', 'Logic')->detail($id);

            $this->assign('imgurl', $link['link_img']);
            $this->assign('link', $link);
            // print_array($this->link);
            $this->action = '编辑链接';
            $this->buttom = '编辑';
            $this->display('addlink');
        }
    }

    public function dellink($id)
    {
        if (D('Links')->del($id)) {
            $this->success('链接删除成功');
        } else {
            $this->error('链接删除失败');
        }
    }

    public function update()
    {
        if (IS_POST) {
            $version = ( int )$_POST ['version'];
            $msg = fopen_url('http://greencms.xjh1994.com/update.php?version=' . $version);
            $data ['version'] = fopen_url('http://greencms.xjh1994.com/update.php?fullversion=1');
            // $msg = 1;
            // $data['version'] = '2.0 Alpha build 20131022';
            $data ['msg'] = $msg != 0 && $msg != 1 ? 2 : $msg;

            $this->ajaxReturn($data, 'JSON');
        } else {
            $this->display();
        }
    }

    public function updateHandle()
    {
        header("ContentType:text/html;charset:utf8");


        if (!$_GET ['backupall'] && !$_GET ['backupall']) {
            $this->error('未选择任何备份目标');
        }

        $date = date('YmdHis');
        $logcontent = 'GreenCMS在线更新日志###';
        $logcontent .= '更新时间:' . date('Y-m-d H:i:s') . '###';
        $logcontent .= '系统原始版本:' . C('SOFT_VERSION') . '###';

        $backup_file = isset ($_GET ['backupall']) ? $_GET ['backupall'] : 0;
        $backupsql = isset ($_GET ['backupsql']) ? $_GET ['backupsql'] : 0;
        $logcontent .= '正在执行系统版本检测...###';
        G('run1');

        $msg = File::readFile('http://greencms.xjh1994.com/update.php?version=' . substr(C('SOFT_VERSION'), -8));
        // $msg = 1;
        $msg = $msg != 0 && $msg != 1 ? 2 : $msg;
        if ($msg == 0)
            //  $this->error('当前系统已经是最新版!');

        $nowversion = File::readFile('http://greencms.xjh1994.com/update.php?fullversion=1');
        // $nowversion = '2.0 Alpha build 20131122';

        if ($msg == 2)
            //  $this->error('更新检测失败!');

        $updateurl = File::readFile('http://greencms.xjh1994.com/update.php?updateurl=1');

        $logcontent .= '系统更新版本:' . $nowversion . '###';
        $logcontent .= '系统版本检测完毕,区间耗时:' . G('run1', 'end1') . 's' . '###';

        // 清理缓存
        $logcontent .= '清理系统缓存...###';
        G('run2');
        $this->clear();
        $logcontent .= '清理系统缓存完毕!,区间耗时:' . G('run2', 'end2') . 's' . ' ###';

        import('@.ORG.PclZip');

        File::makeDir(System_Backup_PATH);
        File::makeDir(System_Backup_PATH . $date);
        if ($backup_file == 1) {
            // 备份整站
            $logcontent .= '开始备份整站内容...###';
            G('run3');
            $backup_all_file = System_Backup_PATH . $date . '/backupall.zip';
            /*
            $zip = new PclZip ($backupallurl);
            $zip->create('App,Data/Backup,Data/DBbackup,Data/Log,install,index.php,admin.php');
            */
            $zip = new \ZipArchive;
            $res = $zip->open($backup_all_file, \ZipArchive::CREATE);
            $zip->addFile(__ROOT__ . 'index.php');
            $zip->close();

            $logcontent .= '成功完成整站数据备份,备份文件路径:<a href=\'' . $backup_all_file . '\'>' . $backup_all_file . '</a>, 区间耗时:' . G('run3', 'end3') . 's' . ' ###';
        }

        if ($backupsql == 1) {
            // 备份数据库
            $logcontent .= '准备执行数据库备份...###';
            G('run4');
            $backupsqlurl = $this->backupsql($date);
            $logcontent .= '成功完成系统数据库备份,备份文件路径:' . $backupsqlurl . ', 区间耗时:' . G('run4', 'end4') . 's' . ' ###';
        }

        // 获取更新包
        $logcontent .= '开始获取远程更新包...###';
        G('run5');
        $path = './Data/Backup/' . $date;
        $updatedzipurl = $path . '/update.zip';
        //File::write_file($updatedzipurl, fopen_url($updateurl));
        $logcontent .= '获取远程更新包成功,更新包路径:<a href=\'' . __ROOT__ . ltrim($updatedzipurl, '.') . '\'>' . $updatedzipurl . '</a>' . '区间耗时:' . G('run5', 'end5') . 's' . '###';

        // 解压缩更新包
        $logcontent .= '更新包解压缩...###';
        G('run6');
        $zip = new PclZip ($updatedzipurl);
        $zip->extract(PCLZIP_OPT_PATH, './');
        $logcontent .= '更新包解压缩成功...' . '区间耗时:' . G('run6', 'end6') . 's' . '###';

        // 更新数据库
        $updatesqlurl = './update.sql';
        if (is_file($updatesqlurl)) {
            $logcontent .= '更新数据库开始...###';
            G('run7');
            if (file_exists($updatesqlurl)) {
                $rs = new Model ();
                $sql = File::read_file($updatesqlurl);
                $sql = str_replace("\r\n", "\n", $sql);
                foreach (explode(";\n", trim($sql)) as $query) {
                    $rs->query(trim($query));
                }
            }
            unlink($updatesqlurl);
            $logcontent .= '更新数据库完毕...' . '区间耗时:' . G('run7', 'end7') . 's' . '###';
        }

        // 系统版本号更新
        G('run8');
        $config = File::read_file(CONF_PATH . '/config_system.php');
        $config = str_replace(C('SOFT_VERSION'), $nowversion, $config);
        File::write_file(CONF_PATH . '/config_system.php', $config);
        $logcontent .= '更新系统版本号,记录更新日志,日志文件路径:<a href=\'' . __ROOT__ . '/Data/Log/' . $date . '/log.txt\'>./Data/Log/' . $date . '/log.txt</a>,';
        $logcontent .= '区间耗时:' . G('run8', 'end8') . 's';

        // 记录更新日志
        File::mk_dir(LOG_PATH);
        File::mk_dir(LOG_PATH . $date);
        File::write_file(LOG_PATH . $date . '/log.txt', $logcontent);

        // 跳转到更新展示页面
        $this->success('更新完毕!', U('Admin/System/over', array(
                                                            "date" => $date
                                                       )));
    }

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
            '剩余空间'                 => round((@disk_free_space(".") / (1024 * 1024)), 2) . 'M',
            '服务器时间'                => date("Y年n月j日 H:i:s"),
            '北京时间'                 => gmdate("Y年n月j日 H:i:s", time() + 8 * 3600),
            '采集函数检测'               => ini_get('allow_url_fopen') ? '支持' : '不支持',
            'register_globals'     => get_cfg_var("register_globals") == "1" ? "ON" : "OFF",
            'magic_quotes_gpc'     => (1 === get_magic_quotes_gpc()) ? 'YES' : 'NO',
            'magic_quotes_runtime' => (1 === get_magic_quotes_runtime()) ? 'YES' : 'NO'
        );
        $this->assign('server_info', $info);

        $this->display('info');
    }


    public function special()
    {

        $this->display();


    }
}