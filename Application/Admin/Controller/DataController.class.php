<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: DataController.class.php
 * User: Timothy Zhang
 * Date: 14-1-26
 * Time: 下午5:25
 */

namespace Admin\Controller;

use Common\Event\MySQLInfoEvent;
use Common\Event\MySQLMaintainEvent;
use Common\Util\File;
use Common\Event\SystemEvent;
use Common\Util\GreenMail;
use Common\Util\GreenMailContent;
use Think\Upload;

/**
 * Class DataController
 * @package Admin\Controller
 */
class DataController extends AdminBaseController
{

    /**
     *
     */
    public function __construct()
    {
        parent::__construct();

    }


    /**
     * 列出系统中所有数据库表信息
     * For MySQL
     */
    public function index()
    {
        $MySQLEvent = new MySQLInfoEvent();

        $this->assign("formUrl", U('Admin/Data/backupHandle'));
        $this->assign("list", $MySQLEvent->getTabs());
        $this->assign("total", $MySQLEvent->getTotalLengthFormated());
        $this->assign("tables", $MySQLEvent->countTabs());
        $this->display();
    }

    /**
     * 备份数据库
     * For MySQL
     */
    public function backupHandle()
    {
        function_exists('set_time_limit') && set_time_limit(0); //防止备份数据过程超时
        @ini_set("memory_limit", '-1');

        if (!IS_POST) $this->error("访问出错啦");

        $type = "手动自动备份";
        $path = DB_Backup_PATH . "/CUSTOM_" . date("Ymd") . "_" . md5(rand(0, 255) . md5(rand(128, 200)) . rand(100, 768));
        $tables = I('POST.table', array());

        $System = new SystemEvent();
        $res = $System->backupDB($type, $tables, $path);

        $this->array2Response($res);

    }

    /**
     * 还原数据库内容
     * For MySQL
     */
    public function restore()
    {
        $MySQLMaintainEvent = new MySQLMaintainEvent();

        $data = $MySQLMaintainEvent->getSqlFilesList();
        $this->assign("list", $data['list']);
        $this->assign("total", $data['size']);
        $this->assign("files", count($data['list']));
        $this->display();
    }

    /**
     * 上传本地数据库内容
     * For MySQL
     */
    public function restorelocal()
    {

        $this->assign('action', '本地数据库恢复');
        $this->assign('action_name', 'restorelocal');

        $this->display();
    }

    public function restorelocalhandle()
    {

        $config = array(
            'rootPath' => DB_Backup_PATH,
            "savePath" => '',
            "maxSize" => 100000000, // 单位B
            "exts" => array('sql'),
            "subName" => array(),
        );

        $upload = new Upload($config);
        $info = $upload->upload();
        if (!$info) { // 上传错误提示错误信息
            $this->error($upload->getError());
        } else { // 上传成功 获取上传文件信息

            $file_path_full = $info['file']['fullpath'];

            //dump($info);die($file_path_full);
            if (File::file_exists($file_path_full)) {
                $this->success("上传成功", U('Admin/Data/restore'));

            } else {
                $this->error('文件不存在');

            }
        }

    }

