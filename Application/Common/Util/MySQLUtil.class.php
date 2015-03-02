<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: MySQLLogic.class.php
 * User: Timothy Zhang
 * Date: 14-1-27
 * Time: 下午8:33
 */

namespace Common\Util;

use Think\Model;

/**
 * Class MySQLUtil
 * @package Admin\Logic
 */
class MySQLUtil // extends Model
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


}
