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

SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Records of {$db_prefix}role
-- ----------------------------
INSERT INTO `{$db_prefix}role` VALUES ('1', '超级管理员', '1', '1', '系统内置超级管理员组');
INSERT INTO `{$db_prefix}role` VALUES ('2', '网站管理员', '2', '1', '拥有系统仅此于超级管理员的权限');
INSERT INTO `{$db_prefix}role` VALUES ('3', '内容管理员', '3', '1', '拥有发布文章权利');
INSERT INTO `{$db_prefix}role` VALUES ('4', '投稿员', '4', '1', '只能投稿默认为未审核');
INSERT INTO `{$db_prefix}role` VALUES ('5', '游客', '5', '1', '基本信息修改');

-- ----------------------------
-- Records of {$db_prefix}role_users
-- ----------------------------
INSERT INTO `{$db_prefix}role_users` VALUES ('1', '1');

-- ----------------------------
-- Records of {$db_prefix}hooks
-- ----------------------------
INSERT INTO `{$db_prefix}hooks` VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '');
INSERT INTO `{$db_prefix}hooks` VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop');
INSERT INTO `{$db_prefix}hooks` VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', '');
INSERT INTO `{$db_prefix}hooks` VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'SocialComment,baidushare');
INSERT INTO `{$db_prefix}hooks` VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '');
INSERT INTO `{$db_prefix}hooks` VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', '');
INSERT INTO `{$db_prefix}hooks` VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor');
INSERT INTO `{$db_prefix}hooks` VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin');
INSERT INTO `{$db_prefix}hooks` VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1382596073', '');
INSERT INTO `{$db_prefix}hooks` VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', 'Editor');
INSERT INTO `{$db_prefix}hooks` VALUES ('16', 'app_begin', '应用开始', '2', '1384481614', '');


-- ----------------------------
-- Records of {$db_prefix}links
-- ----------------------------
INSERT INTO `{$db_prefix}links` VALUES (null, '123', 'http://www.greencms.net/', 'GreenCMS', 'Home', 'GreenCMS首页', '1', '');
INSERT INTO `{$db_prefix}links` VALUES (null, '124', 'http://www.zts1993.com/', 'Z的博客', 'Home', 'Z的博客', '1', '');

-- ----------------------------
-- Records of {$db_prefix}menu
-- ----------------------------
INSERT INTO `{$db_prefix}menu` VALUES (null, '0', '10000', '测试分类', '_self', 'getCatURLByID', 'head', '1');
INSERT INTO `{$db_prefix}menu` VALUES (null, '0', '99', '空链接', '_self', 'none', 'head', null);
INSERT INTO `{$db_prefix}menu` VALUES (null, '0', '99', '测试页面', '_self', 'getSingleURLByID', 'head', '[\"2\",\"page\"]');
INSERT INTO `{$db_prefix}menu` VALUES (null, '0', '99', '测试文章', '_self', 'getSingleURLByID', 'head', '[\"1\",\"single\"]');

