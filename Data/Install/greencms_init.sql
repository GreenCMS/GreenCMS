-- ----------------------------
-- Records of options
-- ----------------------------
INSERT INTO `{$db_prefix}options` VALUES (null, 'site_url', 'http://green.njut.asia', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'title', 'GreenCMS2014  Powered By 绿荫工作室 ', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'keywords', 'GreenCMS v2', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'description', 'GreenCMS 2014', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'foot', 'By GreenStudio', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'ip_geo', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'software_author', 'GreenStudio', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'widget_about_us', 'widget_about_us', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'software_homepage', 'http://www.greencms.net', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'software_version', '2.1.0207', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'software_name', 'GreenCMS v2', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'LOG_RECORD', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'software_build', '2014.02.07', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'HTML_CACHE_ON', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'sqlFileSize', '100000', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'send_mail', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'smtp_host', 'mail.njut.edu.cn', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'smtp_port', '25', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'smtp_user', 'zts1993@njut.edu.cn', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'from_email', 'zts1993@njut.edu.cn', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'smtp_pass', '', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'pager', '5', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'ome_info', 'original', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'db_fieldtype_check', '0', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'DEFAULT_FILTER', 'htmlspecialchars', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'COOKIE_PREFIX', 'greencms_', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'COOKIE_EXPIRE', '3600', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'COOKIE_DOMAIN', '', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'COOKIE_PATH', '/', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'DB_FIELDS_CACHE', '1', 'yes');
INSERT INTO `{$db_prefix}options` VALUES (null, 'DB_SQL_BUILD_CACHE', '1', 'yes');



-- ----------------------------
-- Records of role
-- ----------------------------
INSERT INTO `{$db_prefix}role` VALUES ('1', '超级管理员', '1', '1', '系统内置超级管理员组');
INSERT INTO `{$db_prefix}role` VALUES ('2', '网站管理员', '2', '1', '拥有系统仅此于超级管理员的权限');
INSERT INTO `{$db_prefix}role` VALUES ('3', '内容管理员', '3', '1', '拥有发布文章权利');
INSERT INTO `{$db_prefix}role` VALUES ('4', '投稿员', '4', '1', '只能投稿默认为未审核');
INSERT INTO `{$db_prefix}role` VALUES ('5', '游客', '5', '1', '基本信息修改');



-- ----------------------------
-- Records of role_users
-- ----------------------------
INSERT INTO `{$db_prefix}role_users` VALUES ('1', '1');
