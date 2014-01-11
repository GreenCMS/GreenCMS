<?php

if (version_compare(PHP_VERSION, '5.3.0', '<')) die('require PHP > 5.3.0 !'); //这个是TP3.2的需求,需要namespace

/**
 * 下面的内容自己决定
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
@set_time_limit(240);

/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define ('APP_DEBUG', true);

/**定义网站根目录
 *
 */
define("WEB_ROOT", dirname(__FILE__) . "/");

/**
 * 强制修正URL路径(一般不需要开启)
 * 适用于反代一个二级目录并且不能替换部分文字的情况。(我说的就是捉急的iis~~~~)
 * /
//define('__APP__', '');


/**
 * 缓存目录设置
 * 此目录必须可写，建议移动到非WEB目录
 */
define ('RUNTIME_PATH', './Data/Temp/');

/** 定义项目日志文件路径
 *  此目录必须可写，建议移动到非WEB目录
 */
define('LOG_PATH', WEB_ROOT . "Data/Log/");

/**定义Cache目录
 *  此目录必须可写，建议移动到非WEB目录
 */
define('WEB_CACHE_PATH', WEB_ROOT . "Data/Cache/");

/**系统备份数据库文件存放目录
 *  此目录必须可写，建议移动到非WEB目录
 */
define("DB_Backup_PATH", WEB_ROOT . "Data/DBbackup/");

/**
 * 系统备份文件存放目录
 */
define("System_Backup_PATH", WEB_ROOT . "Data/Backup/");

/**系统升级文件存放目录
 */
define("Upgrade_PATH", WEB_ROOT . "Data/Upgrade/");

/**上传文件存放目录
 */
define("UploadDir", "Upload/");

/**
 * 应用目录设置
 * 安全期间，建议安装调试完成后移动到非WEB目录
 */
define ('APP_PATH', './Application/');


/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require './Core/ThinkPHP/ThinkPHP.php';