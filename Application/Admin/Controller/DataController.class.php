<?php
/**
 * Created by Green Studio.
 * File: DataController.class.php
 * User: TianShuo
 * Date: 14-1-26
 * Time: 下午5:25
 */

namespace Admin\Controller;
use Common\Util\File;


/**
 * Class DataController
 * @package Admin\Controller
 */
class DataController extends AdminBaseController
{

    public function __construct()
    {
        parent::__construct();
        $this->is_sae();

    }


    public function db()
    {

        $this->assign('db_path', DB_Backup_PATH);
        $this->display();


    }

    public function dbHandle()
    {
        $this->saveConfig();
        $this->success('配置成功');

    }


    /**
     * 列出系统中所有数据库表信息
     * For MySQL
     */
    public function index()
    {
        $tabs = M()->query('SHOW TABLE STATUS');
        $total_length = 0;
        foreach ($tabs as $key => $value) {
            $tabs[$key]['size'] = File::byteFormat($value['Data_length'] + $value['Index_length']);
            $total_length += $value['Data_length'] + $value['Index_length'];
        }

        $this->assign("formUrl", U('Admin/Data/backupHandle'));
        $this->assign("list", $tabs);
        $this->assign("total", File::byteFormat($total_length));
        $this->assign("tables", count($tabs));
        $this->display();
    }

    /**
     * 备份数据库
     * For MySQL
     */
    public function backupHandle()
    {
        if (!IS_POST) $this->error("访问出错啦");
        $type = "手动自动备份";
        $path = DB_Backup_PATH . "/CUSTOM_" . date("Ymd") . "_" . md5(rand(0, 255) . md5(rand(128, 200)) . rand(100, 768));
        $tables = empty($_POST['table']) ? array() : $_POST['table'];
        $System = new \Common\Event\SystemEvent();
        //$System->backupFile(); //test ok~
        $res = $System->backupDB($type, $tables, $path);

        if ($res['status'] == 1)
            $this->success($res['info'], $res['url']);

    }

    /**
     * 还原数据库内容
     * For MySQL
     */
    public function restore()
    {
        $MySQLLogic = new \Admin\Logic\MySQLLogic();

        $data = $MySQLLogic->getSqlFilesList();
        $this->assign("list", $data['list']);
        $this->assign("total", $data['size']);
        $this->assign("files", count($data['list']));
        $this->display();
    }

    /**
     * 读取要导入的sql文件列表并排序后插入SESSION中
     */
    /*static*/
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
     * 执行还原数据库操作
     */
    public function restoreData()
    {
        //TODO 需要测试
//        ini_set("memory_limit", "256M");
        function_exists('set_time_limit') && set_time_limit(0); //防止备份数据过程超时
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
                        echo json_encode(array("status" => 1, "info" => '如果导入SQL文件卷较大(多)导入时间可能需要几分钟甚至更久，请耐心等待导入完成，导入期间请勿刷新本页，当前导入进度：<font color="red">已经导入' . $imported . '条Sql</font>',
                                               "url" => U('Admin/Data/restoreData')));//, array(randCode() => randCode())
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
        echo json_encode(array("status" => 1, "info" => "导入成功，耗时：{$time} 秒钟"));
        // $this->success('导入成功', 'restore');
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
                $this->json_return(0, "请先选择要删除的sql文件");

            }

