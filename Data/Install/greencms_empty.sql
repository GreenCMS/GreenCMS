/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : green

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-03-02 10:04:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `{$db_prefix}weixinuser`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}weixinuser`;
CREATE TABLE `{$db_prefix}weixinuser` (
  `weixin_userid` bigint(20) NOT NULL AUTO_INCREMENT,
  `openid` varchar(255) DEFAULT '',
  `subscribe` smallint(5) DEFAULT NULL,
  `nickname` varchar(255) DEFAULT NULL,
  `sex` smallint(5) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `province` varchar(50) DEFAULT NULL,
  `country` varchar(50) DEFAULT NULL,
  `headimgurl` varchar(255) DEFAULT NULL,
  `subscribe_time` bigint(50) DEFAULT NULL,
  PRIMARY KEY (`weixin_userid`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='微信模块用户信息表';

-- ----------------------------
-- Records of green_weixinuser
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}weixinsend`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}weixinsend`;
CREATE TABLE `{$db_prefix}weixinsend` (
  `weixin_send_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `MsgId_to` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `CreateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`weixin_send_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of green_weixinsend
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}weixinre`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}weixinre`;
CREATE TABLE `{$db_prefix}weixinre` (
  `wx_re_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `type` varchar(64) DEFAULT NULL,
  `mediaId` varchar(64) DEFAULT NULL,
  `title` varchar(64) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `picurl` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `group` varchar(20) DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`wx_re_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='微信模块预设定回复 表';

-- ----------------------------
-- Records of green_weixinre
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}weixinlog`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}weixinlog`;
CREATE TABLE `{$db_prefix}weixinlog` (
  `log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `MsgId` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `FromUserName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `ToUserName` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `CreateTime` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `Content` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `MsgType` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Location_X` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Location_Y` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Scale` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `Label` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `PicUrl` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `isread` smallint(5) DEFAULT '0',
  PRIMARY KEY (`log_id`),
  KEY `createtime` (`CreateTime`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='微信记录表';

-- ----------------------------
-- Records of green_weixinlog
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}weixinaction`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}weixinaction`;
CREATE TABLE `{$db_prefix}weixinaction` (
  `wx_action_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `action_name` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `reply_type` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `reply_id` bigint(20) DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`wx_action_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='微信事件表';

-- ----------------------------
-- Records of green_weixinaction
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}user_detail`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}user_detail`;
CREATE TABLE `{$db_prefix}user_detail` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_into` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户详细信息';

-- ----------------------------
-- Records of green_user_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}user`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}user`;
CREATE TABLE `{$db_prefix}user` (
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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表';

-- ----------------------------
-- Records of green_user
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}tags`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}tags`;
CREATE TABLE `{$db_prefix}tags` (
  `tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(200) NOT NULL DEFAULT '',
  `tag_slug` varchar(200) NOT NULL DEFAULT '',
  `tag_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `slug` (`tag_slug`),
  KEY `name` (`tag_name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='标签页';

-- ----------------------------
-- Records of green_tags
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}role_users`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}role_users`;
CREATE TABLE `{$db_prefix}role_users` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` bigint(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of green_role_users
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}role`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}role`;
CREATE TABLE `{$db_prefix}role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of green_role
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}posts`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}posts`;
CREATE TABLE `{$db_prefix}posts` (
  `post_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT '0',
  `post_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `post_content` longtext NOT NULL,
  `post_title` varchar(255) NOT NULL,
  `post_status` varchar(20) DEFAULT 'publish',
  `post_comment_status` varchar(20) DEFAULT 'open',
  `post_password` varchar(20) DEFAULT '',
  `post_name` varchar(200) DEFAULT NULL,
  `post_template` varchar(255) NOT NULL DEFAULT 'single',
  `post_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `post_comment_count` bigint(20) DEFAULT '0',
  `post_view_count` bigint(20) DEFAULT '0',
  `post_type` varchar(255) NOT NULL DEFAULT 'single',
  `post_img` varchar(255) DEFAULT NULL,
  `post_top` smallint(6) DEFAULT '0',
  PRIMARY KEY (`post_id`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_status`,`post_date`,`post_id`),
  KEY `post_author` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='文章列表';

-- ----------------------------
-- Records of green_posts
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}post_tag`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}post_tag`;
CREATE TABLE `{$db_prefix}post_tag` (
  `pt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章标签';

