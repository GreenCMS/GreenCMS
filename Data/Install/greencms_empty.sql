-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- 主机: 127.0.0.1
-- 生成日期: 2014 年 03 月 01 日 06:39
-- 服务器版本: 5.5.27
-- PHP 版本: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- 数据库: `green`
--

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}access`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}access` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '1',
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表';

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}addons`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}addons` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL COMMENT '插件名或标识',
  `title` varchar(20) NOT NULL DEFAULT '' COMMENT '中文名',
  `description` text COMMENT '插件描述',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态',
  `config` text COMMENT '配置',
  `author` varchar(40) DEFAULT '' COMMENT '作者',
  `version` varchar(20) DEFAULT '' COMMENT '版本号',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '安装时间',
  `has_adminlist` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否有后台列表',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='插件表' AUTO_INCREMENT=18 ;

--
-- 转存表中的数据 `{$db_prefix}addons`
--

INSERT INTO `{$db_prefix}addons` (`id`, `name`, `title`, `description`, `status`, `config`, `author`, `version`, `create_time`, `has_adminlist`) VALUES
(9, 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', 1, '{"comment_type":"2","comment_uid_youyan":"1894186","comment_short_name_duoshuo":"greencmsduoshuo","comment_form_pos_duoshuo":"buttom","comment_data_list_duoshuo":"10","comment_data_order_duoshuo":"desc"}', 'xjh1994', '0.1', 1380273962, 0);

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}cats`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}cats` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_father` bigint(10) NOT NULL DEFAULT '0',
  `cat_slug` varchar(200) NOT NULL DEFAULT '',
  `cat_name` varchar(200) NOT NULL DEFAULT '',
  `cat_order` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `slug` (`cat_slug`),
  KEY `name` (`cat_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='分类信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}comments`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}comments` (
  `comment_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint(20) unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext NOT NULL,
  `comment_author_email` varchar(100) NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) NOT NULL DEFAULT '',
  `comment_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `comment_content` text NOT NULL,
  `comment_approved` varchar(20) NOT NULL DEFAULT '1',
  `comment_parent` bigint(20) unsigned NOT NULL DEFAULT '0',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`),
  KEY `comment_parent` (`comment_parent`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}feedback`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}feedback` (
  `fid` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'anonymous',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Waiting',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='反馈信息' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}form`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}form` (
  `fa_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`fa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='申请表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}hooks`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `搜索索引` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=17 ;

--
-- 转存表中的数据 `{$db_prefix}hooks`
--

INSERT INTO `{$db_prefix}hooks` (`id`, `name`, `description`, `type`, `update_time`, `addons`) VALUES
(1, 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', 1, 0, ''),
(2, 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', 1, 0, 'ReturnTop'),
(3, 'documentEditForm', '添加编辑表单的 扩展内容钩子', 1, 0, ''),
(4, 'documentDetailAfter', '文档末尾显示', 1, 0, 'SocialComment,baidushare'),
(5, 'documentDetailBefore', '页面内容前显示用钩子', 1, 0, ''),
(6, 'documentSaveComplete', '保存文档数据后的扩展钩子', 2, 0, ''),
(7, 'documentEditFormContent', '添加编辑表单的内容显示钩子', 1, 0, 'Editor'),
(8, 'adminArticleEdit', '后台内容编辑页编辑器', 1, 1378982734, 'EditorForAdmin'),
(13, 'AdminIndex', '首页小格子个性化显示', 1, 1382596073, ''),
(14, 'topicComment', '评论提交方式扩展钩子。', 1, 1380163518, 'Editor'),
(16, 'app_begin', '应用开始', 2, 1384481614, '');

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}kv`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}kv` (
  `kv_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kv_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kv_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kv_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='key-value通用信息存储' AUTO_INCREMENT=3 ;

--
-- 转存表中的数据 `{$db_prefix}kv`
--

INSERT INTO `{$db_prefix}kv` (`kv_id`, `kv_key`, `kv_value`) VALUES
(1, 'theme_2011college', 'disabled'),
(2, 'theme_Vena', 'disabled');

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}links`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_sort` smallint(25) DEFAULT '0',
  `link_url` varchar(255) DEFAULT '',
  `link_name` varchar(255) DEFAULT '',
  `link_tag` varchar(255) DEFAULT '',
  `link_description` varchar(255) DEFAULT '',
  `link_visible` tinyint(20) DEFAULT '1',
  `link_img` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}log`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}log` (
  `log_id` int(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}login_log`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}login_log` (
  `login_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `log_user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_geo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`login_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登录信息记录' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}menu`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}menu` (
  `menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_pid` bigint(20) NOT NULL DEFAULT '0',
  `menu_sort` bigint(20) NOT NULL DEFAULT '99',
  `menu_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_action` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_function` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_position` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单栏' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}node`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='权限节点表' AUTO_INCREMENT=136 ;

