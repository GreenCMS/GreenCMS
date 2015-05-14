<?php
/**
 * Created by GreenStudio GCS Dev Team.
 * File: admin.php
 * User: Timothy Zhang
 * Date: 14-1-23
 * Time: 上午11:57
 */
if (version_compare(PHP_VERSION, '5.3.0', '<')) die('require PHP > 5.3.0 !'); //这个是TP3.2的需求,需要namespace

//header("Location: ./index.php?m=Install");

error_reporting(E_ERROR | E_WARNING);
@set_time_limit(1200);



/**
 * 系统调试设置 true
 * 项目正式部署后请设置为 false
 */
define('APP_DEBUG', true);


/**
 * 定义网站根目录
 */
define("WEB_ROOT", './');  //dirname(__FILE__) .'/'

/**
 * 应用目录设置
 */
define ('APP_PATH', './Install/');

if (file_exists(WEB_ROOT . "db_config.php")) require(WEB_ROOT . "db_config.php");
require(WEB_ROOT . "const_config.php");
require(WEB_ROOT . "version_config.php");


/**
 * 引入核心入口
 * ThinkPHP亦可移动到WEB以外的目录
 */
require './Core/ThinkPHP/ThinkPHP.php';