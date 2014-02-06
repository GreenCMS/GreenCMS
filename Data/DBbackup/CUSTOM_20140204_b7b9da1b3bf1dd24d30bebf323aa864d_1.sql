# -----------------------------------------------------------
# GreenCMS2014  Powered By 绿荫工作室  database backup files
# URL: http://green.njut.asia
# Type: 管理员后台手动备份
# Description:当前SQL文件包含了表：access、cats、comments、feedback、form、kv、links、log、login_log、menu、node、options、plugin、post_cat、post_meta、post_tag、posts、role、role_users、tags、user、user_detail的结构信息，表：access、cats、comments、feedback、form、kv、links、log、login_log、menu、node、options、plugin、post_cat、post_meta、post_tag、posts的数据
# Time: 2014-02-04 20:34:38
# -----------------------------------------------------------
# 当前SQL卷标：#1
# -----------------------------------------------------------


# 数据库表：access 结构信息
DROP TABLE IF EXISTS `access`;
CREATE TABLE `access` (
  `role_id` smallint(6) unsigned NOT NULL DEFAULT '1',
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `module` varchar(50) DEFAULT NULL,
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='权限分配表' ;

# 数据库表：cats 结构信息
DROP TABLE IF EXISTS `cats`;
CREATE TABLE `cats` (
  `cat_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `cat_father` bigint(10) NOT NULL DEFAULT '0',
  `cat_slug` varchar(200) NOT NULL DEFAULT '',
  `cat_name` varchar(200) NOT NULL DEFAULT '',
  `cat_order` bigint(20) DEFAULT NULL,
  PRIMARY KEY (`cat_id`),
  UNIQUE KEY `slug` (`cat_slug`),
  KEY `name` (`cat_name`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COMMENT='分类信息表' ;

# 数据库表：comments 结构信息
DROP TABLE IF EXISTS `comments`;
CREATE TABLE `comments` (
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
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='评论信息表' ;

# 数据库表：feedback 结构信息
DROP TABLE IF EXISTS `feedback`;
CREATE TABLE `feedback` (
  `fid` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'anonymous',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `comment` varchar(255) COLLATE utf8_unicode_ci DEFAULT 'Waiting',
  PRIMARY KEY (`fid`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='反馈信息' ;

# 数据库表：form 结构信息
DROP TABLE IF EXISTS `form`;
CREATE TABLE `form` (
  `fa_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `class` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `direction` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `message` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`fa_id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='申请表' ;

# 数据库表：kv 结构信息
DROP TABLE IF EXISTS `kv`;
CREATE TABLE `kv` (
  `kv_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kv_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kv_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kv_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='key-value通用信息存储' ;

# 数据库表：links 结构信息
DROP TABLE IF EXISTS `links`;
CREATE TABLE `links` (
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
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='友情链接' ;

# 数据库表：log 结构信息
DROP TABLE IF EXISTS `log`;
CREATE TABLE `log` (
  `log_id` int(20) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='系统日志记录' ;

# 数据库表：login_log 结构信息
DROP TABLE IF EXISTS `login_log`;
CREATE TABLE `login_log` (
  `login_log_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `log_user_id` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_user_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_password` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_geo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`login_log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='登录信息记录' ;

# 数据库表：menu 结构信息
DROP TABLE IF EXISTS `menu`;
CREATE TABLE `menu` (
  `menu_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `menu_pid` bigint(20) NOT NULL DEFAULT '0',
  `menu_sort` bigint(20) NOT NULL DEFAULT '99',
  `menu_name` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_action` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_fuction` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_position` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `menu_url` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='菜单栏' ;

# 数据库表：node 结构信息
DROP TABLE IF EXISTS `node`;
CREATE TABLE `node` (
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
) ENGINE=MyISAM AUTO_INCREMENT=49 DEFAULT CHARSET=utf8 COMMENT='权限节点表' ;

# 数据库表：options 结构信息
DROP TABLE IF EXISTS `options`;
CREATE TABLE `options` (
  `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(64) NOT NULL DEFAULT '',
  `option_value` longtext NOT NULL,
  `autoload` varchar(20) NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`)
) ENGINE=MyISAM AUTO_INCREMENT=469 DEFAULT CHARSET=utf8 COMMENT='选项表' ;

# 数据库表：plugin 结构信息
DROP TABLE IF EXISTS `plugin`;
CREATE TABLE `plugin` (
  `plugin_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `plugin_status` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `plugin_title` char(20) NOT NULL,
  `plugin_description` text NOT NULL,
  `plugin_author` char(20) NOT NULL,
  `plugin_copyright` char(50) NOT NULL,
  `plugin_pubdate` int(10) NOT NULL,
  PRIMARY KEY (`plugin_id`),
  KEY `status` (`plugin_status`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COMMENT='插件信息表' ;

# 数据库表：post_cat 结构信息
DROP TABLE IF EXISTS `post_cat`;
CREATE TABLE `post_cat` (
  `pc_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `cat_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pc_id`)
) ENGINE=MyISAM AUTO_INCREMENT=113 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章分类' ;

# 数据库表：post_meta 结构信息
DROP TABLE IF EXISTS `post_meta`;
CREATE TABLE `post_meta` (
  `meta_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章meta' ;

# 数据库表：post_tag 结构信息
DROP TABLE IF EXISTS `post_tag`;
CREATE TABLE `post_tag` (
  `pt_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `tag_id` bigint(20) NOT NULL DEFAULT '0',
  `post_id` bigint(20) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pt_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1659 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='文章标签' ;

# 数据库表：posts 结构信息
DROP TABLE IF EXISTS `posts`;
CREATE TABLE `posts` (
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
) ENGINE=MyISAM AUTO_INCREMENT=123 DEFAULT CHARSET=utf8 COMMENT='文章列表' ;

# 数据库表：role 结构信息
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COMMENT='角色' ;

# 数据库表：role_users 结构信息
DROP TABLE IF EXISTS `role_users`;
CREATE TABLE `role_users` (
  `role_id` mediumint(9) unsigned DEFAULT NULL,
  `user_id` bigint(32) NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户角色表' ;

# 数据库表：tags 结构信息
DROP TABLE IF EXISTS `tags`;
CREATE TABLE `tags` (
  `tag_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tag_name` varchar(200) NOT NULL DEFAULT '',
  `tag_slug` varchar(200) NOT NULL DEFAULT '',
  `tag_group` bigint(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `slug` (`tag_slug`),
  KEY `name` (`tag_name`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='标签页' ;

# 数据库表：user 结构信息
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
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
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户表' ;

# 数据库表：user_detail 结构信息
DROP TABLE IF EXISTS `user_detail`;
CREATE TABLE `user_detail` (
  `user_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `user_into` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='用户详细信息' ;



# 数据库表：access 数据信息
INSERT INTO `access` VALUES ('1','47','2','1','');
INSERT INTO `access` VALUES ('1','44','3','32','');
INSERT INTO `access` VALUES ('1','43','3','32','');
INSERT INTO `access` VALUES ('1','42','3','32','');
INSERT INTO `access` VALUES ('1','41','3','32','');
INSERT INTO `access` VALUES ('1','40','3','32','');
INSERT INTO `access` VALUES ('1','39','3','32','');
INSERT INTO `access` VALUES ('1','38','3','32','');
INSERT INTO `access` VALUES ('1','37','3','32','');
INSERT INTO `access` VALUES ('1','36','3','32','');
INSERT INTO `access` VALUES ('1','35','3','32','');
INSERT INTO `access` VALUES ('1','34','3','32','');
INSERT INTO `access` VALUES ('1','33','3','32','');
INSERT INTO `access` VALUES ('1','32','2','1','');
INSERT INTO `access` VALUES ('1','45','3','26','');
INSERT INTO `access` VALUES ('1','31','3','26','');
INSERT INTO `access` VALUES ('1','30','3','26','');
INSERT INTO `access` VALUES ('1','29','3','26','');
INSERT INTO `access` VALUES ('1','28','3','26','');
INSERT INTO `access` VALUES ('1','27','3','26','');
INSERT INTO `access` VALUES ('1','26','2','1','');
INSERT INTO `access` VALUES ('1','25','3','14','');
INSERT INTO `access` VALUES ('1','24','3','14','');
INSERT INTO `access` VALUES ('1','23','3','14','');
INSERT INTO `access` VALUES ('1','22','3','14','');
INSERT INTO `access` VALUES ('1','21','3','14','');
INSERT INTO `access` VALUES ('1','20','3','14','');
INSERT INTO `access` VALUES ('1','19','3','14','');
INSERT INTO `access` VALUES ('1','18','3','14','');
INSERT INTO `access` VALUES ('1','17','3','14','');
INSERT INTO `access` VALUES ('1','16','3','14','');
INSERT INTO `access` VALUES ('1','15','3','14','');
INSERT INTO `access` VALUES ('1','9','3','14','');
INSERT INTO `access` VALUES ('1','8','3','14','');
INSERT INTO `access` VALUES ('1','14','2','1','');
INSERT INTO `access` VALUES ('1','13','3','4','');
INSERT INTO `access` VALUES ('1','12','3','4','');
INSERT INTO `access` VALUES ('1','11','3','4','');
INSERT INTO `access` VALUES ('1','10','3','4','');
INSERT INTO `access` VALUES ('1','4','2','1','');
INSERT INTO `access` VALUES ('1','7','3','3','');
INSERT INTO `access` VALUES ('1','3','2','1','');
INSERT INTO `access` VALUES ('1','6','3','2','');
INSERT INTO `access` VALUES ('1','5','3','2','');
INSERT INTO `access` VALUES ('1','2','2','1','');
INSERT INTO `access` VALUES ('1','1','1','0','');
INSERT INTO `access` VALUES ('4','1','1','0','');
INSERT INTO `access` VALUES ('4','2','2','1','');
INSERT INTO `access` VALUES ('4','5','3','2','');
INSERT INTO `access` VALUES ('4','6','3','2','');
INSERT INTO `access` VALUES ('2','44','3','32','');
INSERT INTO `access` VALUES ('2','43','3','32','');
INSERT INTO `access` VALUES ('2','42','3','32','');
INSERT INTO `access` VALUES ('2','41','3','32','');
INSERT INTO `access` VALUES ('2','40','3','32','');
INSERT INTO `access` VALUES ('2','39','3','32','');
INSERT INTO `access` VALUES ('2','38','3','32','');
INSERT INTO `access` VALUES ('2','37','3','32','');
INSERT INTO `access` VALUES ('2','36','3','32','');
INSERT INTO `access` VALUES ('2','35','3','32','');
INSERT INTO `access` VALUES ('2','34','3','32','');
INSERT INTO `access` VALUES ('2','33','3','32','');
INSERT INTO `access` VALUES ('2','32','2','1','');
INSERT INTO `access` VALUES ('2','45','3','26','');
INSERT INTO `access` VALUES ('2','31','3','26','');
INSERT INTO `access` VALUES ('2','30','3','26','');
INSERT INTO `access` VALUES ('2','29','3','26','');
INSERT INTO `access` VALUES ('2','28','3','26','');
INSERT INTO `access` VALUES ('2','27','3','26','');
INSERT INTO `access` VALUES ('2','26','2','1','');
INSERT INTO `access` VALUES ('2','25','3','14','');
INSERT INTO `access` VALUES ('2','24','3','14','');
INSERT INTO `access` VALUES ('2','23','3','14','');
INSERT INTO `access` VALUES ('2','22','3','14','');
INSERT INTO `access` VALUES ('2','21','3','14','');
INSERT INTO `access` VALUES ('2','20','3','14','');
INSERT INTO `access` VALUES ('2','19','3','14','');
INSERT INTO `access` VALUES ('2','18','3','14','');
INSERT INTO `access` VALUES ('2','17','3','14','');
INSERT INTO `access` VALUES ('2','16','3','14','');
INSERT INTO `access` VALUES ('2','15','3','14','');
INSERT INTO `access` VALUES ('2','9','3','14','');
INSERT INTO `access` VALUES ('2','8','3','14','');
INSERT INTO `access` VALUES ('2','14','2','1','');
INSERT INTO `access` VALUES ('2','13','3','4','');
INSERT INTO `access` VALUES ('2','12','3','4','');
INSERT INTO `access` VALUES ('2','11','3','4','');
INSERT INTO `access` VALUES ('2','10','3','4','');
INSERT INTO `access` VALUES ('2','4','2','1','');
INSERT INTO `access` VALUES ('2','7','3','3','');
INSERT INTO `access` VALUES ('2','3','2','1','');
INSERT INTO `access` VALUES ('2','6','3','2','');
INSERT INTO `access` VALUES ('2','5','3','2','');
INSERT INTO `access` VALUES ('2','2','2','1','');
INSERT INTO `access` VALUES ('2','1','1','0','');
INSERT INTO `access` VALUES ('3','1','1','0','');
INSERT INTO `access` VALUES ('3','2','2','1','');
INSERT INTO `access` VALUES ('3','5','3','2','');
INSERT INTO `access` VALUES ('3','6','3','2','');
INSERT INTO `access` VALUES ('3','26','2','1','');
INSERT INTO `access` VALUES ('3','27','3','26','');
INSERT INTO `access` VALUES ('3','28','3','26','');
INSERT INTO `access` VALUES ('3','29','3','26','');
INSERT INTO `access` VALUES ('3','30','3','26','');
INSERT INTO `access` VALUES ('3','31','3','26','');
INSERT INTO `access` VALUES ('3','45','3','26','');
INSERT INTO `access` VALUES ('5','45','3','26','');
INSERT INTO `access` VALUES ('5','31','3','26','');
INSERT INTO `access` VALUES ('5','30','3','26','');
INSERT INTO `access` VALUES ('11','1','1','0','');
INSERT INTO `access` VALUES ('11','2','2','1','');
INSERT INTO `access` VALUES ('11','5','3','2','');
INSERT INTO `access` VALUES ('11','6','3','2','');
INSERT INTO `access` VALUES ('11','3','2','1','');
INSERT INTO `access` VALUES ('11','7','3','3','');
INSERT INTO `access` VALUES ('11','4','2','1','');
INSERT INTO `access` VALUES ('11','10','3','4','');
INSERT INTO `access` VALUES ('11','11','3','4','');
INSERT INTO `access` VALUES ('11','12','3','4','');
INSERT INTO `access` VALUES ('11','13','3','4','');
INSERT INTO `access` VALUES ('11','14','2','1','');
INSERT INTO `access` VALUES ('11','8','3','14','');
INSERT INTO `access` VALUES ('11','9','3','14','');
INSERT INTO `access` VALUES ('11','15','3','14','');
INSERT INTO `access` VALUES ('11','16','3','14','');
INSERT INTO `access` VALUES ('11','17','3','14','');
INSERT INTO `access` VALUES ('11','18','3','14','');
INSERT INTO `access` VALUES ('11','19','3','14','');
INSERT INTO `access` VALUES ('11','20','3','14','');
INSERT INTO `access` VALUES ('11','21','3','14','');
INSERT INTO `access` VALUES ('11','22','3','14','');
INSERT INTO `access` VALUES ('11','23','3','14','');
INSERT INTO `access` VALUES ('11','24','3','14','');
INSERT INTO `access` VALUES ('11','25','3','14','');
INSERT INTO `access` VALUES ('11','26','2','1','');
INSERT INTO `access` VALUES ('11','27','3','26','');
INSERT INTO `access` VALUES ('11','28','3','26','');
INSERT INTO `access` VALUES ('11','29','3','26','');
INSERT INTO `access` VALUES ('11','30','3','26','');
INSERT INTO `access` VALUES ('11','31','3','26','');
INSERT INTO `access` VALUES ('11','45','3','26','');
INSERT INTO `access` VALUES ('11','32','2','1','');
INSERT INTO `access` VALUES ('11','33','3','32','');
INSERT INTO `access` VALUES ('11','34','3','32','');
INSERT INTO `access` VALUES ('11','35','3','32','');
INSERT INTO `access` VALUES ('11','36','3','32','');
INSERT INTO `access` VALUES ('11','37','3','32','');
INSERT INTO `access` VALUES ('11','38','3','32','');
INSERT INTO `access` VALUES ('11','39','3','32','');
INSERT INTO `access` VALUES ('11','40','3','32','');
INSERT INTO `access` VALUES ('11','41','3','32','');
INSERT INTO `access` VALUES ('11','42','3','32','');
INSERT INTO `access` VALUES ('11','43','3','32','');
INSERT INTO `access` VALUES ('11','44','3','32','');
INSERT INTO `access` VALUES ('11','47','2','1','');
INSERT INTO `access` VALUES ('5','29','3','26','');
INSERT INTO `access` VALUES ('5','28','3','26','');
INSERT INTO `access` VALUES ('5','26','2','1','');
INSERT INTO `access` VALUES ('5','25','3','14','');
INSERT INTO `access` VALUES ('5','24','3','14','');
INSERT INTO `access` VALUES ('5','23','3','14','');
INSERT INTO `access` VALUES ('5','22','3','14','');
INSERT INTO `access` VALUES ('5','21','3','14','');
INSERT INTO `access` VALUES ('5','20','3','14','');
INSERT INTO `access` VALUES ('5','19','3','14','');
INSERT INTO `access` VALUES ('5','18','3','14','');
INSERT INTO `access` VALUES ('5','17','3','14','');
INSERT INTO `access` VALUES ('5','16','3','14','');
INSERT INTO `access` VALUES ('5','15','3','14','');
INSERT INTO `access` VALUES ('5','9','3','14','');
INSERT INTO `access` VALUES ('5','8','3','14','');
INSERT INTO `access` VALUES ('5','14','2','1','');
INSERT INTO `access` VALUES ('5','13','3','4','');
INSERT INTO `access` VALUES ('5','12','3','4','');
INSERT INTO `access` VALUES ('5','11','3','4','');
INSERT INTO `access` VALUES ('5','10','3','4','');
INSERT INTO `access` VALUES ('5','4','2','1','');
INSERT INTO `access` VALUES ('5','6','3','2','');
INSERT INTO `access` VALUES ('5','5','3','2','');
INSERT INTO `access` VALUES ('5','2','2','1','');
INSERT INTO `access` VALUES ('5','1','1','0','');


# 数据库表：cats 数据信息
INSERT INTO `cats` VALUES ('15','20','lastest-news','最新新闻','0');
INSERT INTO `cats` VALUES ('16','20','message','消息公告','0');
INSERT INTO `cats` VALUES ('17','0','published2','作品发布','0');
INSERT INTO `cats` VALUES ('18','17','tech','技术分享','0');
INSERT INTO `cats` VALUES ('19','17','','成员介绍','0');
INSERT INTO `cats` VALUES ('20','0','activity','活动发布','0');


# 数据库表：comments 数据信息
INSERT INTO `comments` VALUES ('1','1','WordPress 先生','','http://wordpress.org/','','2013-01-24 08:23:55','您好，这是一条评论。要删除评论，请先登录，然后再查看这篇文章的评论。登录后您可以看到编辑或者删除评论的选项。','1','0','0');


# 数据库表：feedback 数据信息
INSERT INTO `feedback` VALUES ('1','anonymous','admin@zts1993.com','Waiting');
INSERT INTO `feedback` VALUES ('2','在','admin@nutlab.asia','当时Vad');
INSERT INTO `feedback` VALUES ('3','在','admin@nutlab.asia','当时Vad');
INSERT INTO `feedback` VALUES ('4','阿三地方','a8580160@163.com','asd');
INSERT INTO `feedback` VALUES ('5','Facebook效应','admin0@admin0.com','sdf');
INSERT INTO `feedback` VALUES ('6','anonymous','admin@zts1993.com','Waiting');
INSERT INTO `feedback` VALUES ('7','anonymous','zts1993@126.com','Waiting');
INSERT INTO `feedback` VALUES ('8','anonymous','zts1993@126.com','Waiting');
INSERT INTO `feedback` VALUES ('9','anonymous','admin@zts1993.com','Waiting');


# 数据库表：form 数据信息
INSERT INTO `form` VALUES ('9','魏星','1401120122','15950590883','wx199406@126.com','mobile','学习还不错，也算是拿过一奖学金，能用C#写一些应用程序。');
INSERT INTO `form` VALUES ('10','钱晶','1406110115','18251902021','qianjing@njut.edu.cn','web','我是来打酱油的');
INSERT INTO `form` VALUES ('11','白帆','1405090232','13913305044','baifan0516@gmail.com','bigdata','');
INSERT INTO `form` VALUES ('12','zhang tianshuo','5453453453453','45534534535','admin@zts1993.com','ui','                35347538');


# 数据库表：kv 数据信息


# 数据库表：links 数据信息
INSERT INTO `links` VALUES ('1','1','http://www.zts1993.com','Z的博客','','Z的博客','1','');
INSERT INTO `links` VALUES ('5','0','http://www.greencms.net/','GreenCMS','GreenCMS','GreenCMS','1','');


# 数据库表：log 数据信息


# 数据库表：login_log 数据信息


# 数据库表：menu 数据信息


# 数据库表：node 数据信息
INSERT INTO `node` VALUES ('1','Admin','后台管理','1','网站后台管理项目','10','0','1');
INSERT INTO `node` VALUES ('2','Index','管理首页','1','','1','1','2');
INSERT INTO `node` VALUES ('3','Member','注册会员管理','1','','3','1','2');
INSERT INTO `node` VALUES ('4','Webinfo','系统管理','1','','4','1','2');
INSERT INTO `node` VALUES ('5','index','默认页','1','','5','2','3');
INSERT INTO `node` VALUES ('6','myInfo','我的个人信息','1','','6','2','3');
INSERT INTO `node` VALUES ('7','index','会员首页','1','','7','3','3');
INSERT INTO `node` VALUES ('8','index','管理员列表','1','','8','14','3');
INSERT INTO `node` VALUES ('9','addAdmin','添加管理员','1','','9','14','3');
INSERT INTO `node` VALUES ('10','index','系统设置首页','1','','10','4','3');
INSERT INTO `node` VALUES ('11','setEmailConfig','设置系统邮件','1','','12','4','3');
INSERT INTO `node` VALUES ('12','testEmailConfig','发送测试邮件','1','','0','4','3');
INSERT INTO `node` VALUES ('13','setSafeConfig','系统安全设置','1','','0','4','3');
INSERT INTO `node` VALUES ('14','Access','权限管理','1','权限管理，为系统后台管理员设置不同的权限','0','1','2');
INSERT INTO `node` VALUES ('15','nodeList','查看节点','1','节点列表信息','0','14','3');
INSERT INTO `node` VALUES ('16','roleList','角色列表查看','1','角色列表查看','0','14','3');
INSERT INTO `node` VALUES ('17','addRole','添加角色','1','','0','14','3');
INSERT INTO `node` VALUES ('18','editRole','编辑角色','1','','0','14','3');
INSERT INTO `node` VALUES ('19','opNodeStatus','便捷开启禁用节点','1','','0','14','3');
INSERT INTO `node` VALUES ('20','opRoleStatus','便捷开启禁用角色','1','','0','14','3');
INSERT INTO `node` VALUES ('21','editNode','编辑节点','1','','0','14','3');
INSERT INTO `node` VALUES ('22','addNode','添加节点','1','','0','14','3');
INSERT INTO `node` VALUES ('23','addAdmin','添加管理员','1','','0','14','3');
INSERT INTO `node` VALUES ('24','editAdmin','编辑管理员信息','1','','0','14','3');
INSERT INTO `node` VALUES ('25','changeRole','权限分配','1','','0','14','3');
INSERT INTO `node` VALUES ('26','Posts','资讯管理','1','','0','1','2');
INSERT INTO `node` VALUES ('27','index','新闻列表','1','','0','26','3');
INSERT INTO `node` VALUES ('28','category','文章分类管理','1','','0','26','3');
INSERT INTO `node` VALUES ('29','add','发布文章','1','','0','26','3');
INSERT INTO `node` VALUES ('30','edit','编辑文章','1','','0','26','3');
INSERT INTO `node` VALUES ('31','del','删除信息','1','','0','26','3');
INSERT INTO `node` VALUES ('32','SysData','数据库管理','1','包含数据库备份、还原、打包等','0','1','2');
INSERT INTO `node` VALUES ('33','index','查看数据库表结构信息','1','','0','32','3');
INSERT INTO `node` VALUES ('34','backup','备份数据库','1','','0','32','3');
INSERT INTO `node` VALUES ('35','restore','查看已备份SQL文件','1','','0','32','3');
INSERT INTO `node` VALUES ('36','restoreData','执行数据库还原操作','1','','0','32','3');
INSERT INTO `node` VALUES ('37','delSqlFiles','删除SQL文件','1','','0','32','3');
INSERT INTO `node` VALUES ('38','sendSql','邮件发送SQL文件','1','','0','32','3');
INSERT INTO `node` VALUES ('39','zipSql','打包SQL文件','1','','0','32','3');
INSERT INTO `node` VALUES ('40','zipList','查看已打包SQL文件','1','','0','32','3');
INSERT INTO `node` VALUES ('41','unzipSqlfile','解压缩ZIP文件','1','','0','32','3');
INSERT INTO `node` VALUES ('42','delZipFiles','删除zip压缩文件','1','','0','32','3');
INSERT INTO `node` VALUES ('43','downFile','下载备份的SQL,ZIP文件','1','','0','32','3');
INSERT INTO `node` VALUES ('44','repair','数据库优化修复','1','','0','32','3');
INSERT INTO `node` VALUES ('45','tag','文章标签管理','1','','0','26','3');


# 数据库表：options 数据信息
INSERT INTO `options` VALUES ('418','site_url','http://green.njut.asia','yes');
INSERT INTO `options` VALUES ('419','title','GreenCMS2014  Powered By 绿荫工作室 ','yes');
INSERT INTO `options` VALUES ('420','keywords','GreenCMS v2','yes');
INSERT INTO `options` VALUES ('421','description','GreenCMS 2014','yes');
INSERT INTO `options` VALUES ('422','foot','By GreenStudio','yes');
INSERT INTO `options` VALUES ('444','think_token','1','yes');
INSERT INTO `options` VALUES ('443','ip_geo','1','yes');
INSERT INTO `options` VALUES ('442','software_author','GreenStudio','yes');
INSERT INTO `options` VALUES ('431','widget_about_us','widget_about_us','yes');
INSERT INTO `options` VALUES ('441','software_homepage','http://www.greencms.net','yes');
INSERT INTO `options` VALUES ('440','software_version','2.1.0126','yes');
INSERT INTO `options` VALUES ('439','software_name','GreenCMS v2','yes');
INSERT INTO `options` VALUES ('446','LOG_RECORD','1','yes');
INSERT INTO `options` VALUES ('447','software_build','2014.01.27','yes');
INSERT INTO `options` VALUES ('448','users_can_register','1','yes');
INSERT INTO `options` VALUES ('449','HTML_CACHE_ON','1','yes');
INSERT INTO `options` VALUES ('450','sqlFileSize','100001','yes');
INSERT INTO `options` VALUES ('451','send_mail','1','yes');
INSERT INTO `options` VALUES ('452','smtp_host','mail.njut.edu.cn','yes');
INSERT INTO `options` VALUES ('453','smtp_port','25','yes');
INSERT INTO `options` VALUES ('454','smtp_user','zts1993@njut.edu.cn','yes');
INSERT INTO `options` VALUES ('455','from_email','zts1993@njut.edu.cn','yes');
INSERT INTO `options` VALUES ('456','smtp_pass','262019','yes');
INSERT INTO `options` VALUES ('457','smtp_test','','yes');
INSERT INTO `options` VALUES ('458','pager','5','yes');
INSERT INTO `options` VALUES ('459','ome_info','original','yes');
INSERT INTO `options` VALUES ('461','db_fieldtype_check','0','yes');
INSERT INTO `options` VALUES ('462','DEFAULT_FILTER','htmlspecialchars','yes');
INSERT INTO `options` VALUES ('463','COOKIE_PREFIX','greencms_','yes');
INSERT INTO `options` VALUES ('464','COOKIE_EXPIRE','3600','yes');
INSERT INTO `options` VALUES ('465','COOKIE_DOMAIN','','yes');
INSERT INTO `options` VALUES ('466','COOKIE_PATH','/','yes');
INSERT INTO `options` VALUES ('467','DB_FIELDS_CACHE','1','yes');
INSERT INTO `options` VALUES ('468','DB_SQL_BUILD_CACHE','1','yes');


# 数据库表：plugin 数据信息
INSERT INTO `plugin` VALUES ('1','0','duoshuo','多说评论插件','xjh1994','GreenCMS','0');
INSERT INTO `plugin` VALUES ('2','0','guestbook','留言本插件','xjh1994','GreenCMS','0');
INSERT INTO `plugin` VALUES ('3','0','rss','站点RSS订阅插件','xjh1994','GreenCMS','0');


# 数据库表：post_cat 数据信息
INSERT INTO `post_cat` VALUES ('20','18','97');
INSERT INTO `post_cat` VALUES ('23','20','99');
INSERT INTO `post_cat` VALUES ('39','15','97');
INSERT INTO `post_cat` VALUES ('34','15','101');
INSERT INTO `post_cat` VALUES ('31','15','103');
INSERT INTO `post_cat` VALUES ('32','16','104');
INSERT INTO `post_cat` VALUES ('102','15','105');
INSERT INTO `post_cat` VALUES ('30','20','106');
INSERT INTO `post_cat` VALUES ('103','15','116');
INSERT INTO `post_cat` VALUES ('104','16','116');
INSERT INTO `post_cat` VALUES ('105','17','116');
INSERT INTO `post_cat` VALUES ('106','18','116');
INSERT INTO `post_cat` VALUES ('107','19','116');
INSERT INTO `post_cat` VALUES ('108','20','116');
INSERT INTO `post_cat` VALUES ('109','17','117');
INSERT INTO `post_cat` VALUES ('110','18','118');
INSERT INTO `post_cat` VALUES ('111','15','120');
INSERT INTO `post_cat` VALUES ('112','17','122');


# 数据库表：post_meta 数据信息


# 数据库表：post_tag 数据信息
INSERT INTO `post_tag` VALUES ('1653','8','105');
INSERT INTO `post_tag` VALUES ('1654','8','116');
INSERT INTO `post_tag` VALUES ('1655','8','117');
INSERT INTO `post_tag` VALUES ('1656','8','118');
INSERT INTO `post_tag` VALUES ('1657','8','120');
INSERT INTO `post_tag` VALUES ('1658','8','122');


# 数据库表：posts 数据信息
INSERT INTO `posts` VALUES ('94','1','2013-10-11 20:10:27','<p><iframe height="420" frameborder="0" width="820" scrolling="no" marginheight="0" marginwidth="0" src="http://live.64ma.com/tv/live.html"></iframe></p>','网络电视','publish','open','','网络电视','detail','2013-10-11 20:10:27','0','8','page','','0');
INSERT INTO `posts` VALUES ('95','1','2013-10-11 20:10:40','<h4 style="margin: 0px; padding: 50px 0px 0px; border: 0px; font-family: &#39;Microsoft YaHei&#39;, sans-serif; font-size: 15px; vertical-align: baseline; color: rgb(29, 29, 29); white-space: normal; background-color: rgb(233, 240, 244);">联系方式</h4><hr class="smalline" style="border: none; height: 2px; background-color: rgb(193, 207, 217); margin: 15px 0px 10px; clear: both; width: 32px; color: rgb(119, 119, 119); font-family: &#39;Microsoft YaHei&#39;, sans-serif; font-size: 14px; white-space: normal;"/><ul class="getintouch list-paddingleft-2" style="list-style-type: none;"><li><p><span class="icon-map-marker" style="margin: 0px 4px 0px 0px; padding: 0px; border: 0px; font-family: FontAwesome; font-size: inherit; font-variant: inherit; vertical-align: baseline; text-decoration: inherit; -webkit-font-smoothing: antialiased; display: inline-block; width: 1.25em; height: auto; background-image: none; text-align: center; background-position: 0% 0%; background-repeat: repeat repeat;"></span><span style="margin: 0px; padding: 0px; border: 0px; font-size: inherit; font-style: inherit; font-variant: inherit; font-weight: 700; line-height: inherit; vertical-align: baseline;">地址:&nbsp;</span>信息学院学办620</p></li><li><p><span class="icon-phone" style="margin: 0px 4px 0px 0px; padding: 0px; border: 0px; font-family: FontAwesome; font-size: inherit; font-variant: inherit; vertical-align: baseline; text-decoration: inherit; -webkit-font-smoothing: antialiased; display: inline-block; width: 1.25em; height: auto; background-image: none; text-align: center; background-position: 0% 0%; background-repeat: repeat repeat;"></span><span style="margin: 0px; padding: 0px; border: 0px; font-size: inherit; font-style: inherit; font-variant: inherit; font-weight: 700; line-height: inherit; vertical-align: baseline;">QQ群:&nbsp;</span>249301028</p></li><li><p><span class="icon-envelope" style="margin: 0px 4px 0px 0px; padding: 0px; border: 0px; font-family: FontAwesome; font-size: inherit; font-variant: inherit; vertical-align: baseline; text-decoration: inherit; -webkit-font-smoothing: antialiased; display: inline-block; width: 1.25em; height: auto; background-image: none; text-align: center; background-position: 0% 0%; background-repeat: repeat repeat;"></span><span style="margin: 0px; padding: 0px; border: 0px; font-size: inherit; font-style: inherit; font-variant: inherit; font-weight: 700; line-height: inherit; vertical-align: baseline;">电子邮件:&nbsp;</span>admin@njut.asia</p></li></ul><p><br/></p>','联系我们','publish','open','','联系我们','detail','2013-11-20 22:11:22','0','10','page','','0');
INSERT INTO `posts` VALUES ('97','1','2013-10-11 21:10:20','<p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">大家都知道ipv6现在是免流量的。因此五花八门的免流量上网方式都出现了。最好的当然后goagent，毕竟免费嘛。但是goagent使用的是google app engine 每个月每个应用只有1G流量，10个应用只有10G流量这个流量对于很多人来说还是不够用。同时使用goagent代理并不是全局的而且做不到全局(不是技术原因，因为goagent并不是一个真正的http代理程序)，同时goagent代理还有一个致命的缺点不能登录qq(这个技术上的问题就不说了)，goagent有个最大的bug就是出口ip并不是不变的，所以常常会出现某些网站不能登录或者登录之后不停的掉线这样的问题。</p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);"><span style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none; color: rgb(0, 0, 128);">因此使用openvpn建立ipv6是我们最好的选择</span></h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">为什么不选pptp —-因为不支持ipv6。</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">为什么不选l2tp—-openvz的vps不支持加密。毕竟我们选便宜的vps搭建~~~</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">其他~~~代理不能全局，其他vpn方法需要大微软技术的支持，作为廉洁解决方案基本不可能完成。</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">关于openvpn对于ipv6的支持情况，在openvpn2.3版本之前使用ipv6需要打补丁但是2.3版本开始就不需要了。记住windows版和linux server都要选择2.3。ipv6与ipv4配置上的区别就一点 udp改成udp6就ok了。是不是很简单。。。。。。。</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">另外关于openvpn注意openvpn connector是商业软件，免费两个许可证连接而且不支持ipv6。我们使用的是社区版，下载客户端时候注意。</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">说了好多废话现在开始安装openvpn</p><h1 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 36px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);"><strong style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;">安装 OpenVPN</strong></h1><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">环境准备好之后，我们正式开始安装 OpenVPN 了。这里使用的是 yum 来进行安装。</p><pre style="padding: 3px; margin-top: 0px; margin-bottom: 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); background-color: rgb(255, 255, 255); font-size: 11px; line-height: 19px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">CentOS5用户执行</pre><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">wget http://dl.fedoraproject.org/pub/epel/5/i386/epel-release-5-4.noarch.rpm</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">rpm -Uvh epel-release-5-4.noarch.rpm</code></p><pre style="padding: 3px; margin-top: 0px; margin-bottom: 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); background-color: rgb(255, 255, 255); font-size: 11px; line-height: 19px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">CentOS6用户执行&nbsp;wget&nbsp;wget&nbsp;http://dl.fedoraproject.org/pub/epel/6/i386/epel-release-6-8.noarch.rpm</pre><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">rpm -Uvh epel-release-6-8.noarch.rpm</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">如果你看到这篇文章时你都已经用上centos7了那你就去http://dl.fedoraproject.org/pub/epel里面找吧</p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">成功后 yum 源里面就有 OpenVPN 了，直接使用命令安装</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">yum install openvpn</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">说明：这里就体现了 yum 安装的好处，比如 OpenVPN 需要 lzo 支持，安装的时候会检测系统，没有的组件会自动安装进去。</p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">安装easy-rsa–由于最新的OpenVPN不自带easy-rsa，需要手工安装。</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">wget https://github.com/OpenVPN/easy-rsa/archive/master.zip<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">unzip master.zip<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code></p><h1 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 36px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">配置 OpenVPN</h1><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">easy-rsa这个文件夹移至 /etc/openvpn/<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">cp -R easy-rsa-master/easy-rsa /etc/openvpn/ #位置根据自己的情况看啊</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">进入然后/etc/openvpn/easy-rsa/2.0目录，用vi vars来编辑环境变量。<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">cd /etc/openvpn/easy-rsa/2.0</code><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">vi vars</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">根据实际情况修改</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);"><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>export KEY_COUNTRY=&quot;CN&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>export KEY_PROVINCE=&quot;JiangSu&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>export KEY_CITY=&quot;NanJing&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>export KEY_ORG=&quot;GreenStudio&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>export KEY_EMAIL=&quot;admin@njut.asia&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">更改文件夹内文件属性防止Access Denied<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">cd ..;chmod -R 777 2.0;</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">保存后把. vars设置生效。<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">source ./vars</code><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">./clean-all</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">生成证书</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">创建证书颁发机构（系统出现提示操作时，直接全部回车）</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">./build-ca server</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">创建CA之后来生成服务器证书（系统出现提示操作时，前面直接全部回车 最后2个按y）</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">./build-key-server server</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">服务器证书生成完了，我们来生成客户端证书，理论上每个OpenVPN用户都有独立的证书。（系统出现提示操作时，前面直接全部回车 最后2个按y）</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">./build-key greenstudio</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">说明：这里的greenstudio是客户端名称，如果你再生成第二个随便换成别的名字就可以如：./build-key zts1993）</p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">最后生成Diffie Hellman参数，这个需要一小点时间</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">./build-dh</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">设置OpenVPN参数新建 server.conf</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">vi /etc/openvpn/server.conf</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">放入如下内容：<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);"><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>port 8080 #这个端口一般不敢封<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>proto udp6 #这个是ipv6的设置如果是ipv4是udp就可以了，可以用tcp这里我用udp了<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>dev tun<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>ca /etc/openvpn/easy-rsa/2.0/keys/ca.crt #这几行按需修改<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>cert /etc/openvpn/easy-rsa/2.0/keys/server.crt<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>key /etc/openvpn/easy-rsa/2.0/keys/server.key<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>dh /etc/openvpn/easy-rsa/2.0/keys/dh2048.pem<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>server 10.8.0.0 255.255.255.0<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>push &quot;redirect-gateway def1&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>push &quot;dhcp-option DNS 8.8.8.8&quot; #设个DNS吧<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>push &quot;dhcp-option DNS 8.8.4.4&quot;<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>client-to-client #允许客户互相访问<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>keepalive 10 120<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>comp-lzo<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>persist-key<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>persist-tun<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>cipher AES-128-CBC<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>verb 3<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">启用 ipv4 转发(理论上ipv6用户可以忽视这段)<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>1.编辑/etc/sysctl.conf<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">vi /etc/sysctl.conf<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>2.找到net.ipv4.ip_forward = 0改成net.ipv4.ip_forward = 1保存<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>3.让更改生效<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">sysctl -p<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code>说明：如果此时出现错误 error: “Operation not permitted” setting key “net.ipv4.tcp_syncookies” ，就编辑 /etc/sysctl.conf，注释掉 net.ipv4.tcp_syncookies 那一行</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">配置 iptables<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>如果你跟偶一样很多类型VPN，很多NAT的话直接<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>xen和kvm用户<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">iptables -t nat -A POSTROUTING -s 10.8.0.0/24 -o eth0 -j MASQUERADE<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>openvz用户<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">iptables -t nat -A POSTROUTING -s 10.8.0.0/24 -o venet0 -j MASQUERADE</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">启动 OpenVPN</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">service openvpn start<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">检查 OpenVPN</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">netstat -anup | grep 8080 # 8080是端口<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>如出现如下字样表示成功，OpenVPN 已经打开<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">udp 0 0 0.0.0.0:8080 0.0.0.0:*<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>8179/openvpn</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">设置 OpenVPN 开机启动</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);"><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>chkconfig openvpn on</code></p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">OpenVPN 客户端</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">下载客户端：&nbsp;被墙了，各位各显神通吧。<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><a href="http://openvpn.net/index.php/open-source/downloads.html" style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none; color: rgb(68, 68, 68); text-decoration: none; transition: all 0.2s ease-in-out; -webkit-transition: all 0.2s ease-in-out;">http://openvpn.net/index.php/open-source/downloads.html</a></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">&nbsp;</p><h2 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 30px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);">使用 SFTP 软件把客户端所需文件下载到本地</h2><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">#用户名就是你刚才生成证书时候的名字啊~~~~<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>服务器路径为：/etc/openvpn/easy-rsa/2.0/keys　中5个文件的：<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);">用户名.crt<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>用户名.key<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>ca.crt</code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">将上述3个文件下载到 C:Program FilesOpenVPNconfig（总之是你安装目录了）</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">上面那个文件夹下，新建 #用户名#.ovpn，输入以下内容</p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);"><br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>client<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>dev tun<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>proto udp6<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>remote 服务器IPv6地址 8080<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>resolv-retry infinite<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>nobind<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>persist-key<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>persist-tun<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>ca ca.crt<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>cert #用户名#.crt<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>key #用户名#.key<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>ns-cert-type server<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>comp-lzo<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>cipher AES-128-CBC<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>verb 3<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/></code></p><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">恭喜你大功告成<br style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;"/>在开始菜单里面找到OpenVPN GUI并运行，Vista和Win7下需要管理员身份运行。点Connect后等一下，出现本地连接2，搞定!</p><h1 style="padding: 0px; margin: 0px 0px 15px; outline: none; list-style: none; border: 0px none; font-weight: normal; line-height: 1em; font-family: Helvetica; font-size: 36px; color: rgb(51, 51, 51); white-space: normal; background-color: rgb(255, 255, 255);"><span style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none; color: rgb(255, 0, 0);"><code style="padding: 3px; margin: 0px 0px 20px; outline: none; list-style: none; border-width: 1px; border-style: solid; border-color: rgb(225, 225, 225) rgb(240, 240, 240) rgb(240, 240, 240) rgb(225, 225, 225); direction: ltr; background-image: url(https://blog.zts1993.com/wp-content/themes/z8/images/code-bg.png); font-size: 11px; line-height: 19px; font-family: &#39;andale mono&#39;, &#39;lucida console&#39;, monospace; display: block; overflow-x: visible; overflow-y: hidden; color: rgb(102, 102, 102);"><strong style="padding: 0px; margin: 0px; outline: none; list-style: none; border: 0px none;">千万注意，需要Administrator权限！</strong></code></span></h1><p style="padding: 0.5em 0px; margin-top: 0px; margin-bottom: 0px; outline: none; list-style: none; border: 0px none; color: rgb(51, 51, 51); font-family: Tahoma, Arial, Verdana, sans-serif; font-size: 13px; line-height: 19px; white-space: normal; background-color: rgb(255, 255, 255);">在服务器上你可以用 service openvpn start | stop | restart 来控制</p><p><br/></p>','校园网免流量之基于IPV6的OPENVPN搭建','publish','open','','校园网免流量之基于IPV6的OPENVPN搭建','detail','2013-10-11 21:10:20','0','1','single','','0');
INSERT INTO `posts` VALUES ('99','1','2013-10-16 10:10:47','<p style="margin-top: 0px; margin-bottom: 0.8em; padding: 0px; line-height: 1.4em;">本周六&nbsp;(10/19)&nbsp;举办，这次会请到对所有开发者都有重要帮助的神器&nbsp;GoAgent&nbsp;的作者&nbsp;Phus&nbsp;Lu&nbsp;来给我们做一场分享，热烈欢迎大家报名参加。&nbsp;<br/><br/>大家不要错过机会，近距离接触这位了不起的开发者，关于科学上网，黑客人生，技术实现等，大家有什么问题都赶快准备好吧&nbsp;:)&nbsp;<br/><br/><br/>时间:&nbsp;&nbsp;10月19日&nbsp;(周六)&nbsp;下午&nbsp;2:00~17:30&nbsp;<br/><br/>地点:&nbsp;&nbsp;南京半坡村咖啡馆&nbsp;青岛路32号（近南京大学鼓楼校区）&nbsp;025－83324627&nbsp;<br/><br/>日程安排:&nbsp;<br/><br/>a.&nbsp;Introduce&nbsp;to&nbsp;GoAgent&nbsp;<br/><br/>分享嘉宾:&nbsp;<br/>Phus&nbsp;Lu&nbsp;<br/><br/>G+&nbsp;活动链接:&nbsp;<br/>https://plus.google.com/events/cn59ga02nulai7sje8vap98pml8</p><p style="margin-top: 0px; margin-bottom: 0.8em; padding: 0px; line-height: 1.4em;">活动报名地址：</p><p><a href="https://docs.google.com/forms/d/19h6ivEYSYMB_NC7YoFRNYJRcUjTgH19jb7iam1Y_L-s/viewform" _src="https://docs.google.com/forms/d/19h6ivEYSYMB_NC7YoFRNYJRcUjTgH19jb7iam1Y_L-s/viewform">https://docs.google.com/forms/d/19h6ivEYSYMB_NC7YoFRNYJRcUjTgH19jb7iam1Y_L-s/viewform</a></p><p><br/></p><p><br/></p><p><br/></p><p><br/></p>','与GoAgent作者一起喝咖啡','publish','open','','与GoAgent作者一起喝咖啡','detail','2013-10-16 10:10:47','0','0','single','','0');
INSERT INTO `posts` VALUES ('100','1','2013-10-16 11:10:21','<p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">今 &nbsp;早英特尔公司现任首席执行官Brian Krzanich主持召开了第三季度财务电话会议，会上除了透露14nm制程移动端Broadwell处理器由于量产问题已经推迟一季度上市之外<span style="font-weight: 700;">还谈到在未来几周将会有超过10款装备Bay Trail芯片的设备问世。</span><br/></p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;"><img src="/upload/74361381895409.png" alt="" original="http://static.cnbetacdn.com/newsimg/2013/1016/01381892037.png" style="border: 0px;"/><br/></p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">根据消息称这些设备大部分都是<a data-type="2" data-keyword="平板" data-rd="1" data-style="1" data-tmpl="290x380" target="_blank" style="color: rgb(0, 51, 102); cursor: pointer;">平板</a>或者跨界设备，产品最迟在11月低之前的几周内发布，使之能够在即将到来的假日购物季中赢得不错的销量。</p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">Krzanich称：“我们已经看到了超过50多款设备，其中有超过一半都是跨界设备，在明年我们将会继续加大跨界设备的投入。”明年我们将会看到一大波装备32位Windows 8.1系统内置Bay Trail芯片的跨界平板，而64位版本平板将在2014年第一季度上架发售。</p><p><br/></p>','[图]一大波装备Bay Trail芯片的跨界平板即将问世','publish','open','','[图]一大波装备Bay Trail芯片的跨界平板即将问世','detail','2013-11-21 22:11:11','0','0','single','','0');
INSERT INTO `posts` VALUES ('101','1','2013-10-16 11:10:17','<p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">在一开始的时候，谷歌光纤就不允许其高速互联网服务的用户在家中使用服务器。其辩称这是&quot;行业惯例&quot;，但是遭到了网络中立倡导者和电子前沿基金会(EFF)的强烈批评。现在，Google已经决定放宽这一限制。<span style="font-weight: 700;">在最近更新的&quot;可接受使用政策&quot;(Acceptable Use Policy)中，该公司已经允许个人用户使用服务器，但是任何商业用途仍然是不被允许的。</span><br/></p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px; text-align: center;"><img src="/upload/19601381895471.jpg" title="google-fiber-12-verge-1020_large_verge_medium_landscape.jpg" original="http://static.cnbetacdn.com/newsimg/2013/1016/95_1381890980.jpg_w600.jpg" style="border: 0px;"/></p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">本月早些时候，一名愤怒的消费者曾在Google于犹他州Provo举办的&quot;Fiber sign-up&quot;活动会场外进行过抗议。相信本次政策变动应该能够减少他的愤怒。</p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">Google Fiber新政策所允许的场景包括：在家里使用虚拟专用网络、或者包括<a data-type="2" data-keyword="服务器" data-rd="1" data-style="1" data-tmpl="290x380" target="_blank" style="color: rgb(0, 51, 102); cursor: pointer;">服务器</a>功能的硬件和应用(如多人游戏、视频会议和家庭安全)。</p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">当然，这仅限于消费者。如果一个小型企业希望用Google的千兆网络作为自己的商用服务器，目前仍然是不被允许的。不过未来应该会有更多面向小型企业的产品信息。</p><p><br/></p>','谷歌光纤政策松绑 允许个人服务器 小企业仍无缘','publish','open','','谷歌光纤政策松绑 允许个人服务器 小企业仍无缘','detail','2013-11-05 13:11:44','0','3','single','','0');
INSERT INTO `posts` VALUES ('103','1','2013-10-16 11:10:53','<blockquote><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">不得不说，Chrome的成长步伐还是很快了，别的浏览器还都在十几岁，它这个后来者都已经到而立之年了。<span style="font-weight: 700;">今天，Google正式发布了Chrome 30.0.1599.101，Windows、Mac、Linux和Chrome Frame浏览器框架用户均可下载更新。</span></p></blockquote><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">虽然听版本号似乎是一次重大发布，但实际上，Chrome 30也只能看做是维护版本。<span style="font-weight: 700;">它修复了5个安全漏洞</span>，这些漏洞由安全研究人员和个人报告，Google为此<a data-type="2" data-keyword="支付" data-rd="1" data-style="1" data-tmpl="290x380" target="_blank" style="color: rgb(0, 51, 102); cursor: pointer;">支付</a>了奖金。此外，Chrome 30还修复了一些之前版本存在的Bug，完善了浏览器性能。</p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px;">建议所有用户都更新到最新版，Chrome用户可以通过浏览器内置的静默更新器获取新版，也可以直接到官网下载：<a href="https://www.google.com/intl/zh-CN/chrome/browser/" style="color: rgb(0, 51, 102); text-decoration: none; cursor: pointer;">https://www.google.com/intl/zh-CN/chrome/browser/</a></p><p style="margin: 10px auto; padding: 0px; vertical-align: baseline; word-wrap: break-word; line-height: 25px; text-align: center;"><img alt="Chrome 30正式版发布" src="/greencms/upload/4071381895539.jpg" original="http://static.cnbetacdn.com/newsimg/2013/1016/01381886270.jpg" style="border: 0px;"/></p><p><br/></p><p><br/></p><blockquote><p>chrome版本号什么时候会int溢出</p></blockquote>','Chrome 30正式版发布','publish','open','','Chrome 30正式版发布','detail','2013-11-05 13:11:23','0','11','single','','0');
INSERT INTO `posts` VALUES ('104','1','2013-10-16 11:10:51','<p style="text-align: left;"><span style="color: rgb(51, 51, 51); font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif;  background-color: rgb(255, 255, 255);">绿荫工作室2013&nbsp;</span><a href="http://rrurl.cn/vkFfoy" target="_blank" title="http://green.njut.edu.cn/" style="color: rgb(51, 102, 153); text-decoration: none; font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);">http://rrurl.cn/vkFfoy&nbsp;</a><span style="color: rgb(51, 51, 51); font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif;  background-color: rgb(255, 255, 255);">招新宣讲与 10月16日晚7点在仁智513 举行~~~本次招新面向全校大一大二所有专业，可选方向包括UI设计 ，移动应用开发和 Web开发三个方向。如果你对软件开发，美工，移动应用开发等感兴趣，千万不要错过，，报名地址直戳右边--》</span><a href="http://rrurl.cn/pNlkg6" target="_blank" title="http://green.njut.asia/form/apply" style="color: rgb(51, 102, 153); text-decoration: none; font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif; white-space: normal; background-color: rgb(255, 255, 255);">http://rrurl.cn/pNlkg6&nbsp;</a><span style="color: rgb(51, 51, 51); font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif;  background-color: rgb(255, 255, 255);">&nbsp;</span></p><p><span style="color: rgb(51, 51, 51); font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif;  background-color: rgb(255, 255, 255);"><br style="text-align: left;"/></span></p><p style="text-align:center"><img src="/upload/20131016/13818957911222.jpg" title="海报红蓝.jpg"/></p><p style="text-align:center"><img src="/upload/20131016/13818957924079.jpg" title="海报蓝绿.jpg"/></p><p><span style="color: rgb(51, 51, 51); font-family: Tahoma, Verdana, STHeiTi, simsun, sans-serif;  background-color: rgb(255, 255, 255);"><br style="text-align: left;"/></span></p>','绿荫工作室2013招新啦','publish','open','','绿荫工作室2013招新啦','detail','2013-11-05 13:11:33','0','1','single','','0');
