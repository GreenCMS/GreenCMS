<?php
/**
 * Created by PhpStorm.
 * User: Timothy Zhang
 * Date: 14-9-24
 * Time: 下午9:03
 */

namespace Common\Event;


interface DBMaintainEvent
{

    public function getAllTableName();

    public function backupTable($table_list);

    public function getSqlFilesList();

    public function getZipFilesList();

} 