    /**
     * 执行还原数据库操作
     */
    public function restoreData()
    {

//        ini_set("memory_limit", "256M");
        function_exists('set_time_limit') && set_time_limit(0); //防止备份数据过程超时
        @ini_set("memory_limit", '-1');

//取得需要导入的sql文件
        $files = isset($_SESSION['cacheRestore']) ? $_SESSION['cacheRestore']['files'] : self::getRestoreFiles();
//取得上次文件导入到sql的句柄位置
        $position = isset($_SESSION['cacheRestore']['position']) ? $_SESSION['cacheRestore']['position'] : 0;
        $M = M('User');
        $execute = 0;
        foreach ($files as $fileKey => $sqlFile) {
            $file = DB_Backup_PATH . $sqlFile;
            if (!file_exists($file))
                continue;
            $file = fopen($file, "r");
            $sql = "";
            fseek($file, $position); //将文件指针指向上次位置
            while (!feof($file)) {
                $tem = trim(fgets($file));
//过滤掉空行、以#号注释掉的行、以--注释掉的行
                if (empty($tem) || $tem[0] == '#' || ($tem[0] == '-' && $tem[1] == '-'))
                    continue;
//统计一行字符串的长度
                $end = (int)(strlen($tem) - 1);
//检测一行字符串最后有个字符是否是分号，是分号则一条sql语句结束，否则sql还有一部分在下一行中
                if ($tem[$end] == ";") {
                    $sql .= $tem;
                    // $sql = str_replace("`", "", $sql);
                    $M->query($sql);
                    $sql = "";
                    $execute++;
                    if ($execute > 500) {
                        $_SESSION['cacheRestore']['position'] = ftell($file);
                        $imported = isset($_SESSION['cacheRestore']['imported']) ? $_SESSION['cacheRestore']['imported'] : 0;
                        $imported += $execute;
                        $_SESSION['cacheRestore']['imported'] = $imported;
//                        echo json_encode(array("status" => 1, "info" =>,
//                            "url" => U('Admin/Data/restoreData')));
                        $this->jsonReturn(1, '如果导入SQL文件卷较大(多)导入时间可能需要几分钟甚至更久，请耐心等待导入完成，导入期间请勿刷新本页，当前导入进度：<font color="red">已经导入' . $imported . '条Sql</font>', U('Admin/Data/restoreData'));
                        //, array(randCode() => randCode())
                        exit;
                    }
                } else {
                    $sql .= $tem;
                }
            }
            fclose($file);
            unset($_SESSION['cacheRestore']['files'][$fileKey]);
            $position = 0;
        }
        $time = time() - $_SESSION['cacheRestore']['time'];
        unset($_SESSION['cacheRestore']);
//        echo json_encode(array("status" => 1, "info" => "导入成功，耗时：{$time} 秒钟"));

        $SystemEvent = new SystemEvent;
        $SystemEvent->clearCacheAll();

        $this->jsonReturn(1, "导入成功，耗时：{$time} 秒钟");
        // $this->success('导入成功', 'restore');
    }

    /**
     * @return array
     */
    private function getRestoreFiles()
    {
        $_SESSION['cacheRestore']['time'] = time();

        if (empty($_GET['sqlPre']))
            $this->error('错误的请求');
        // die(json_encode(array("status" => 0, "info" => "错误的请求")));
//获取sql文件前缀
        $sqlPre = $_GET['sqlPre'];
        $handle = opendir(DB_Backup_PATH);
        $sqlFiles = array();
        while ($file = readdir($handle)) {
//获取以$sqlPre为前缀的所有sql文件
            if (preg_match('#\.sql$#i', $file) && preg_match('#' . $sqlPre . '#i', $file))
                $sqlFiles[] = $file;
        }
        closedir($handle);
        if (count($sqlFiles) == 0)
            $this->error('错误的请求，不存在对应的SQL文件');
        // die(json_encode(array("status" => 0, "info" => "错误的请求，不存在对应的SQL文件")));
//将要还原的sql文件按顺序组成数组，防止先导入不带表结构的sql文件
        $files = array();
        foreach ($sqlFiles as $sqlFile) {
            $k = str_replace(".sql", "", str_replace($sqlPre . "_", "", $sqlFile));
            $files[$k] = $sqlFile;
        }
        unset($sqlFiles, $sqlPre);
        ksort($files);
        $_SESSION['cacheRestore']['files'] = $files;
        return $files;
    }

    /**
     * 删除已备份数据库文件
     */
    public function delSqlFiles()
    {
        if (IS_POST) {
            //  $this->checkToken();
            $sql_files = explode(',', $_POST['sqlFiles']);
            if (empty($sql_files) || count($sql_files) == 0 || $_POST['sqlFiles'] == "") {
                $this->jsonReturn(0, "请先选择要删除的sql文件");

            }

            $files = $sql_files;
            foreach ($files as $file) {
                File::delFile(DB_Backup_PATH . $file);
            }

            $this->jsonReturn(1, "已删除：" . implode("、", $files), __URL__);

        }
    }

