/*
Navicat MySQL Data Transfer

Source Server         : 本地MySQL
Source Server Version : 50617
Source Host           : localhost:3306
Source Database       : greencms-sae

Target Server Type    : MYSQL
Target Server Version : 50617
File Encoding         : 65001

Date: 2014-06-11 17:14:16
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `green_access`
-- ----------------------------
DROP TABLE IF EXISTS `green_access`;
CREATE TABLE `green_access` (
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

-- ----------------------------
-- Table structure for `green_addons`
-- ----------------------------
DROP TABLE IF EXISTS `green_addons`;
CREATE TABLE `green_addons` (
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
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='插件表';

-- ----------------------------
-- Records of green_addons
-- ----------------------------
INSERT INTO `green_addons` VALUES ('9', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"2\",\"comment_uid_youyan\":\"1894186\",\"comment_short_name_duoshuo\":\"greencmsduoshuo\",\"comment_form_pos_duoshuo\":\"buttom\",\"comment_data_list_duoshuo\":\"10\",\"comment_data_order_duoshuo\":\"desc\"}', 'xjh1994', '0.1', '1380273962', '0');

-- ----------------------------
-- Table structure for `green_cats`
-- ----------------------------
DROP TABLE IF EXISTS `green_cats`;
CREATE TABLE `green_cats` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_father` bigint(10) NOT NULL DEFAULT '0',
  `cat_slug` varchar(200) NOT NULL DEFAULT '',
  `cat_name` varchar(200) NOT NULL DEFAULT '',
  `cat_order` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `slug` (`cat_slug`),
  KEY `name` (`cat_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='分类信息表';

-- ----------------------------
-- Records of green_cats
-- ----------------------------
INSERT INTO `green_cats` VALUES ('1', '0', 'test_cat1', '测试分类', '0');
INSERT INTO `green_cats` VALUES ('2', '1', 'test_cat2', '测试子分类', '0');

-- ----------------------------
-- Table structure for `green_comments`
-- ----------------------------
DROP TABLE IF EXISTS `green_comments`;
CREATE TABLE `green_comments` (
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
-- Table structure for `green_feedback`
-- ----------------------------
DROP TABLE IF EXISTS `green_feedback`;
CREATE TABLE `green_feedback` (
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
-- Table structure for `green_form`
-- ----------------------------
DROP TABLE IF EXISTS `green_form`;
CREATE TABLE `green_form` (
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
-- Table structure for `green_hooks`
-- ----------------------------
DROP TABLE IF EXISTS `green_hooks`;
CREATE TABLE `green_hooks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '主键',
  `name` varchar(40) NOT NULL DEFAULT '' COMMENT '钩子名称',
  `description` text NOT NULL COMMENT '描述',
  `type` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '类型',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  `addons` varchar(255) NOT NULL DEFAULT '' COMMENT '钩子挂载的插件 ''，''分割',
  PRIMARY KEY (`id`),
  UNIQUE KEY `搜索索引` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='Hooks表\r\n';

-- ----------------------------
-- Records of green_hooks
-- ----------------------------
INSERT INTO `green_hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '');
INSERT INTO `green_hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop');
INSERT INTO `green_hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', '');
INSERT INTO `green_hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'SocialComment,baidushare');
INSERT INTO `green_hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '');
INSERT INTO `green_hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', '');
INSERT INTO `green_hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor');
INSERT INTO `green_hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin');
INSERT INTO `green_hooks` VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1382596073', '');
INSERT INTO `green_hooks` VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', 'Editor');
INSERT INTO `green_hooks` VALUES ('16', 'app_begin', '应用开始', '2', '1384481614', '');

-- ----------------------------
-- Table structure for `green_kv`
-- ----------------------------
DROP TABLE IF EXISTS `green_kv`;
CREATE TABLE `green_kv` (
  `kv_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kv_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kv_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='key-value通用信息存储';

-- ----------------------------
-- Records of green_kv
-- ----------------------------
INSERT INTO `green_kv` VALUES ('1', 'home_theme', 'NovaGreenStudio');
INSERT INTO `green_kv` VALUES ('2', 'theme_NovaGreenStudio', 'enabled');
INSERT INTO `green_kv` VALUES ('3', 'theme_Twentytwelve', 'enabled');

-- ----------------------------
-- Table structure for `green_links`
-- ----------------------------
DROP TABLE IF EXISTS `green_links`;
CREATE TABLE `green_links` (
  `link_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `link_sort` smallint(25) DEFAULT '0',
  `link_url` varchar(255) DEFAULT '',
  `link_name` varchar(255) DEFAULT '',
  `link_tag` varchar(255) DEFAULT '',
  `link_description` varchar(255) DEFAULT '',
  `link_visible` tinyint(20) DEFAULT '1',
  `link_img` varchar(255) DEFAULT NULL,
  `link_group_id` bigint(20) DEFAULT '0',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友情链接';

-- ----------------------------
-- Records of green_links
-- ----------------------------

-- ----------------------------
-- Table structure for `green_link_group`
-- ----------------------------
DROP TABLE IF EXISTS `green_link_group`;
CREATE TABLE `green_link_group` (
  `link_group_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `link_id` bigint(20) DEFAULT NULL,
  `link_group_name` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`link_group_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='链接分类表';

-- ----------------------------
-- Records of green_link_group
-- ----------------------------

-- ----------------------------
-- Table structure for `green_log`
-- ----------------------------
DROP TABLE IF EXISTS `green_log`;
CREATE TABLE `green_log` (
  `log_id` int(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录';

-- ----------------------------
-- Records of green_log
-- ----------------------------

-- ----------------------------
-- Table structure for `green_login_log`
-- ----------------------------
DROP TABLE IF EXISTS `green_login_log`;
CREATE TABLE `green_login_log` (
  `login_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `log_user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_geo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `log_ip` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_status` smallint(5) DEFAULT NULL,
  PRIMARY KEY (`login_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登录信息记录';

-- ----------------------------
-- Records of green_login_log
-- ----------------------------

-- ----------------------------
-- Table structure for `green_menu`
-- ----------------------------
DROP TABLE IF EXISTS `green_menu`;
CREATE TABLE `green_menu` (
  `menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_pid` bigint(20) NOT NULL DEFAULT '0',
  `menu_sort` bigint(20) NOT NULL DEFAULT '99',
  `menu_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `menu_action` varchar(255) CHARACTER SET utf8 DEFAULT '_self',
  `menu_function` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `menu_position` varchar(255) CHARACTER SET utf8 DEFAULT 'head',
  `menu_url` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单栏';

-- ----------------------------
-- Records of green_menu
-- ----------------------------
INSERT INTO `green_menu` VALUES ('1', '0', '10000', '测试分类', '_self', 'getCatURLByID', 'head', '1');
INSERT INTO `green_menu` VALUES ('2', '0', '99', '空链接', '_self', 'none', 'head', null);
INSERT INTO `green_menu` VALUES ('3', '0', '99', '测试页面', '_self', 'getSingleURLByID', 'head', '[\"2\",\"page\"]');
INSERT INTO `green_menu` VALUES ('4', '0', '99', '测试文章', '_self', 'getSingleURLByID', 'head', '[\"1\",\"single\"]');

-- ----------------------------
-- Table structure for `green_mysql`
-- ----------------------------
DROP TABLE IF EXISTS `green_mysql`;
CREATE TABLE `green_mysql` (
  `mysql_id` bigint(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`mysql_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- ----------------------------
-- Records of green_mysql
-- ----------------------------

-- ----------------------------
-- Table structure for `green_node`
-- ----------------------------
DROP TABLE IF EXISTS `green_node`;
CREATE TABLE `green_node` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限节点表';

-- ----------------------------
-- Records of green_node
-- ----------------------------

-- ----------------------------
-- Table structure for `green_options`
-- ----------------------------
DROP TABLE IF EXISTS `green_options`;
CREATE TABLE `green_options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=46 DEFAULT CHARSET=utf8 COMMENT='选项表';

-- ----------------------------
-- Records of green_options
-- ----------------------------
INSERT INTO `green_options` VALUES ('1', 'site_url', 'http://127.0.0.1/greencms-sae/1', 'yes');
INSERT INTO `green_options` VALUES ('2', 'title', 'GreenCMS SAE', 'yes');
INSERT INTO `green_options` VALUES ('3', 'keywords', 'GreenCMS v2 based on ThinkPHP 3.2.1', 'yes');
INSERT INTO `green_options` VALUES ('4', 'description', 'GreenCMS created by GreenStudio ', 'yes');
INSERT INTO `green_options` VALUES ('5', 'foot', '', 'yes');
INSERT INTO `green_options` VALUES ('6', 'ip_geo', '1', 'yes');
INSERT INTO `green_options` VALUES ('7', 'software_author', 'GreenStudio', 'yes');
INSERT INTO `green_options` VALUES ('8', 'widget_about_us', '关于我们', 'yes');
INSERT INTO `green_options` VALUES ('9', 'software_homepage', 'http://www.greencms.net', 'yes');
INSERT INTO `green_options` VALUES ('10', 'software_version', 'v2.1.0602', 'yes');
INSERT INTO `green_options` VALUES ('11', 'software_name', 'GreenCMS v2', 'yes');
INSERT INTO `green_options` VALUES ('12', 'LOG_RECORD', '1', 'yes');
INSERT INTO `green_options` VALUES ('13', 'software_build', '20140602', 'yes');
INSERT INTO `green_options` VALUES ('14', 'HTML_CACHE_ON', '0', 'false');
INSERT INTO `green_options` VALUES ('15', 'sqlFileSize', '500000000', 'yes');
INSERT INTO `green_options` VALUES ('16', 'send_mail', '1', 'yes');
INSERT INTO `green_options` VALUES ('17', 'smtp_host', 'mail.njut.edu.cn', 'yes');
INSERT INTO `green_options` VALUES ('18', 'smtp_port', '25', 'yes');
INSERT INTO `green_options` VALUES ('19', 'smtp_user', 'test@njut.edu.cn', 'yes');
INSERT INTO `green_options` VALUES ('20', 'from_email', 'test@njut.edu.cn', 'yes');
INSERT INTO `green_options` VALUES ('21', 'smtp_pass', ' ', 'yes');
INSERT INTO `green_options` VALUES ('22', 'PAGER', '20', 'yes');
INSERT INTO `green_options` VALUES ('23', 'ome_info', 'original', 'yes');
INSERT INTO `green_options` VALUES ('24', 'db_fieldtype_check', '0', 'yes');
INSERT INTO `green_options` VALUES ('25', 'DEFAULT_FILTER', 'htmlspecialchars', 'yes');
INSERT INTO `green_options` VALUES ('26', 'COOKIE_PREFIX', 'greencms_', 'yes');
INSERT INTO `green_options` VALUES ('27', 'COOKIE_EXPIRE', '3600', 'yes');
INSERT INTO `green_options` VALUES ('28', 'COOKIE_DOMAIN', '', 'yes');
INSERT INTO `green_options` VALUES ('29', 'COOKIE_PATH', '/', 'yes');
INSERT INTO `green_options` VALUES ('30', 'DB_FIELDS_CACHE', '1', 'yes');
INSERT INTO `green_options` VALUES ('31', 'DB_SQL_BUILD_CACHE', '1', 'yes');
INSERT INTO `green_options` VALUES ('32', 'sql_mail', '', 'yes');
INSERT INTO `green_options` VALUES ('33', 'SHOW_CHROME_TRACE', '0', 'yes');
INSERT INTO `green_options` VALUES ('34', 'users_can_register', 'on', 'yes');
INSERT INTO `green_options` VALUES ('35', 'feed_open', '1', 'yes');
INSERT INTO `green_options` VALUES ('36', 'feed_num', '20', 'yes');
INSERT INTO `green_options` VALUES ('37', 'Weixin_reply_subscribe', '欢迎使用Z的博客微信服务平台！回复help获得使用帮助', 'yes');
INSERT INTO `green_options` VALUES ('38', 'Weixin_appid', ' ', 'yes');
INSERT INTO `green_options` VALUES ('39', 'Weixin_secret', ' ', 'yes');
INSERT INTO `green_options` VALUES ('40', 'Weixin_menu', ' ', 'yes');
INSERT INTO `green_options` VALUES ('41', 'weixin_token', ' ', 'yes');
INSERT INTO `green_options` VALUES ('42', 'home_url_model', '0', 'yes');
INSERT INTO `green_options` VALUES ('43', 'home_cat_model', 'native', 'yes');
INSERT INTO `green_options` VALUES ('44', 'home_tag_model', 'native', 'yes');
INSERT INTO `green_options` VALUES ('45', 'home_post_model', 'native', 'yes');

-- ----------------------------
-- Table structure for `green_plugin`
-- ----------------------------
DROP TABLE IF EXISTS `green_plugin`;
CREATE TABLE `green_plugin` (
  `plugin_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `plugin_title` char(20) NOT NULL,
  `plugin_description` text NOT NULL,
  `plugin_author` char(20) NOT NULL,
  `plugin_copyright` char(50) NOT NULL,
  `plugin_pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`plugin_id`),
  KEY `status` (`plugin_status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='插件信息表';

-- ----------------------------
-- Records of green_plugin
-- ----------------------------
INSERT INTO `green_plugin` VALUES ('1', '1', 'Duoshuo', '多说第三方评论', 'XJH1994', 'GreenCMS', '2014-06-11 17:08:36');

-- ----------------------------
-- Table structure for `green_posts`
-- ----------------------------
DROP TABLE IF EXISTS `green_posts`;
CREATE TABLE `green_posts` (
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
  `post_modified` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `post_comment_count` bigint(20) DEFAULT '0',
  `post_view_count` bigint(20) DEFAULT '0',
  `post_type` varchar(255) NOT NULL DEFAULT 'single',
  `post_img` varchar(255) DEFAULT NULL,
  `post_top` smallint(6) DEFAULT '0',
  `post_url` varchar(255) ,

  PRIMARY KEY (`post_id`),
  KEY `post_name` (`post_name`),
  KEY `type_status_date` (`post_status`,`post_date`,`post_id`),
  KEY `post_author` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='文章列表';

-- ----------------------------
-- Records of green_posts
-- ----------------------------
INSERT INTO `green_posts` VALUES ('1', '1', '2014-06-11 17:08:36', '你好,世界', '欢迎使用GreenCMS', 'publish', 'open', '', 'helloworld', 'detail', '2014-06-11 17:08:36', '0', '0', 'single', null, '0');
INSERT INTO `green_posts` VALUES ('2', '1', '2014-06-11 17:08:36', '欢迎使用', '这是一个文章测试页面', 'publish', 'open', '', 'testpage', 'detail', '2014-06-11 17:08:36', '0', '0', 'page', null, '0');

-- ----------------------------
-- Table structure for `green_post_cat`
-- ----------------------------
DROP TABLE IF EXISTS `green_post_cat`;
CREATE TABLE `green_post_cat` (
  `pc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章分类';

-- ----------------------------
-- Records of green_post_cat
-- ----------------------------
INSERT INTO `green_post_cat` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for `green_post_meta`
-- ----------------------------
DROP TABLE IF EXISTS `green_post_meta`;
CREATE TABLE `green_post_meta` (
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
-- Table structure for `green_post_tag`
-- ----------------------------
DROP TABLE IF EXISTS `green_post_tag`;
CREATE TABLE `green_post_tag` (
  `pt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章标签';

-- ----------------------------
-- Records of green_post_tag
-- ----------------------------
INSERT INTO `green_post_tag` VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for `green_role`
-- ----------------------------
DROP TABLE IF EXISTS `green_role`;
CREATE TABLE `green_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='角色';

-- ----------------------------
-- Records of green_role
-- ----------------------------
INSERT INTO `green_role` VALUES ('1', '超级管理员', '1', '1', '系统内置超级管理员组');
INSERT INTO `green_role` VALUES ('2', '网站管理员', '2', '1', '拥有系统仅此于超级管理员的权限');
INSERT INTO `green_role` VALUES ('3', '内容管理员', '3', '1', '拥有发布文章权利');
INSERT INTO `green_role` VALUES ('4', '投稿员', '4', '1', '只能投稿默认为未审核');
INSERT INTO `green_role` VALUES ('5', '游客', '5', '1', '基本信息修改');

-- ----------------------------
-- Table structure for `green_role_users`
-- ----------------------------
DROP TABLE IF EXISTS `green_role_users`;
CREATE TABLE `green_role_users` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` bigint(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表';

-- ----------------------------
-- Records of green_role_users
-- ----------------------------
INSERT INTO `green_role_users` VALUES ('1', '1');

-- ----------------------------
-- Table structure for `green_tags`
-- ----------------------------
DROP TABLE IF EXISTS `green_tags`;
CREATE TABLE `green_tags` (
  `tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(200) NOT NULL DEFAULT '',
  `tag_slug` varchar(200) NOT NULL DEFAULT '',
  `tag_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `slug` (`tag_slug`),
  KEY `name` (`tag_name`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='标签页';

-- ----------------------------
-- Records of green_tags
-- ----------------------------
INSERT INTO `green_tags` VALUES ('1', '测试标签', 'test_tag', '0');
INSERT INTO `green_tags` VALUES ('2', 'hello', 'hello', '0');

-- ----------------------------
-- Table structure for `green_user`
-- ----------------------------
DROP TABLE IF EXISTS `green_user`;
CREATE TABLE `green_user` (
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
-- Records of green_user
-- ----------------------------
INSERT INTO `green_user` VALUES ('1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', '管理员', 'admin@njut.asia', '', '2014-06-11 17:06:35', '', '1', '我是admin，欢迎使用', '2', '2987c46d0155e9c8909f935849287609');

-- ----------------------------
-- Table structure for `green_user_detail`
-- ----------------------------
DROP TABLE IF EXISTS `green_user_detail`;
CREATE TABLE `green_user_detail` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_into` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户详细信息';

-- ----------------------------
-- Records of green_user_detail
-- ----------------------------

-- ----------------------------
-- Table structure for `green_user_sns`
-- ----------------------------
DROP TABLE IF EXISTS `green_user_sns`;
CREATE TABLE `green_user_sns` (
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
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of green_user_sns
-- ----------------------------

-- ----------------------------
-- Table structure for `green_weixinaction`
-- ----------------------------
DROP TABLE IF EXISTS `green_weixinaction`;
CREATE TABLE `green_weixinaction` (
  `wx_action_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `action_type` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `action_name` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `reply_type` varchar(20) CHARACTER SET latin1 DEFAULT NULL,
  `reply_id` bigint(20) DEFAULT NULL,
  `time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`wx_action_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信事件表';

-- ----------------------------
-- Records of green_weixinaction
-- ----------------------------

-- ----------------------------
-- Table structure for `green_weixinlog`
-- ----------------------------
DROP TABLE IF EXISTS `green_weixinlog`;
CREATE TABLE `green_weixinlog` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信记录表';

-- ----------------------------
-- Records of green_weixinlog
-- ----------------------------

-- ----------------------------
-- Table structure for `green_weixinre`
-- ----------------------------
DROP TABLE IF EXISTS `green_weixinre`;
CREATE TABLE `green_weixinre` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信模块预设定回复 表';

-- ----------------------------
-- Records of green_weixinre
-- ----------------------------

-- ----------------------------
-- Table structure for `green_weixinsend`
-- ----------------------------
DROP TABLE IF EXISTS `green_weixinsend`;
CREATE TABLE `green_weixinsend` (
  `weixin_send_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `MsgId_to` varchar(255) DEFAULT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `CreateTime` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`weixin_send_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of green_weixinsend
-- ----------------------------

-- ----------------------------
-- Table structure for `green_weixinuser`
-- ----------------------------
DROP TABLE IF EXISTS `green_weixinuser`;
CREATE TABLE `green_weixinuser` (
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信模块用户信息表';

-- ----------------------------
-- Records of green_weixinuser
-- ----------------------------
