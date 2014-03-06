<?php
/**
 * Created by Green Studio.
 * File: index.php
 * User: TianShuo
 * Date: 14-1-23
 * Time: 上午11:57
 */
ob_start();


/**
 * 下面的内容自己决定
 */
error_reporting(E_ERROR | E_WARNING | E_PARSE);
//error_reporting(0);
@set_time_limit(240);
//@ini_set("memory_limit",'-1');

//date_default_timezone_set (PRC);


/**
 * 系统调试设置
 * 项目正式部署后请设置为false
 */
define('APP_DEBUG', true);

/**
 * 定义网站根目录
 */
define("WEB_ROOT", dirname(__FILE__) . '/');

/**
 * 应用目录设置
 */
define ('APP_PATH', './Application/');


if (file_exists(WEB_ROOT . "db_config.php")) require(WEB_ROOT . "db_config.php");
elseif (strtolower($_GET['m']) != 'install') die('<a href="install.php">click here to install</a>');
if (file_exists(WEB_ROOT . "const_config.php")) require(WEB_ROOT . "const_config.php");


/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require './Core/ThinkPHP/ThinkPHP.php';