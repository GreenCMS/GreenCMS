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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;";
    $Model->query($sql);

}



function upgrade_20140706_to_20140620()
{
    $db_prefix = C('db_prefix');

    $Model = new \Think\Model();

    $sql = "ALTER TABLE `{$db_prefix}posts` ADD COLUMN `post_url` varchar(255)";
    $Model->query($sql);

    $sql = "DROP TABLE IF EXISTS `{$db_prefix}log`";
    $Model->query($sql);

    $sql = "CREATE TABLE `{$db_prefix}log` ( `log_id` bigint(20) NOT NULL AUTO_INCREMENT,  `log_type` int(5) DEFAULT NULL,  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,  `group_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  `module_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  `action_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  `user_id` bigint(20) DEFAULT NULL,
  `user_ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,  PRIMARY KEY (`log_id`)) ENGINE=MyISAM AUTO_INCREMENT=61 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录';";

    $Model->query($sql);

}