-- ----------------------------
-- Records of {$db_prefix}options
-- ----------------------------
INSERT INTO `{$db_prefix}options` VALUES ('1', 'site_url', 'http://127.0.0.1/green2014', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('2', 'title', 'GreenCMS 2014', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('3', 'keywords', 'GreenCMS v2 based on ThinkPHP 3.2.1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('4', 'description', 'GreenCMS is support by GreenStudio From NJUT', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('5', 'foot', '', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('6', 'ip_geo', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('7', 'software_author', 'GreenStudio', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('8', 'widget_about_us', '关于我们', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('9', 'software_homepage', 'http://www.greencms.net', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('10', 'software_version', 'v2.1.0306', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('11', 'software_name', 'GreenCMS v2', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('12', 'LOG_RECORD', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('13', 'software_build', '20140306', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('14', 'HTML_CACHE_ON', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('15', 'sqlFileSize', '5000000', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('16', 'send_mail', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('17', 'smtp_host', 'mail.njut.edu.cn', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('18', 'smtp_port', '25', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('19', 'smtp_user', 'test@njut.edu.cn', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('20', 'from_email', 'test@njut.edu.cn', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('21', 'smtp_pass', ' ', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('22', 'PAGER', '20', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('23', 'ome_info', 'original', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('24', 'db_fieldtype_check', '0', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('25', 'DEFAULT_FILTER', 'htmlspecialchars', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('26', 'COOKIE_PREFIX', 'greencms_', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('27', 'COOKIE_EXPIRE', '3600', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('28', 'COOKIE_DOMAIN', '', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('29', 'COOKIE_PATH', '/', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('30', 'DB_FIELDS_CACHE', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('31', 'DB_SQL_BUILD_CACHE', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('32', 'sql_mail', '', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('33', 'SHOW_CHROME_TRACE', '0', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('34', 'users_can_register', 'on', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('35', 'feed_open', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('36', 'feed_num', '20', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('37', 'Weixin_reply_subscribe', '欢迎使用Z的博客微信服务平台！回复help获得使用帮助', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('38', 'Weixin_appid', ' ', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('39', 'Weixin_secret', ' ', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('40', 'Weixin_menu', ' ', 'yes');
INSERT INTO `{$db_prefix}options` VALUES ('41', 'weixin_token', ' ', 'yes');


-- ----------------------------
-- Records of {$db_prefix}kv
-- ----------------------------
INSERT INTO `{$db_prefix}kv` VALUES ('1', 'home_theme', 'Twentytwelve');
INSERT INTO `{$db_prefix}kv` VALUES ('2', 'home_url_model', '2');
INSERT INTO `{$db_prefix}kv` VALUES ('3', 'home_cat_model', 'native');
INSERT INTO `{$db_prefix}kv` VALUES ('4', 'home_tag_model', 'native');
INSERT INTO `{$db_prefix}kv` VALUES ('5', 'home_post_model', 'native');
INSERT INTO `{$db_prefix}kv` VALUES ('7', 'theme_Vena', 'enabled');
INSERT INTO `{$db_prefix}kv` VALUES ('8', 'theme_2011college', 'enabled');
INSERT INTO `{$db_prefix}kv` VALUES ('9', 'theme_Twentytwelve', 'enabled');
INSERT INTO `{$db_prefix}kv` VALUES ('10', 'HTML_CACHE_ON', 'false');

-- ----------------------------
-- Records of {$db_prefix}posts
-- ----------------------------
INSERT INTO `{$db_prefix}posts` VALUES (null, 1, CURRENT_TIMESTAMP, '你好,世界', '欢迎使用GreenCMS', 'publish', 'open', '', 'helloworld', 'detail', CURRENT_TIMESTAMP, 0, 0, 'single', NULL, 0);
INSERT INTO `{$db_prefix}posts` VALUES (null, 1, CURRENT_TIMESTAMP, '欢迎使用', '这是一个文章测试页面', 'publish', 'open', '', 'testpage', 'detail', CURRENT_TIMESTAMP, 0, 0, 'page', NULL, 0);


-- ----------------------------
-- Records of {$db_prefix}cats
-- ----------------------------
INSERT INTO `{$db_prefix}cats` VALUES ('1','0','test_cat1','测试分类','');
INSERT INTO `{$db_prefix}cats` VALUES ('2','1','test_cat2','测试子分类','');

-- ----------------------------
-- Records of {$db_prefix}tags
-- ----------------------------
INSERT INTO `{$db_prefix}tags` VALUES ('1','测试标签','test_tag','0');
INSERT INTO `{$db_prefix}tags` VALUES ('1','hello','hello','0');

-- ----------------------------
-- Records of {$db_prefix}post_cat
-- ----------------------------
INSERT INTO `{$db_prefix}post_cat` VALUES (null,'1','1');

-- ----------------------------
-- Records of {$db_prefix}post_tag
-- ----------------------------
INSERT INTO `{$db_prefix}post_tag` VALUES (null,'1','1');


-- ----------------------------
-- Records of {$db_prefix}plugin
-- ----------------------------
INSERT INTO `{$db_prefix}plugin` VALUES ('1', '1', 'Duoshuo', '多说第三方评论', 'XJH1994', 'GreenCMS',CURRENT_TIMESTAMP);


-- ----------------------------
-- Records of {$db_prefix}addons
-- ----------------------------
INSERT INTO `{$db_prefix}addons` VALUES ('9', 'SocialComment', '通用社交化评论', '集成了各种社交化评论插件，轻松集成到系统中。', '1', '{\"comment_type\":\"2\",\"comment_uid_youyan\":\"1894186\",\"comment_short_name_duoshuo\":\"greencmsduoshuo\",\"comment_form_pos_duoshuo\":\"buttom\",\"comment_data_list_duoshuo\":\"10\",\"comment_data_order_duoshuo\":\"desc\"}', 'xjh1994', '0.1', '1380273962', '0');