    /**
     * 将已备份数据库文件通过系统邮箱发送到指定邮箱中
     */
    public function sendSql()
    {
        set_time_limit(0);
        if (IS_POST) {
            header('Content-Type:application/json; charset=utf-8');
            $sqlFiles = explode(',', $_POST['sqlFiles']);

            if (empty($sqlFiles) || count($sqlFiles) == 0 || $_POST['sqlFiles'] == "")
                $this->jsonReturn(0, "请选择要打包的sql文件");


            $files = isset($_SESSION['cacheSendSql']['files']) ? $_SESSION['cacheSendSql']['files'] : self::getSqlFilesGroups();
            $to = $_SESSION['cacheSendSql']['to'];
            $sum = $_SESSION['cacheSendSql']['count'];


            $zipOut = "sqlBackup.zip";
            if ($zip_res = File::zip($sqlFiles, $zipOut, WEB_CACHE_PATH)) {

                //$res = send_mail($to, "", "数据库备份", "网站：<b>" . get_opinion('title') . "</b> 数据文件备份", WEB_CACHE_PATH . $zipOut); //

                $GreenMailContent = new GreenMailContent();
                $GreenMailContent->to = $to;
                $GreenMailContent->subject = get_opinion('title') . date("Y-m-d") . "数据库备份";
                $GreenMailContent->body = "网站：<b>" . get_opinion('title') . "</b> 数据文件备份.生成时间:" . date("Y-m-d");
                $GreenMailContent->attachment = WEB_CACHE_PATH . $zipOut;

                $GreenMail = new GreenMail();
                $res = $GreenMail->send($GreenMailContent);


            } else {

                $this->jsonReturn(0, "发送失败");
            }


            File::delAll(WEB_CACHE_PATH . $zipOut); //删除已发送附件

            $time = time() - $_SESSION['cacheSendSql']['time'];
            unset($_SESSION['cacheSendSql']);

            if ($res['statue'] == true) {
                $this->jsonReturn(1, "sql文件已发送到你的邮件，请注意查收<br/>耗时：$time 秒");
            } else {
                $this->jsonReturn(0, $res['info']);
            }
        }
        $this->display();
    }

    /**
     * 将待通过邮件发送的sql文件按卷标升序排序并按sql文件大小分组为多个待压缩组
     * @return array
     */
    private function getSqlFilesGroups()
    {
        $_SESSION['cacheSendSql']['time'] = time();

        $sql = explode(',', $_POST['sqlFiles']);

        if (empty($sql) || count($sql) == 0 || $_POST['sqlFiles'] == "") {
            $this->jsonReturn(0, "请选择要发送到邮件的sql文件");
        }

        $files = $sql;

        if (is_email($_POST['to'])) {
            $_SESSION['cacheSendSql']['to'] = $_POST['to'];
        } else {
            $this->jsonReturn(0, "接受SQL文件的邮件地址格式错误");
        }

        $sqlFiles = array();
        foreach ($files as $sqlFile) {
            $k = explode("_", $sqlFile);
            $k = explode(".", end($k));
            $sqlFiles[$k[0]] = $sqlFile;
        }

        unset($k);
        ksort($sqlFiles); //对数组按key排序

        $temSize = 0;
        $n = 1;
        //计算待发送到邮件的附件大小，并分成多个压缩文件组
        foreach (array($sqlFiles) as $key => $value) {
            $path = DB_Backup_PATH . $key;
            if (file_exists($path)) {
                if (filesize($path) > 52428800) { //50*1024*1024=52428800
                    $files[$n][] = $key;
                    $temSize = 0;
                    $n++;
                } else {
                    $temSize += filesize($path);
                    if ($temSize < 52428800) {
                        $files[$n][] = $key;
                    } else {
                        $temSize = 0;
                        $temSize += filesize($path);
                        $files[$n][] = $key;
                        $n++;
                    }
                }
            }
        }
        unset($_POST, $sqlFiles, $temSize);
        $_SESSION['cacheSendSql']['count'] = count($files);

        //TODO 测试分卷发送
        return $_SESSION['cacheSendSql']['files'] = $files;
    }

    /**
     * 打包sql文件
     */
    public function zipSql()
    {
        if (IS_POST) {
            header('Content-Type:application/json; charset=utf-8');
            $sqlFiles = explode(',', $_POST['sqlFiles']);
            if (empty($sqlFiles) || count($sqlFiles) == 0 || $_POST['sqlFiles'] == "")
                $this->jsonReturn(0, "请选择要打包的sql文件");

            $files = $sqlFiles;
            $toZip = array();
            foreach ($files as $file) {
                $tem = explode("_", $file);
                unset($tem[count($tem) - 1]);
                $toZip[implode("_", $tem)][] = $file;
            }
            foreach ($toZip as $zipOut => $files) {
                if (File::zip($files, $zipOut . ".zip", DB_Backup_PATH . "Zip/")) {
                    /*foreach ($files as $file) {
                        delDirAndFile(DB_Backup_PATH . $file);
                    }*/
                } else {
                    //die(json_encode(array("status" => 2, "info" => "打包过程出现错误")));
                    $this->jsonReturn(2, "打包过程出现错误");
                }
            }
            $this->jsonReturn(1, "打包的sql文件成功，本次打包" . count($toZip) . "个zip文件", U('Admin/Data/zipList'));
        }
    }

