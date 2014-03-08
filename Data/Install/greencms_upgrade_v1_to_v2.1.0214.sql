DROP TABLE IF EXISTS `{$db_prefix}feedback`;
ALTER TABLE `{$db_prefix}form_apply` RENAME TO `{$db_prefix}form`;

-- ----------------------------
-- Table structure for `{$db_prefix}kv`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}kv`;
CREATE TABLE `{$db_prefix}kv` (
  `kv_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kv_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kv_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='key-value通用信息存储';

-- ----------------------------
-- Records of kv
-- ----------------------------
INSERT INTO `{$db_prefix}kv` VALUES ('1', 'home_theme', 'Vena');



-- ----------------------------
-- Table structure for `menu`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}menu`;
CREATE TABLE `{$db_prefix}menu` (
  `menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_pid` bigint(20) NOT NULL DEFAULT '0',
  `menu_sort` bigint(20) NOT NULL DEFAULT '99',
  `menu_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `menu_action` varchar(255) CHARACTER SET utf8 DEFAULT '_self',
  `menu_function` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_position` varchar(255) CHARACTER SET utf8 DEFAULT 'head',
  `menu_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=101 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单栏';

-- ----------------------------
-- Records of {$db_prefix}menu
-- ----------------------------
INSERT INTO `{$db_prefix}menu` VALUES ('100', '0', '10000', '科技新闻', '_self', 'getCatURLByID', 'head', '1');
INSERT INTO `{$db_prefix}menu` VALUES ('2', '0', '99', '消息公告', '_self', 'none', 'head', null);
INSERT INTO `{$db_prefix}menu` VALUES ('3', '2', '120', '作品发布', '_self', 'getCatURLByID', 'head', '15');
INSERT INTO `{$db_prefix}menu` VALUES ('4', '2', '111', '活动发布', '_self', 'getCatURLByID', 'head', '20');
INSERT INTO `{$db_prefix}menu` VALUES ('5', '0', '99', '联系我们', '_self', 'getSingleURLByID', 'head', '[\"95\",\"page\"]');
INSERT INTO `{$db_prefix}menu` VALUES ('6', '12', '99', '加入我们', '_self', 'U', 'head', 'Form/apply');
INSERT INTO `{$db_prefix}menu` VALUES ('7', '100', '99', '旧版入口', '_self', 'direct', 'head', 'http://green.njtech.edu.cn');
INSERT INTO `{$db_prefix}menu` VALUES ('10', '0', '99', '技术分享', '_self', 'getCatURLByID', 'head', '18');
INSERT INTO `{$db_prefix}menu` VALUES ('12', '0', '99', '加入我们', '_self', null, 'head', null);




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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表';

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `{$db_prefix}user` VALUES ('1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', '管理员', 'admin@njut.asia', '', '2014-02-12 00:02:54', '', '1', '我是admin，欢迎使用', '2', '505bdede0719246d944fc265d81965ef');



-- ----------------------------
-- Table structure for `user_detail`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}user_detail`;
CREATE TABLE `{$db_prefix}user_detail` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_into` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户详细信息';

-- ----------------------------
-- Table structure for `mysql`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}mysql`;
CREATE TABLE `{$db_prefix}mysql` (
  `mysql_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mysql_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


-- ----------------------------
-- Table structure for `plugin`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}plugin`;
CREATE TABLE `{$db_prefix}plugin` (
  `plugin_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `plugin_title` char(20) NOT NULL,
  `plugin_description` text NOT NULL,
  `plugin_author` char(20) NOT NULL,
  `plugin_copyright` char(50) NOT NULL,
  `plugin_pubdate` int(10) NOT NULL,
  PRIMARY KEY (`plugin_id`),
  KEY `status` (`plugin_status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='插件信息表';

-- ----------------------------
-- Table structure for `{$db_prefix}log`
-- ----------------------------
DROP TABLE IF EXISTS `{$db_prefix}log`;
CREATE TABLE `{$db_prefix}log` (
  `log_id` int(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录';



-- ----------------------------
-- Table structure for `login_log`
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


ALTER TABLE `{$db_prefix}posts`
CHANGE `comment_count` `post_comment_count` bigint(20) DEFAULT 0;
ALTER TABLE `{$db_prefix}posts`
CHANGE `comment_status` `post_comment_status` varchar(20) DEFAULT 'open';
ALTER TABLE `{$db_prefix}posts`
CHANGE `view_count` `post_view_count` bigint(20) DEFAULT '0';
ALTER TABLE `{$db_prefix}posts`
ADD COLUMN `post_template` varchar(255) NOT NULL DEFAULT 'detail';