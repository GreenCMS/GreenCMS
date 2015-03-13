<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-9-24
 * Time: 下午9:03
 */

namespace Common\Event;


use Common\Util\File;

class MySQLMaintainEvent implements DBMaintainEvent
{
    /**
     * @var int
     */
    static public $sqlFilesSize = 0;

    /**
     * +----------------------------------------------------------
     * 功能：获取数据库中所有表名
     * +----------------------------------------------------------
     * @return array
    +----------------------------------------------------------
     */
    public function getAllTableName()
    {
        $tabs = M()->query('SHOW TABLE STATUS');
        $arr = array();
        foreach ($tabs as $tab) {
            $arr[] = $tab['Name'];
        }
        unset($tabs);
        return $arr;
    }

    /**
     * +----------------------------------------------------------
     * 功能：读取数据库表结构信息
     * +----------------------------------------------------------
     * @param $table_list
     *
     * @return string
    +----------------------------------------------------------
     */
    public function backupTable($table_list)
    {
        //M()->query("SET OPTION SQL_QUOTE_SHOW_CREATE = 1"); //1，表示表名和字段名会用``包着的,0 则不用``
        $outPut = '';
        if (!is_array($table_list) || empty($table_list)) {
            return false;
        }
        foreach ($table_list as $table) {
            $outPut .= "# 数据库表：{$table} 结构信息\n";
            $outPut .= "DROP TABLE IF EXISTS `{$table}`;\n";
            $tmp = M()->query("SHOW CREATE TABLE {$table}");
            $outPut .= $tmp[0]['Create Table'] . " ;\n\n";
        }
        return $outPut;
    }

    /**
     * 功能：读取已经备份SQL文件列表，并按备份时间倒序，名称升序排列
     * @return array
     */
    public function getSqlFilesList()
    {
        $list = array();
        $size = 0;
        $handle = opendir(DB_Backup_PATH);

        while ($file = readdir($handle)) {
            if (preg_match('#\.sql$#i', $file)) {
                $fp = fopen(DB_Backup_PATH . "/$file", 'rb');
                $bakinfo = fread($fp, 2000);
                fclose($fp);
                $detail = explode("\n", $bakinfo);
                $bk = array();
                $bk['name'] = $file;
                $bk['url'] = substr($detail[2], 7);
                $bk['type'] = substr($detail[3], 8);
                $bk['description'] = substr($detail[4], 14);
                $bk['time'] = substr($detail[5], 8);
                $_size = filesize(DB_Backup_PATH . "/$file");
                $bk['size'] = File::byteFormat($_size);
                $size += $_size;
                $bk['pre'] = substr($file, 0, strrpos($file, '_'));
                $bk['num'] = substr($file, strrpos($file, '_') + 1, strrpos($file, '.') - 1 - strrpos($file, '_'));
                $mtime = filemtime(DB_Backup_PATH . "/$file");
                $list[$mtime][$file] = $bk;
            }
        }
        closedir($handle);
        krsort($list); //按备份时间倒序排列
        $newArr = array();
        foreach ($list as $k => $array) {
            ksort($array); //按备份文件名称顺序排列
            foreach ($array as $arr) {
                $newArr[] = $arr;
            }
        }
        unset($list);
        return array("list" => $newArr, "size" => File::byteFormat($size));
    }

    /**
     * @return array
     */
    public function getZipFilesList()
    {
        $list = array();
        $size = 0;
        $handle = opendir(DB_Backup_PATH . "Zip/");

        while ($file = readdir($handle)) {
            if ($file != "." && $file != "..") {
                $tem = array();
                $tem['file'] = $file; //  checkCharset($file);
                $_size = filesize(DB_Backup_PATH . "Zip/$file");
                $tem['size'] = File::byteFormat($_size);
                $tem['time'] = date("Y-m-d H:i:s", filectime(DB_Backup_PATH . "Zip/$file"));
                $size += $_size;
                $list[] = $tem;
            }
        }
        return array("list" => $list, "size" => File::byteFormat($size));
    }

} 