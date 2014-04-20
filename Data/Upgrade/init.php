<?php
/**
 * Created by Green Studio.
 * File: Upgrade.php
 * User: TianShuo
 * Date: 14-4-20
 * Time: 下午4:51
 */
function upgrade_20140420_to_20140421()
{
    $db_prefix = C('db_prefix');
    $sql = "ALTER TABLE `{$db_prefix}role` ADD COLUMN `cataccess` varchar(255) NOT NULL DEFAULT ''";
    $Model = new \Think\Model();
    $Model->query($sql);


}