--
-- 转存表中的数据 `{$db_prefix}node`
--

INSERT INTO `{$db_prefix}node` (`id`, `name`, `title`, `status`, `remark`, `sort`, `pid`, `level`) VALUES
(1, 'Admin', '后台管理', 1, '后台管理', 0, 0, 1),
(2, 'Index', 'IndexController', 1, 'IndexController', 0, 1, 2),
(3, 'Access', 'AccessController', 1, 'AccessController', 0, 1, 2),
(4, 'Custom', 'CustomController', 1, 'CustomController', 0, 1, 2),
(5, 'Data', 'DataController', 1, 'DataController', 0, 1, 2),
(6, 'System', 'SystemController', 1, 'SystemController', 0, 1, 2),
(7, 'Media', 'MediaController', 1, 'MediaController', 0, 1, 2),
(8, 'Ueditor', 'UeditorController', 1, 'UeditorController', 0, 1, 2),
(9, 'Posts', 'PostsController', 1, 'PostsController', 0, 1, 2),
(10, 'index', 'index', 1, 'index', 0, 2, 3),
(11, 'main', 'main', 1, 'main', 0, 2, 3),
(12, 'changePass', 'changePass', 1, 'changePass', 0, 2, 3),
(13, 'changePassHandle', 'changePassHandle', 1, 'changePassHandle', 0, 2, 3),
(14, 'index', 'index', 1, 'index', 0, 3, 3),
(15, 'guest', 'guest', 1, 'guest', 0, 3, 3),
(16, 'indexlocked', 'indexlocked', 1, 'indexlocked', 0, 3, 3),
(17, 'indexlock', 'indexlock', 1, 'indexlock', 0, 3, 3),
(18, 'rolelist', 'rolelist', 1, 'rolelist', 0, 3, 3),
(19, 'nodelist', 'nodelist', 1, 'nodelist', 0, 3, 3),
(20, 'addUser', 'addUser', 1, 'addUser', 0, 3, 3),
(21, 'addUserHandle', 'addUserHandle', 1, 'addUserHandle', 0, 3, 3),
(22, 'editUser', 'editUser', 1, 'editUser', 0, 3, 3),
(23, 'delUser', 'delUser', 1, 'delUser', 0, 3, 3),
(24, 'addRole', 'addRole', 1, 'addRole', 0, 3, 3),
(25, 'editRole', 'editRole', 1, 'editRole', 0, 3, 3),
(26, 'changeRole', 'changeRole', 1, 'changeRole', 0, 3, 3),
(27, 'opNodeStatus', 'opNodeStatus', 1, 'opNodeStatus', 0, 3, 3),
(28, 'opRoleStatus', 'opRoleStatus', 1, 'opRoleStatus', 0, 3, 3),
(29, 'opSort', 'opSort', 1, 'opSort', 0, 3, 3),
(30, 'addRoleHandle', 'addRoleHandle', 1, 'addRoleHandle', 0, 3, 3),
(31, 'addNode', 'addNode', 1, 'addNode', 0, 3, 3),
(32, 'editNode', 'editNode', 1, 'editNode', 0, 3, 3),
(33, 'addNodeHandle', 'addNodeHandle', 1, 'addNodeHandle', 0, 3, 3),
(34, 'index', 'index', 1, 'index', 0, 4, 3),
(35, 'menu', 'menu', 1, 'menu', 0, 4, 3),
(36, 'menuDel', 'menuDel', 1, 'menuDel', 0, 4, 3),
(37, 'menuAdd', 'menuAdd', 1, 'menuAdd', 0, 4, 3),
(38, 'menuAddHandle', 'menuAddHandle', 1, 'menuAddHandle', 0, 4, 3),
(39, 'menuEdit', 'menuEdit', 1, 'menuEdit', 0, 4, 3),
(40, 'menuEditHandle', 'menuEditHandle', 1, 'menuEditHandle', 0, 4, 3),
(41, 'themeAdd', 'themeAdd', 1, 'themeAdd', 0, 4, 3),
(42, 'themeChangeHandle', 'themeChangeHandle', 1, 'themeChangeHandle', 0, 4, 3),
(43, 'themeDelHandle', 'themeDelHandle', 1, 'themeDelHandle', 0, 4, 3),
(44, 'plugin', 'plugin', 1, 'plugin', 0, 4, 3),
(45, 'create', 'create', 1, 'create', 0, 4, 3),
(46, 'preview', 'preview', 1, 'preview', 0, 4, 3),
(47, 'checkForm', 'checkForm', 1, 'checkForm', 0, 4, 3),
(48, 'build', 'build', 1, 'build', 0, 4, 3),
(49, 'adminList', 'adminList', 1, 'adminList', 0, 4, 3),
(50, 'enable', 'enable', 1, 'enable', 0, 4, 3),
(51, 'disable', 'disable', 1, 'disable', 0, 4, 3),
(52, 'config', 'config', 1, 'config', 0, 4, 3),
(53, 'install', 'install', 1, 'install', 0, 4, 3),
(54, 'uninstall', 'uninstall', 1, 'uninstall', 0, 4, 3),
(55, 'hooks', 'hooks', 1, 'hooks', 0, 4, 3),
(56, 'addhook', 'addhook', 1, 'addhook', 0, 4, 3),
(57, 'edithook', 'edithook', 1, 'edithook', 0, 4, 3),
(58, 'delhook', 'delhook', 1, 'delhook', 0, 4, 3),
(59, 'updateHook', 'updateHook', 1, 'updateHook', 0, 4, 3),
(60, 'execute', 'execute', 1, 'execute', 0, 4, 3),
(61, 'db', 'db', 1, 'db', 0, 5, 3),
(62, 'dbHandle', 'dbHandle', 1, 'dbHandle', 0, 5, 3),
(63, 'index', 'index', 1, 'index', 0, 5, 3),
(64, 'backupHandle', 'backupHandle', 1, 'backupHandle', 0, 5, 3),
(65, 'restore', 'restore', 1, 'restore', 0, 5, 3),
(66, 'restoreData', 'restoreData', 1, 'restoreData', 0, 5, 3),
(67, 'delSqlFiles', 'delSqlFiles', 1, 'delSqlFiles', 0, 5, 3),
(68, 'sendSql', 'sendSql', 1, 'sendSql', 0, 5, 3),
(69, 'zipSql', 'zipSql', 1, 'zipSql', 0, 5, 3),
(70, 'zipList', 'zipList', 1, 'zipList', 0, 5, 3),
(71, 'unzipSqlfile', 'unzipSqlfile', 1, 'unzipSqlfile', 0, 5, 3),
(72, 'delZipFiles', 'delZipFiles', 1, 'delZipFiles', 0, 5, 3),
(73, 'downFile', 'downFile', 1, 'downFile', 0, 5, 3),
(74, 'repair', 'repair', 1, 'repair', 0, 5, 3),
(75, 'cache', 'cache', 1, 'cache', 0, 5, 3),
(76, 'cacheHandle', 'cacheHandle', 1, 'cacheHandle', 0, 5, 3),
(77, 'clear', 'clear', 1, 'clear', 0, 5, 3),
(78, 'add', 'add', 1, 'add', 0, 6, 3),
(79, 'index', 'index', 1, 'index', 0, 6, 3),
(80, 'indexHandle', 'indexHandle', 1, 'indexHandle', 0, 6, 3),
(81, 'setEmailConfig', 'setEmailConfig', 1, 'setEmailConfig', 0, 6, 3),
(82, 'setEmailConfigHandle', 'setEmailConfigHandle', 1, 'setEmailConfigHandle', 0, 6, 3),
(83, 'setSafeConfig', 'setSafeConfig', 1, 'setSafeConfig', 0, 6, 3),
(84, 'setSafeConfigHandle', 'setSafeConfigHandle', 1, 'setSafeConfigHandle', 0, 6, 3),
(85, 'links', 'links', 1, 'links', 0, 6, 3),
(86, 'addlink', 'addlink', 1, 'addlink', 0, 6, 3),
(87, 'editlink', 'editlink', 1, 'editlink', 0, 6, 3),
(88, 'dellink', 'dellink', 1, 'dellink', 0, 6, 3),
(89, 'update', 'update', 1, 'update', 0, 6, 3),
(90, 'updateHandle', 'updateHandle', 1, 'updateHandle', 0, 6, 3),
(91, 'over', 'over', 1, 'over', 0, 6, 3),
(92, 'backupsql', 'backupsql', 1, 'backupsql', 0, 6, 3),
(93, 'insertsql', 'insertsql', 1, 'insertsql', 0, 6, 3),
(94, 'applycookie', 'applycookie', 1, 'applycookie', 0, 6, 3),
(95, 'info', 'info', 1, 'info', 0, 6, 3),
(96, 'special', 'special', 1, 'special', 0, 6, 3),
(97, 'file', 'file', 1, 'file', 0, 7, 3),
(98, 'fileConnect', 'fileConnect', 1, 'fileConnect', 0, 7, 3),
(99, 'backupFile', 'backupFile', 1, 'backupFile', 0, 7, 3),
(100, 'backupFileHandle', 'backupFileHandle', 1, 'backupFileHandle', 0, 7, 3),
(101, 'restoreFile', 'restoreFile', 1, 'restoreFile', 0, 7, 3),
(102, 'index', 'index', 1, 'index', 0, 8, 3),
(103, 'getContent', 'getContent', 1, 'getContent', 0, 8, 3),
(104, 'fileUp', 'fileUp', 1, 'fileUp', 0, 8, 3),
(105, 'scrawlUp', 'scrawlUp', 1, 'scrawlUp', 0, 8, 3),
(106, 'getRemoteImage', 'getRemoteImage', 1, 'getRemoteImage', 0, 8, 3),
(107, 'getRemoteImage2', 'getRemoteImage2', 1, 'getRemoteImage2', 0, 8, 3),
(108, 'getMovie', 'getMovie', 1, 'getMovie', 0, 8, 3),
(109, 'imageManager', 'imageManager', 1, 'imageManager', 0, 8, 3),
(110, 'imageUp', 'imageUp', 1, 'imageUp', 0, 8, 3),
(111, 'index', 'index', 1, 'index', 0, 9, 3),
(112, 'indexHandle', 'indexHandle', 1, 'indexHandle', 0, 9, 3),
(113, 'page', 'page', 1, 'page', 0, 9, 3),
(114, 'add', 'add', 1, 'add', 0, 9, 3),
(115, 'noVerify', 'noVerify', 1, 'noVerify', 0, 9, 3),
(116, 'addHandle', 'addHandle', 1, 'addHandle', 0, 9, 3),
(117, 'preDel', 'preDel', 1, 'preDel', 0, 9, 3),
(118, 'del', 'del', 1, 'del', 0, 9, 3),
(119, 'unverified', 'unverified', 1, 'unverified', 0, 9, 3),
(120, 'unverifiedHandle', 'unverifiedHandle', 1, 'unverifiedHandle', 0, 9, 3),
(121, 'recycle', 'recycle', 1, 'recycle', 0, 9, 3),
(122, 'recycleHandle', 'recycleHandle', 1, 'recycleHandle', 0, 9, 3),
(123, 'posts', 'posts', 1, 'posts', 0, 9, 3),
(124, 'category', 'category', 1, 'category', 0, 9, 3),
(125, 'addCategory', 'addCategory', 1, 'addCategory', 0, 9, 3),
(126, 'addCategoryHandle', 'addCategoryHandle', 1, 'addCategoryHandle', 0, 9, 3),
(127, 'editCategory', 'editCategory', 1, 'editCategory', 0, 9, 3),
(128, 'editCategoryHandle', 'editCategoryHandle', 1, 'editCategoryHandle', 0, 9, 3),
(129, 'delCategory', 'delCategory', 1, 'delCategory', 0, 9, 3),
(130, 'tag', 'tag', 1, 'tag', 0, 9, 3),
(131, 'addTag', 'addTag', 1, 'addTag', 0, 9, 3),
(132, 'addTagHandle', 'addTagHandle', 1, 'addTagHandle', 0, 9, 3),
(133, 'editTag', 'editTag', 1, 'editTag', 0, 9, 3),
(134, 'editTagHandle', 'editTagHandle', 1, 'editTagHandle', 0, 9, 3),
(135, 'delTag', 'delTag', 1, 'delTag', 0, 9, 3);

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}options`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='选项表' AUTO_INCREMENT=32 ;

--
-- 转存表中的数据 `{$db_prefix}options`
--

INSERT INTO `{$db_prefix}options` (`option_id`, `option_name`, `option_value`, `autoload`) VALUES
(1, 'site_url', 'http://localhost/greencms', 'yes'),
(2, 'title', 'GreenCMS v2', 'yes'),
(3, 'keywords', 'GreenCMS v2', 'yes'),
(4, 'description', 'GreenCMS 2014', 'yes'),
(5, 'foot', 'By GreenStudio', 'yes'),
(6, 'ip_geo', '1', 'yes'),
(7, 'software_author', 'GreenStudio', 'yes'),
(8, 'widget_about_us', 'widget_about_us', 'yes'),
(9, 'software_homepage', 'http://www.greencms.net', 'yes'),
(10, 'software_version', 'v2.1.0225', 'yes'),
(11, 'software_name', 'GreenCMS v2', 'yes'),
(12, 'LOG_RECORD', '1', 'yes'),
(13, 'software_build', '20140225', 'yes'),
(14, 'HTML_CACHE_ON', '1', 'yes'),
(15, 'sqlFileSize', '100000', 'yes'),
(16, 'send_mail', '1', 'yes'),
(17, 'smtp_host', 'mail.njut.edu.cn', 'yes'),
(18, 'smtp_port', '25', 'yes'),
(19, 'smtp_user', 'zts1993@njut.edu.cn', 'yes'),
(20, 'from_email', 'zts1993@njut.edu.cn', 'yes'),
(21, 'smtp_pass', '', 'yes'),
(22, 'pager', '5', 'yes'),
(23, 'ome_info', 'original', 'yes'),
(24, 'db_fieldtype_check', '0', 'yes'),
(25, 'DEFAULT_FILTER', 'htmlspecialchars', 'yes'),
(26, 'COOKIE_PREFIX', 'greencms_', 'yes'),
(27, 'COOKIE_EXPIRE', '3600', 'yes'),
(28, 'COOKIE_DOMAIN', '', 'yes'),
(29, 'COOKIE_PATH', '/', 'yes'),
(30, 'DB_FIELDS_CACHE', '1', 'yes'),
(31, 'DB_SQL_BUILD_CACHE', '1', 'yes');

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}plugin`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}plugin` (
  `plugin_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `plugin_title` char(20) NOT NULL,
  `plugin_description` text NOT NULL,
  `plugin_author` char(20) NOT NULL,
  `plugin_copyright` char(50) NOT NULL,
  `plugin_pubdate` int(10) NOT NULL,
  PRIMARY KEY (`plugin_id`),
  KEY `status` (`plugin_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件信息表' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}posts`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}posts` (
  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT '0',
  `post_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `post_content` longtext NOT NULL,
  `post_title` text NOT NULL,
  `post_status` varchar(20) DEFAULT 'publish',
  `post_comment_status` varchar(20) DEFAULT 'open',
  `post_password` varchar(20) DEFAULT '',
  `post_name` varchar(200) DEFAULT NULL,
  `post_template` varchar(255) NOT NULL DEFAULT 'detail',
  `post_modified` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `post_comment_count` bigint(20) DEFAULT '0',
  `post_view_count` bigint(20) DEFAULT '0',
  `post_type` varchar(255) NOT NULL DEFAULT 'single',
  `post_img` varchar(255) DEFAULT NULL,
  `post_top` smallint(6) DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_status`,`post_date`,`post_id`),
  KEY `post_author` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='文章列表' AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}post_cat`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}post_cat` (
  `pc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pc_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章分类' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}post_meta`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}post_meta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章meta' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}post_tag`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}post_tag` (
  `pt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章标签' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}role`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='角色' AUTO_INCREMENT=6 ;

--
-- 转存表中的数据 `{$db_prefix}role`
--

INSERT INTO `{$db_prefix}role` (`id`, `name`, `pid`, `status`, `remark`) VALUES
(1, '超级管理员', 1, 1, '系统内置超级管理员组'),
(2, '网站管理员', 2, 1, '拥有系统仅此于超级管理员的权限'),
(3, '内容管理员', 3, 1, '拥有发布文章权利'),
(4, '投稿员', 4, 1, '只能投稿默认为未审核'),
(5, '游客', 5, 1, '基本信息修改');

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}role_users`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}role_users` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` bigint(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

--
-- 转存表中的数据 `{$db_prefix}role_users`
--

INSERT INTO `{$db_prefix}role_users` (`role_id`, `user_id`) VALUES
(1, 1);

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}tags`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}tags` (
  `tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(200) NOT NULL DEFAULT '',
  `tag_slug` varchar(200) NOT NULL DEFAULT '',
  `tag_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `slug` (`tag_slug`),
  KEY `name` (`tag_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='标签页' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}user`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}user` (
  `user_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `user_pass` varchar(64) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `user_nicename` varchar(50) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `user_email` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `user_url` varchar(100) CHARACTER SET utf8 DEFAULT '',
  `user_registered` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `user_activation_key` varchar(60) CHARACTER SET utf8 DEFAULT '',
  `user_status` int(11) DEFAULT '1',
  `user_intro` text CHARACTER SET utf8,
  `user_level` smallint(6) NOT NULL DEFAULT '10',
  `user_session` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- 表的结构 `{$db_prefix}user_detail`
--

CREATE TABLE IF NOT EXISTS `{$db_prefix}user_detail` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_into` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户详细信息' AUTO_INCREMENT=1 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
