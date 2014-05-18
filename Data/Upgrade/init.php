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


function upgrade_20140501_to_20140512()
{
    $db_prefix = C('db_prefix');
    $Model = new \Think\Model();

    $sql = "ALTER TABLE `{$db_prefix}login_log` ADD COLUMN `log_ip` varchar(255)";
    $Model->query($sql);

    $sql = "ALTER TABLE `{$db_prefix}login_log` ADD COLUMN `log_status` smallint(5)";
    $Model->query($sql);

}


function upgrade_20140517_to_20140520()
{

    //删除旧版Admin


}