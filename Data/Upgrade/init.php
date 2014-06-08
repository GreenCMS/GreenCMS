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


function upgrade_20140517_to_20140519()
{

    //删除旧版Admin
//下次再做

}


function upgrade_20140520_to_20140525()
{
    $db_prefix = C('db_prefix');

    $Model = new \Think\Model();

    $sql = "DROP TABLE IF EXISTS `{$db_prefix}link_group`";
    $Model->query($sql);

    $sql = "CREATE TABLE `{$db_prefix}link_group` (
`link_group_id`  bigint(20) NOT NULL AUTO_INCREMENT ,
  `link_group_name`  varchar(20) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL DEFAULT NULL ,
  PRIMARY KEY (`link_group_id`)
)  ENGINE=MyISAM DEFAULT CHARACTER SET=utf8 COLLATE=utf8_unicode_ci COMMENT='链接分类表';";
    $Model->query($sql);

    $sql = "ALTER TABLE `{$db_prefix}links` ADD COLUMN `link_group_id` bigint(20)";
    $Model->query($sql);
}

function upgrade_20140527_to_20140602()
{
    $db_prefix = C('db_prefix');

    $Model = new \Think\Model();

    $sql = "DROP TABLE IF EXISTS `{$db_prefix}user_sns`";
    $Model->query($sql);

    $sql = "CREATE TABLE `{$db_prefix}user_sns` (
  `us_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) DEFAULT '0',
  `access_token` varchar(50) DEFAULT NULL,
  `refresh_token` varchar(50) DEFAULT NULL,
  `remind_in` varchar(50) DEFAULT NULL,
  `expires_in` varchar(50) DEFAULT NULL,
  `openid` varchar(50) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `expires_time` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`us_id`),
  KEY `useropen` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;";
    $Model->query($sql);

}