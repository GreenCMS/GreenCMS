<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: SystemEvent.class.php
 * User: Timothy Zhang
 * Date: 14-2-17
 * Time: 上午11:50
 */

namespace Common\Event;

use Common\Util\File;
use Common\Logic;
use Think\Cache;

/**
 * 系统事件 包括数据缓存文件 备份清理操作
 * Class SystemEvent
 * @package Common\Event
 */
class SystemEvent
{

    /**
     * 文章关联完整性检查
     */
    public function postIntegrity()
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


        $Post_cat = D('Post_cat');
        $pc = $Post_cat->select();
        foreach ($pc as $key => $value) {
            if ((!in_array($value["post_id"], $post_ids)) || (!in_array($value["cat_id"], $cat_ids))) {
                $Post_cat->where($value)->delete();
            }
        }

        $Post_tag = D('Post_tag');
        $pt = $Post_tag->select();
        foreach ($pt as $key => $value) {
            if ((!in_array($value["post_id"], $post_ids)) || (!in_array($value["tag_id"], $tag_ids))) {
                $Post_tag->where($value)->delete();
            }
        }


    }


    /**
     * 备份文件夹
     * @param string $dir
     * @param string $backup_path
     * @return array
     */
    public function backupFile($dir = '', $backup_path = System_Backup_PATH)
    {
        if ($dir == '') {
            $dir = scandir(WEB_ROOT);
        }
        @set_time_limit(1024);

        mkdir($backup_path, 0777, true);

        $Zip = new \ZipArchive();
        $PHPZip = new \Common\Util\PHPZip();

        $file_name = $backup_path . 'system_backup-' . date('Ymd') . '-' . md5(rand(0, 255) . md5(rand(128, 200)) . rand(100, 768)) . ".zip";
        $Zip->open($file_name, \ZIPARCHIVE::CREATE);;

        foreach ($dir as $value) {
            if ($value[0] != '.' && $value != 'Data') {
                $PHPZip::folderToZip($value, $Zip);
            }
        }

        $Zip->close();

        return array("status" => 1, "info" => $file_name);
    }

    /**
     * 清空所有缓存
     * @return bool
     */
    public function clearCacheAll()
    {
        $caches = array(
            RUNTIME_PATH . "HTML",
            RUNTIME_PATH . "Cache",
            RUNTIME_PATH . "Data",
            RUNTIME_PATH . "Temp",
            RUNTIME_PATH . "~runtime.php",
        );
        foreach ($caches as $value) {
            $this->clearCache($value);
        }
        $Cache = new Cache();
        $caches = $Cache->connect();
        $caches->clear();

        return true;
    }

    /**
     * 清空缓存
     * @param $cache_path
     * @return bool
     */
    public function clearCache($cache_path)
    {
        return File::delAll($cache_path, true);
    }

    /**
     * 清空日志
     * @return bool
     */
    public function clearLog()
    {
        return File::delAll(LOG_PATH, true);
    }

    /**
     * 备份所有数据看
     * @return array
     */
    public function backupDBAll()
    {
        $type = "系统自动备份";
        $path = DB_Backup_PATH . "/SYSTEM_" . date("Ymd");

        $MySQLLogic = new \Common\Util\MySQLUtil();
        $tables = $MySQLLogic->getAllTableName();
        return $this->backupDB($type, $tables, $path);
    }

    /**
     * 备份数据库
     * @param string $type
     * @param array $tables
     * @param string $path
     * @return array
     */
    public function backupDB($type = "系统自动备份", $tables = array(), $path = DB_Backup_PATH)
    {
        $M = M();
        function_exists('set_time_limit') && set_time_limit(0); //防止备份数据过程超时

        /**
         * 如果备份文件夹不存在，则自动建立
         */
        if (!is_dir(DB_Backup_PATH)) {
            File::makeDir(DB_Backup_PATH, 0777);
        }

        G('Backup_start');


        $pre =
            "# -----------------------------------------------------------\n" .
            "# " . get_opinion('title') . " database backup files\n" .
            "# URL: " . get_opinion('site_url') . "\n" .
            "# Type: {$type}\n";
        $MySQLLogic = new \Common\Util\MySQLUtil();
        $bdTable = $MySQLLogic->backupTable($tables); //取得表结构信息
        $outPut = "";
        $file_n = 1;
        $backedTable = array();
        foreach ($tables as $table) {
            $backedTable[] = $table;
            $outPut .= "\n\n# 数据库表：{$table} 数据信息\n";
            $tableInfo = $M->query("SHOW TABLE STATUS LIKE '{$table}'");
            $page = ceil($tableInfo[0]['Rows'] / 10000) - 1;
            for ($i = 0; $i <= $page; $i++) {
                $query = $M->query("SELECT * FROM {$table} LIMIT " . ($i * 10000) . ", 10000");
                foreach ($query as $val) {
                    $temSql = "";
                    $tn = 0;
                    $temSql = '';
                    foreach ($val as $v) {
                        $temSql .= $tn == 0 ? "" : ",";
                        $temSql .= $v == '' ? "''" : "'{$v}'";
                        $tn++;
                    }
                    $temSql = "INSERT INTO `{$table}` VALUES ({$temSql});\n";

                    $sqlNo = "\n# Time: " . date("Y-m-d H:i:s") . "\n" .
                        "# -----------------------------------------------------------\n" .
                        "# 当前SQL卷标：#{$file_n}\n# -----------------------------------------------------------\n\n\n";
                    if ($file_n == 1) {
                        $sqlNo = "# Description:当前SQL文件包含了表：" . implode("、", $tables) . "的结构信息，表：" . implode("、", $backedTable) . "的数据" . $sqlNo;
                    } else {
                        $sqlNo = "# Description:当前SQL文件包含了表：" . implode("、", $backedTable) . "的数据" . $sqlNo;
                    }
                    if (strlen($pre) + strlen($sqlNo) + strlen($bdTable) + strlen($outPut) + strlen($temSql) > get_opinion("sqlFileSize")) {
                        $file_name = $path . "_" . $file_n . ".sql";
                        $outPut = $file_n == 1 ? $pre . $sqlNo . $bdTable . $outPut : $pre . $sqlNo . $outPut;
                        //file_put_contents($file, $outPut, FILE_APPEND);
                        //TODO file_put_contents-->> File::writeFile需要测试
                        File::writeFile($file_name, $outPut);
                        $bdTable = $outPut = "";
                        $backedTable = array();
                        $backedTable[] = $table;
                        $file_n++;
                    }
                    $outPut .= $temSql;
                }
            }
        }
        if (strlen($bdTable . $outPut) > 0) {
            $sqlNo = "\n# Time: " . date("Y-m-d H:i:s") . "\n" .
                "# -----------------------------------------------------------\n" .
                "# 当前SQL卷标：#{$file_n}\n# -----------------------------------------------------------\n\n\n";
            if ($file_n == 1) {
                $sqlNo = "# Description:当前SQL文件包含了表：" . implode("、", $tables) . "的结构信息，表：" . implode("、", $backedTable) . "的数据" . $sqlNo;
            } else {
                $sqlNo = "# Description:当前SQL文件包含了表：" . implode("、", $backedTable) . "的数据" . $sqlNo;
            }
            $file_name = $path . "_" . $file_n . ".sql";
            $outPut = $file_n == 1 ? $pre . $sqlNo . $bdTable . $outPut : $pre . $sqlNo . $outPut;
            // file_put_contents($file_name, $outPut, FILE_APPEND);
            File::writeFile($file_name, $outPut);
            $file_n++;
        }

        G('Backup_end');

        $res = array("status" => 1, "info" => "成功备份所选数据库表结构和数据，本次备份共生成了" . ($file_n - 1) .
            "个SQL文件。耗时：" . G('Backup_start', 'Backup_end') . "秒", "url" => U('Admin/Data/restore'));
        return $res;
    }


}