    /**
     * 列出以打包sql文件
     */
    public function zipList()
    {
        $data = $this->getZipFilesList();
        $this->assign("list", $data['list']);
        $this->assign("total", $data['size']);
        $this->assign("files", count($data['list']));
        $this->display();
    }


    /**
     * @return array
     */
    private function getZipFilesList()
    {
        $list = array();
        $size = 0;
        $handle = opendir(DB_Backup_PATH . "Zip/");

        while ($file = readdir($handle)) {
            if ($file != "." && $file != "..") {
                $tem = array();
                $tem['file'] = $file;
                $_size = filesize(DB_Backup_PATH . "Zip/$file");
                $tem['size'] = File::byteFormat($_size);
                $tem['time'] = date("Y-m-d H:i:s", filectime(DB_Backup_PATH . "Zip/$file"));
                $size += $_size;
                $list[] = $tem;
            }
        }
        return array("list" => $list, "size" => File::byteFormat($size));
    }


    /**
     * @return bool
     */
    function unzipSqlfile()
    {
        if (!IS_POST)
            return false;


        $zipFiles = explode(',', $_POST['zipFiles']);

        $_SESSION['unzip']['time'] = time();
        if (empty($zipFiles) || count($zipFiles) == 0 || $_POST['zipFiles'] == "")
            $this->jsonReturn(0, "请选择要解压的zip文件");

        $files = $zipFiles;
//      $_SESSION['unzip']['files'] = $files;
//      $_SESSION['unzip']['count'] = count($files);

        foreach ($files as $k => $file) {
            File::unzip($file);
            /* if (count($files) > 1) {
                echo json_encode(array("status" => 1, "info" => "正在解压缩，请勿刷新本页<br />当前已经解压完{$file}", "url" => U('Data/unzipSqlfile', array(randCode() => randCode()))));
                unset($_SESSION['unzip']['files'][$k]);
                exit;
            } */
        }

        $time = time() - $_SESSION['unzip']['time'];
        unset($_SESSION['unzip']);
        $this->jsonReturn(1, "已解压完成&nbsp;&nbsp;耗时：$time 秒", U('Admin/Data/restore'));

    }

    /**
     * 删除已备份数据库文件
     */
    public function delZipFiles()
    {
        if (IS_POST) {
            $zipFiles = explode(',', $_POST['zipFiles']);
            if (empty($zipFiles) || count($zipFiles) == 0 || $_POST['zipFiles'] == "") {
                $this->jsonReturn(0, "请先选择要删除的zip文件");
            }
            $files = $zipFiles;
            foreach ($files as $file) {

                File::delFile(DB_Backup_PATH . "Zip/" . $file);
                //$dir = new Dir(DB_Backup_PATH . "Zip/");
                //$dir->delDirAndFile(DB_Backup_PATH . "Zip/" . $file);
            }
            echo json_encode(array("status" => 1, "info" => "已删除：" . implode("、", $files), "url" => __URL__ . "/zipList?" . time()));
        }
    }

    /**
     *
     */
    public function downFile()
    {
        if (empty($_GET['file']) || empty($_GET['type']) || !in_array($_GET['type'], array("zip", "sql"))) {
            $this->error("下载地址不存在");
        }

        if (strpos(I("get.file"), "..") !== false) {
            $this->error("非法请求");
        }

        $path = array("zip" => DB_Backup_PATH . "Zip/", "sql" => DB_Backup_PATH);
        $filePath = $path[$_GET['type']] . $_GET['file'];
        if (!file_exists($filePath)) {
            $this->error("该文件不存在，可能是被删除");
        }
        $filename = basename($filePath);
        header("Content-type: application/octet-stream");
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header("Content-Length: " . filesize($filePath));
        readfile($filePath);
    }


