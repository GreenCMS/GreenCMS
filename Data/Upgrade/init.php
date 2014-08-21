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


function upgrade_20140620_to_20140706()
{
    $db_prefix = C('db_prefix');

    $Model = new \Think\Model();

    $sql = "ALTER TABLE `{$db_prefix}posts` ADD COLUMN `post_url` varchar(255)";
    $Model->query($sql);

    $Model = new \Think\Model();

    $sql = "DROP TABLE IF EXISTS `{$db_prefix}log`";
    $Model->query($sql);

    $Model = new \Think\Model();

    $sql = "CREATE TABLE `{$db_prefix}log` (
  `log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `log_type` int(5) DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `group_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `module_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `action_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `user_id` bigint(20) DEFAULT NULL,
  `user_ip` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `message` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=497 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录';";
    $Model->query($sql);

}


function upgrade_20140706_to_20140726()
{

    $db_prefix = C('db_prefix');

    $Model = new \Think\Model();
    $sql = "DROP TABLE IF EXISTS `{$db_prefix}theme`";
    $Model->query($sql);

    $Model = new \Think\Model();


    $sql = "CREATE TABLE `{$db_prefix}theme` (
  `theme_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `theme_name` varchar(255) DEFAULT NULL,
  `theme_description` varchar(255) DEFAULT NULL,
  `theme_build` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `theme_versioin` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `theme_preview` varchar(255) DEFAULT NULL,
  `theme_copyright` varchar(255) DEFAULT NULL,
  `theme_xml` text,
  `theme_config` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  PRIMARY KEY (`theme_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;
";

    $Model->query($sql);



}



function upgrade_20140819_to_20140822()
{
    $db_prefix = C('db_prefix');

    $Model = new \Think\Model();
    $sql = "DROP TABLE IF EXISTS `{$db_prefix}plugin`";
    $Model->query($sql);

    $Model = new \Think\Model();


    $sql = "CREATE TABLE `{$db_prefix}plugin` (
  `plugin_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `plugin_title` char(20) NOT NULL,
  `plugin_description` text NOT NULL,
  `plugin_author` char(20) NOT NULL,
  `plugin_copyright` char(50) NOT NULL,
  `plugin_pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`plugin_id`),
  KEY `status` (`plugin_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='插件信息表';";

    $Model->query($sql);





}