            $files = $sql_files;
            foreach ($files as $file) {
                File::delFile(DB_Backup_PATH . $file);
            }
           // echo json_encode(array("status" => 1, "info" => "已删除：" . implode("、", $files), "url" => __URL__));
            $this->json_return( 1,  "已删除：" . implode("、", $files),__URL__);

        }
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
            $this->json_return(0, "请选择要发送到邮件的sql文件");
        }

        $files = $sql;

        if (is_email($_POST['to'])) {
            $_SESSION['cacheSendSql']['to'] = $_POST['to'];
        } else {
            $this->json_return(0, "接受SQL文件的邮件地址格式错误");
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
     * 将已备份数据库文件通过系统邮箱发送到指定邮箱中
     */
    public function sendSql()
    {
        set_time_limit(0);
        if (IS_POST) {
            header('Content-Type:application/json; charset=utf-8');
            $sqlFiles = explode(',', $_POST['sqlFiles']);


            $files = isset($_SESSION['cacheSendSql']['files']) ? $_SESSION['cacheSendSql']['files'] : self::getSqlFilesGroups();
            $to = $_SESSION['cacheSendSql']['to'];
            $sum = $_SESSION['cacheSendSql']['count'];

            //$tempstr=$to. "数据库备份" . "网站：<b>" .C('title'). "</b> 数据文件备份";

            // $filesready = explode(',', $files);

            $zipOut = "sqlBackup.zip";
            if (File::zip($sqlFiles, $zipOut)) {
                //TODO send_mail
                $res = send_mail($to, "", "数据库备份", "网站：<b>" . C('title') . "</b> 数据文件备份", WEB_CACHE_PATH . $zipOut); //

            }


            //  foreach ($files as $k => $zipFiles) {
//                 $zipOut = $sum == 1 ? "sqlBackup.zip" : "sqlBackup_" . ($k + 1). ".zip";

            //TODO check here
            // 	$zipOut = $sum == 1 ? "sqlBackup.zip" : "sqlBackup_{$k}.zip";


            //	Log::write('$zipFiles:'.$zipFiles);

            // $MySQLLogic= new \Admin\Logic\MySQLLogic();

            //   if ( $MySQLLogic->zip($zipFiles, $zipOut)) {
            //  	Log::write('$zipFiles:'.$zipFiles.'//$zipOut:'.$zipOut);

            //TODO is_ok
            // send_mail($to, "", "数据库备份" . ($k + 1) . "/{$sum}", "网站：<b>" . $this->site['SITE_INFO']['name'] . "</b> 数据文件备份", DB_Backup_PATH .'CUSTOM_20131016_o2Jke_1.sql');//
            //        send_mail($to, "", "数据库备份" . ($k + 1) . "/{$sum}", "网站：<b>" . $this->site['SITE_INFO']['name'] . "</b> 数据文件备份", WEB_CACHE_PATH . $zipOut);//


            File::delAll(WEB_CACHE_PATH . $zipOut); //删除已发送附件


            // echo json_encode(array("status" => 1, "info" => "如果要发送SQL文件卷较大(多)发送时间可能需要几分钟甚至更久，请耐心等待，发送期间请勿刷新本页。SQL打包成{$sum}个zip包，分{$sum}封邮件发出，<font color=\"red\">当前已经发送完第{$k}封邮件</font>", "url" => U('Admin/Data/sendSql', array(randCode() => randCode()))));
            // unset($_SESSION['cacheSendSql']['files'][$k]);
            // exit;
            //    }
            // }
            $time = time() - $_SESSION['cacheSendSql']['time'];
            unset($_SESSION['cacheSendSql']);

            if (is_bool($res) && $res == true) {
                $this->json_return(1, "sql文件已发送到你的邮件，请注意查收<br/>耗时：$time 秒");
            } else {
                $this->json_return(1, "$res");
            }
        }
        $this->display();
    }

    /**
     * 打包sql文件
     */
    public function zipSql()
    {
        if (IS_POST) {
            header('Content-Type:application/json; charset=utf-8');
            $sqlFiles = explode(',', $_POST['sqlFiles']);
            if (empty($sqlFiles) || count(sqlFiles) == 0 || $_POST['sqlFiles'] == "")
                $this->json_return(0, "请选择要打包的sql文件");

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
                    $this->json_return(2, "打包过程出现错误");
                }
            }
            $this->json_return(1, "打包的sql文件成功，本次打包" . count($toZip) . "个zip文件", U('Admin/Data/zipList'));
        }
    }

    /**
     * 列出以打包sql文件
     */
    public function zipList()
    {
        $MySQLLogic = new \Admin\Logic\MySQLLogic();

        $data = $MySQLLogic->getZipFilesList();
        $this->assign("list", $data['list']);
        $this->assign("total", $data['size']);
        $this->assign("files", count($data['list']));
        $this->display();
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
            $this->json_return(0, "请选择要解压的zip文件");

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
        $this->json_return(1, "已解压完成&nbsp;&nbsp;耗时：$time 秒", U('Admin/Data/restore'));

    }

    /**
     * 删除已备份数据库文件
     */
    public function delZipFiles()
    {
        if (IS_POST) {
            $zipFiles = explode(',', $_POST['zipFiles']);
            if (empty($zipFiles) || count($zipFiles) == 0 || $_POST['zipFiles'] == "") {
                $this->json_return(0, "请先选择要删除的zip文件");
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
     * 完整性测试，有待检验
     */
    //TODO bug
    /**
     * cat tag被删除之后完整性不能保证
     */
    private function integrity_testing()
    {


        $post_ids = D('Posts')->field('post_id')->select();
        foreach ($post_ids as $key => $value) {
            $post_ids[$key] = $post_ids[$key]['post_id'];
        }

        $tag_ids = D('Tags')->field('tag_id')->select();
        foreach ($tag_ids as $key => $value) {
            $tag_ids[$key] = $tag_ids[$key]['tag_id'];
        }

        $cat_ids = D('Cats')->field('cat_id')->select();
        foreach ($cat_ids as $key => $value) {
            $cat_ids[$key] = $cat_ids[$key]['cat_id'];
        }

        $map['post_id'] = array('not in', $post_ids);
        $map['cat_id'] = array('not in', $cat_ids);
        $map['_logic'] = 'OR';

        $un = D('Post_cat')->where($map)->delete();

        unset($map);


        $map['post_id'] = array('not in', $post_ids);
        $map['tag_id'] = array('not in', $tag_ids);
        $map['_logic'] = 'OR';

        //print_array($map);
        $un2 = D('Post_tag')->where($map)->delete();


    }


    /**
     *
     */
    public function repair()
    {
        $M = M();
        if (IS_POST) {

            $this->integrity_testing();

            if (empty($_POST['table']) || count($_POST['table']) == 0) {
                $this->json_return(0, "请选择要处理的表");
            }
            $table = implode(',', $_POST['table']);
            if ($_POST['act'] == 'repair') {
                if ($M->query("REPAIR TABLE {$table} "))
                    $this->json_return(1, "修复表成功", U('Admin/Data/repair'));
            } elseif ($_POST['act'] == 'optimize') {
                if ($M->query("OPTIMIZE TABLE $table"))
                    $this->json_return(1, "优化表成功", U('Admin/Data/repair'));
            }
            $this->json_return(0, "请选择操作");
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
    public function cache()
    {
        $this->assign('HTML_CACHE_ON', (int)get_opinion('HTML_CACHE_ON'));
        $this->assign('DB_FIELDS_CACHE', (int)get_opinion('DB_FIELDS_CACHE'));
        $this->assign('DB_SQL_BUILD_CACHE', (int)get_opinion('DB_SQL_BUILD_CACHE'));

        $this->display();
    }

    /**
     *
     */
    public function cacheHandle()
    {
        $this->saveConfig();
        $this->success('配置成功');

    }


    /**
     *
     */
    public function clear()
    {
        $caches = array(
            "HTMLCache"   => array(
                "name" => "网站HTML缓存文件",
                "path" => RUNTIME_PATH . "HTML",
                //"size" => $Dir->size(RUNTIME_PATH . "Cache"),
                "size" => File::realSize(RUNTIME_PATH . "HTML"),
            ),
            "HomeCache"   => array(
                "name" => "网站缓存文件",
                "path" => RUNTIME_PATH . "Cache",
                //"size" => $Dir->size(RUNTIME_PATH . "Cache"),
                "size" => File::realSize(RUNTIME_PATH . "Cache"),
            ),
            "HomeData"    => array(
                "name" => "网站数据库字段缓存文件",
                "path" => RUNTIME_PATH . "Data",
                "size" => File::realSize(RUNTIME_PATH . "Data"),
            ),
            "AdminLog"    => array(
                "name" => "网站日志缓存文件",
                "path" => LOG_PATH,
                "size" => File::realSize(LOG_PATH),
            ),
            "AdminTemp"   => array(
                "name" => "网站临时缓存文件",
                "path" => RUNTIME_PATH . "Temp",
                "size" => File::realSize(RUNTIME_PATH . "Temp"),
            ),
            "Homeruntime" => array(
                "name" => "网站~runtime.php缓存文件",
                "path" => RUNTIME_PATH . "~runtime.php",
                //  "size" => $Dir->realsize(RUNTIME_PATH . "~runtime.php"),
                "size" => File::realSize(RUNTIME_PATH . "common~runtime.php"),
            )
        );

        // p($_POST['cache']);die;
        if (IS_POST) {
            $paths=$_POST ['cache'];
            foreach ($paths as $path) {
                if (isset ($caches [$path])) {
                    $res = File::delAll($caches [$path] ['path'], true);
                }
                //$Dir->delDirAndFile($caches [$path] ['path']);
            }

            /*echo json_encode ( array (
                    "status" => 1,
                    "info" => "缓存文件已清除"
            ) );*/
            $this->success("清除成功");
        } else {

            $this->assign("caches", $caches);
            $this->display();
        }
    }




}