    /**
     * For Mysql only
     */
    public function repair()
    {
        if (get_opinion('DB_TYPE') != 'mysql' && get_opinion('DB_TYPE') != 'mysqli') {
            $this->error('当前数据库类型不被支持');
        }

        $M = M();
        if (IS_POST) {

            $System = new \Common\Event\SystemEvent();
            $System->postIntegrity();

            if (empty($_POST['table']) || count($_POST['table']) == 0) {
                $this->jsonReturn(0, "请选择要处理的表");
            }
            $table = implode(',', $_POST['table']);
            if ($_POST['act'] == 'repair') {
                if ($M->query("REPAIR TABLE {$table} "))
                    $this->jsonReturn(1, "修复表成功", U('Admin/Data/repair'));
            } elseif ($_POST['act'] == 'optimize') {
                if ($M->query("OPTIMIZE TABLE $table"))
                    $this->jsonReturn(1, "优化表成功", U('Admin/Data/repair'));
            }
            $this->jsonReturn(0, "请选择操作");
        } else {
            $tabs = $M->query('SHOW TABLE STATUS');
            $total_size = array('table' => 0, 'index' => 0, 'data' => 0, 'free' => 0);
            $tables = array();
            foreach ($tabs as $k => $table) {
                $table['size'] = File::byteFormat($table['Data_length'] + $table['Index_length']);
                $total_size['table'] += $table['Data_length'] + $table['Index_length'];
                $total_size['data'] += $table['Data_length'];
                $table['Data_length'] = File::byteFormat($table['Data_length']);
                $total_size['index'] += $table['Index_length'];
                $table['Index_length'] = File:: byteFormat($table['Index_length']);
                $total_size['free'] += $table['Data_free'];
                $table['Data_free'] = File::byteFormat($table['Data_free']);
                $tables[] = $table;
            }

            $total_size['table'] = File::byteFormat($total_size['table']);


            $total_size['free'] = File::byteFormat($total_size['free']);
            $total_size['table'] = File::byteFormat($total_size['table']);
            $total_size['data'] = File::byteFormat($total_size['data']);
            $total_size['index'] = File::byteFormat($total_size['index']);


            $this->assign("list", $tables);
            $this->assign("totalsize", $total_size);
            $this->display();
        }
    }


    /**
     *
     */
    public function clear()
    {
        $caches = array(
            "HTMLCache" => array(
                "name" => "网站HTML缓存文件",
                "path" => RUNTIME_PATH . "HTML",
                //"size" => $Dir->size(RUNTIME_PATH . "Cache"),
                "size" => File::realSize(RUNTIME_PATH . "HTML"),
            ),
            "HomeCache" => array(
                "name" => "网站缓存文件",
                "path" => RUNTIME_PATH . "Cache",
                //"size" => $Dir->size(RUNTIME_PATH . "Cache"),
                "size" => File::realSize(RUNTIME_PATH . "Cache"),
            ),
            "HomeData" => array(
                "name" => "网站数据库字段缓存文件",
                "path" => RUNTIME_PATH . "Data",
                "size" => File::realSize(RUNTIME_PATH . "Data"),
            ),
            "AdminLog" => array(
                "name" => "网站日志文件",
                "path" => LOG_PATH,
                "size" => File::realSize(LOG_PATH),
            ),
            "AdminTemp" => array(
                "name" => "网站临时文件",
                "path" => RUNTIME_PATH . "Temp",
                "size" => File::realSize(RUNTIME_PATH . "Temp"),
            ),
            "Homeruntime" => array(
                "name" => "网站~runtime.php缓存文件",
                "path" => RUNTIME_PATH . "common~runtime.php",
                //  "size" => $Dir->realsize(RUNTIME_PATH . "~runtime.php"),
                "size" => File::realSize(RUNTIME_PATH . "common~runtime.php"),
            )
        );

        // p($_POST['cache']);die;
        if (IS_POST) {

            $paths = $_POST ['cache'];
            foreach ($paths as $path) {
                if (isset ($caches [$path])) {
                    $res = File::delAll($caches [$path] ['path'], true);
                }
            }
            $SystemEvent = new SystemEvent;
            $SystemEvent->clearCacheAll();


            $this->success("清除成功");
        } else {

            $this->assign("caches", $caches);
            $this->display();
        }
    }


    public function clearAll()
    {
        $SystemEvent = new SystemEvent;
        $SystemEvent->clearCacheAll();
        $this->success("清除成功");
    }


}