-- ----------------------------
-- Records of green_post_tag
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}post_meta`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}post_meta`;
CREATE TABLE `{$db_prefix}post_meta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章meta';

-- ----------------------------
-- Records of green_post_meta
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}post_cat`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}post_cat`;
CREATE TABLE `{$db_prefix}post_cat` (
  `pc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章分类';

-- ----------------------------
-- Records of green_post_cat
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}plugin`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}plugin`;
CREATE TABLE `{$db_prefix}plugin` (
  `plugin_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `plugin_title` char(20) NOT NULL,
  `plugin_description` text NOT NULL,
  `plugin_author` char(20) NOT NULL,
  `plugin_copyright` char(50) NOT NULL,
  `plugin_pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`plugin_id`),
  KEY `status` (`plugin_status`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='插件信息表';

-- ----------------------------
-- Records of green_plugin
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}options`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}options`;
CREATE TABLE `{$db_prefix}options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='选项表';

-- ----------------------------
-- Records of green_options
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}node`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}node`;
CREATE TABLE `{$db_prefix}node` (
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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='权限节点表';

-- ----------------------------
-- Records of green_node
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}mysql`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}mysql`;
CREATE TABLE `{$db_prefix}mysql` (
  `mysql_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mysql_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of green_mysql
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}menu`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}menu`;
CREATE TABLE `{$db_prefix}menu` (
  `menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_pid` bigint(20) NOT NULL DEFAULT '0',
  `menu_sort` bigint(20) NOT NULL DEFAULT '99',
  `menu_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `menu_action` varchar(255) CHARACTER SET utf8 DEFAULT '_self',
  `menu_function` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `menu_position` varchar(255) CHARACTER SET utf8 DEFAULT 'head',
  `menu_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单栏';

-- ----------------------------
-- Records of green_menu
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}login_log`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}login_log`;
CREATE TABLE `{$db_prefix}login_log` (
  `login_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `log_user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_geo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`login_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登录信息记录';

-- ----------------------------
-- Records of green_login_log
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}log`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}log`;
CREATE TABLE `{$db_prefix}log` (
  `log_id` int(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录';

-- ----------------------------
-- Records of green_log
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}links`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}links`;
CREATE TABLE `{$db_prefix}links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_sort` smallint(25) DEFAULT '0',
  `link_url` varchar(255) DEFAULT '',
  `link_name` varchar(255) DEFAULT '',
  `link_tag` varchar(255) DEFAULT '',
  `link_description` varchar(255) DEFAULT '',
  `link_visible` tinyint(20) DEFAULT '1',
  `link_img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of green_links
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}kv`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}kv`;
CREATE TABLE `{$db_prefix}kv` (
  `kv_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kv_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kv_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='key-value通用信息存储';

-- ----------------------------
-- Records of green_kv
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}hooks`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}hooks`;
CREATE TABLE `{$db_prefix}hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `搜索索引` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='Hooks表\r\n';

-- ----------------------------
-- Records of green_hooks
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}form`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}form`;
CREATE TABLE `{$db_prefix}form` (
  `fa_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`fa_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='申请表';

-- ----------------------------
-- Records of green_form
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}feedback`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}feedback`;
CREATE TABLE `{$db_prefix}feedback` (
  `fid` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'anonymous',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Waiting',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='反馈信息';

-- ----------------------------
-- Records of green_feedback
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}comments`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}comments`;
CREATE TABLE `{$db_prefix}comments` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论信息表';

-- ----------------------------
-- Records of green_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}cats`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}cats`;
CREATE TABLE `{$db_prefix}cats` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_father` bigint(10) NOT NULL DEFAULT '0',
  `cat_slug` varchar(200) NOT NULL DEFAULT '',
  `cat_name` varchar(200) NOT NULL DEFAULT '',
  `cat_order` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `slug` (`cat_slug`),
  KEY `name` (`cat_name`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='分类信息表';

-- ----------------------------
-- Records of green_cats
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}addons`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}addons`;
CREATE TABLE `{$db_prefix}addons` (
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
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of green_addons
-- ----------------------------

-- ----------------------------
-- Table structure for `{$db_prefix}access`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}access`;
CREATE TABLE `{$db_prefix}access` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '1',
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表';

-- ----------------------------
-- Records of green_access
-- ----------------------------
