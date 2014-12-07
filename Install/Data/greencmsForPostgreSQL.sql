/*
Navicat PGSQL Data Transfer

Source Server         : Postgre-127.0.0.1
Source Server Version : 90305
Source Host           : 127.0.0.1:5432
Source Database       : green
Source Schema         : public

Target Server Type    : PGSQL
Target Server Version : 90305
File Encoding         : 65001

Date: 2014-11-29 15:25:26
*/


-- ----------------------------
-- Table structure for green_access
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_access";
CREATE TABLE "public"."green_access" (
"role_id" int4 NOT NULL,
"node_id" int4 NOT NULL,
"level" int2 NOT NULL,
"pid" int2,
"module" varchar(50) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_access" IS '权限分配表';

-- ----------------------------
-- Records of green_access
-- ----------------------------
INSERT INTO "public"."green_access" VALUES ('5', '1', '1', '0', null);
INSERT INTO "public"."green_access" VALUES ('5', '2', '2', '1', null);
INSERT INTO "public"."green_access" VALUES ('5', '12', '3', '2', null);
INSERT INTO "public"."green_access" VALUES ('5', '13', '3', '2', null);
INSERT INTO "public"."green_access" VALUES ('5', '16', '3', '2', null);
INSERT INTO "public"."green_access" VALUES ('5', '17', '3', '2', null);
INSERT INTO "public"."green_access" VALUES ('5', '18', '3', '2', null);

-- ----------------------------
-- Table structure for green_addons
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_addons";
CREATE TABLE "public"."green_addons" (
"id" int8 NOT NULL,
"name" varchar(40) COLLATE "default" NOT NULL,
"title" varchar(20) COLLATE "default" NOT NULL,
"description" text COLLATE "default",
"status" int2 NOT NULL,
"config" text COLLATE "default",
"author" varchar(40) COLLATE "default",
"version" varchar(20) COLLATE "default",
"create_time" int8 NOT NULL,
"has_adminlist" int2 NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_addons" IS '插件表';
COMMENT ON COLUMN "public"."green_addons"."id" IS '主键';
COMMENT ON COLUMN "public"."green_addons"."name" IS '插件名或标识';
COMMENT ON COLUMN "public"."green_addons"."title" IS '中文名';
COMMENT ON COLUMN "public"."green_addons"."description" IS '插件描述';
COMMENT ON COLUMN "public"."green_addons"."status" IS '状态';
COMMENT ON COLUMN "public"."green_addons"."config" IS '配置';
COMMENT ON COLUMN "public"."green_addons"."author" IS '作者';
COMMENT ON COLUMN "public"."green_addons"."version" IS '版本号';
COMMENT ON COLUMN "public"."green_addons"."create_time" IS '安装时间';
COMMENT ON COLUMN "public"."green_addons"."has_adminlist" IS '是否有后台列表';

-- ----------------------------
-- Records of green_addons
-- ----------------------------
INSERT INTO "public"."green_addons" VALUES ('1', 'GreenWeixin', '微信模块', '微信模块入口', '1', '{"random":"1"}', 'timothy_zhang', '0.1', '1417053055', '1');

-- ----------------------------
-- Table structure for green_cats
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_cats";
CREATE TABLE "public"."green_cats" (
"cat_id" numeric(20) NOT NULL,
"cat_father" int8 NOT NULL,
"cat_slug" varchar(200) COLLATE "default" NOT NULL,
"cat_name" varchar(200) COLLATE "default" NOT NULL,
"cat_order" int8
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_cats" IS '分类信息表';

-- ----------------------------
-- Records of green_cats
-- ----------------------------
INSERT INTO "public"."green_cats" VALUES ('1', '0', 'test_cat1', '测试分类', '0');
INSERT INTO "public"."green_cats" VALUES ('2', '1', 'test_cat2', '测试子分类', '0');

-- ----------------------------
-- Table structure for green_comments
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_comments";
CREATE TABLE "public"."green_comments" (
"comment_ID" numeric(20) NOT NULL,
"comment_post_ID" numeric(20) NOT NULL,
"comment_author" text COLLATE "default" NOT NULL,
"comment_author_email" varchar(100) COLLATE "default" NOT NULL,
"comment_author_url" varchar(200) COLLATE "default" NOT NULL,
"comment_author_IP" varchar(100) COLLATE "default" NOT NULL,
"comment_date" timestamp(6) NOT NULL,
"comment_content" text COLLATE "default" NOT NULL,
"comment_approved" varchar(20) COLLATE "default" NOT NULL,
"comment_parent" numeric(20) NOT NULL,
"user_id" numeric(20) NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_comments" IS '评论信息表';

-- ----------------------------
-- Records of green_comments
-- ----------------------------

-- ----------------------------
-- Table structure for green_form
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_form";
CREATE TABLE "public"."green_form" (
"fa_id" int8 NOT NULL,
"name" varchar(50) COLLATE "default" NOT NULL,
"class" varchar(50) COLLATE "default" NOT NULL,
"tel" varchar(50) COLLATE "default" NOT NULL,
"email" varchar(100) COLLATE "default" NOT NULL,
"direction" varchar(50) COLLATE "default" NOT NULL,
"message" text COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_form" IS '申请表';

-- ----------------------------
-- Records of green_form
-- ----------------------------

-- ----------------------------
-- Table structure for green_hooks
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_hooks";
CREATE TABLE "public"."green_hooks" (
"id" int8 NOT NULL,
"name" varchar(40) COLLATE "default" NOT NULL,
"description" text COLLATE "default" NOT NULL,
"type" int2 NOT NULL,
"update_time" int8 NOT NULL,
"addons" varchar(255) COLLATE "default" NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_hooks" IS 'Hooks表
';
COMMENT ON COLUMN "public"."green_hooks"."id" IS '主键';
COMMENT ON COLUMN "public"."green_hooks"."name" IS '钩子名称';
COMMENT ON COLUMN "public"."green_hooks"."description" IS '描述';
COMMENT ON COLUMN "public"."green_hooks"."type" IS '类型';
COMMENT ON COLUMN "public"."green_hooks"."update_time" IS '更新时间';
COMMENT ON COLUMN "public"."green_hooks"."addons" IS '钩子挂载的插件 ''，''分割';

-- ----------------------------
-- Records of green_hooks
-- ----------------------------
INSERT INTO "public"."green_hooks" VALUES ('1', 'pageHeader', '页面header钩子，一般用于加载插件CSS文件和代码', '1', '0', '');
INSERT INTO "public"."green_hooks" VALUES ('2', 'pageFooter', '页面footer钩子，一般用于加载插件JS文件和JS代码', '1', '0', 'ReturnTop');
INSERT INTO "public"."green_hooks" VALUES ('3', 'documentEditForm', '添加编辑表单的 扩展内容钩子', '1', '0', '');
INSERT INTO "public"."green_hooks" VALUES ('4', 'documentDetailAfter', '文档末尾显示', '1', '0', 'SocialComment,baidushare');
INSERT INTO "public"."green_hooks" VALUES ('5', 'documentDetailBefore', '页面内容前显示用钩子', '1', '0', '');
INSERT INTO "public"."green_hooks" VALUES ('6', 'documentSaveComplete', '保存文档数据后的扩展钩子', '2', '0', '');
INSERT INTO "public"."green_hooks" VALUES ('7', 'documentEditFormContent', '添加编辑表单的内容显示钩子', '1', '0', 'Editor');
INSERT INTO "public"."green_hooks" VALUES ('8', 'adminArticleEdit', '后台内容编辑页编辑器', '1', '1378982734', 'EditorForAdmin');
INSERT INTO "public"."green_hooks" VALUES ('13', 'AdminIndex', '首页小格子个性化显示', '1', '1382596073', '');
INSERT INTO "public"."green_hooks" VALUES ('14', 'topicComment', '评论提交方式扩展钩子。', '1', '1380163518', 'Editor');
INSERT INTO "public"."green_hooks" VALUES ('16', 'app_begin', '应用开始', '2', '1384481614', '');
INSERT INTO "public"."green_hooks" VALUES ('17', 'adminSideBar', 'adminSideBar', '1', '0', 'GreenWeixin');

-- ----------------------------
-- Table structure for green_kv
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_kv";
CREATE TABLE "public"."green_kv" (
"kv_id" int8 NOT NULL,
"kv_key" varchar(50) COLLATE "default",
"kv_value" varchar(50) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_kv" IS 'key-value通用信息存储';

-- ----------------------------
-- Records of green_kv
-- ----------------------------
INSERT INTO "public"."green_kv" VALUES ('1', 'home_theme', 'RootStrap');
INSERT INTO "public"."green_kv" VALUES ('2', 'theme_NovaGreenStudio', 'enabled');
INSERT INTO "public"."green_kv" VALUES ('3', 'theme_Twentytwelve', 'enabled');

-- ----------------------------
-- Table structure for green_link_group
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_link_group";
CREATE TABLE "public"."green_link_group" (
"link_group_id" int8 NOT NULL,
"link_group_name" varchar(20) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_link_group" IS '链接分类表';

-- ----------------------------
-- Records of green_link_group
-- ----------------------------
INSERT INTO "public"."green_link_group" VALUES ('1', 'Home');

-- ----------------------------
-- Table structure for green_links
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_links";
CREATE TABLE "public"."green_links" (
"link_id" numeric(20) NOT NULL,
"link_sort" int2,
"link_url" varchar(255) COLLATE "default",
"link_name" varchar(255) COLLATE "default",
"link_tag" varchar(255) COLLATE "default",
"link_description" varchar(255) COLLATE "default",
"link_visible" int2,
"link_img" varchar(255) COLLATE "default",
"link_group_id" int8
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_links" IS '友情链接';

-- ----------------------------
-- Records of green_links
-- ----------------------------
INSERT INTO "public"."green_links" VALUES ('1', '123', 'http://www.greencms.net/', 'GreenCMS', 'Home', 'GreenCMS首页', '1', '', '0');
INSERT INTO "public"."green_links" VALUES ('2', '124', 'http://www.zts1993.com/', 'Z的博客', 'Home', 'Z的博客', '1', '', '0');

-- ----------------------------
-- Table structure for green_log
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_log";
CREATE TABLE "public"."green_log" (
"log_id" int8 NOT NULL,
"log_type" int4,
"log_time" timestamp(6),
"group_name" varchar(255) COLLATE "default",
"module_name" varchar(255) COLLATE "default",
"action_name" varchar(255) COLLATE "default",
"user_id" int8,
"user_ip" varchar(50) COLLATE "default",
"message" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_log" IS '系统日志记录';

-- ----------------------------
-- Records of green_log
-- ----------------------------

-- ----------------------------
-- Table structure for green_login_log
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_login_log";
CREATE TABLE "public"."green_login_log" (
"login_log_id" int8 NOT NULL,
"log_user_id" varchar(255) COLLATE "default",
"log_user_name" varchar(255) COLLATE "default",
"log_password" varchar(255) COLLATE "default",
"log_geo" varchar(255) COLLATE "default",
"log_time" timestamp(6),
"log_ip" varchar(255) COLLATE "default",
"log_status" int2
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_login_log" IS '登录信息记录';

-- ----------------------------
-- Records of green_login_log
-- ----------------------------
INSERT INTO "public"."green_login_log" VALUES ('1', '-1', '', '', null, '2014-11-19 18:03:37', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('2', '1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', null, '2014-11-19 18:03:47', '127.0.0.1', '1');
INSERT INTO "public"."green_login_log" VALUES ('3', '-1', '', '', null, '2014-11-20 12:52:27', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('4', '1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', null, '2014-11-20 12:52:36', '127.0.0.1', '1');
INSERT INTO "public"."green_login_log" VALUES ('5', '-1', '', '', null, '2014-11-21 09:23:09', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('6', '1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', null, '2014-11-21 09:23:19', '127.0.0.1', '1');
INSERT INTO "public"."green_login_log" VALUES ('7', '-1', '', '', null, '2014-11-25 18:20:24', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('8', '-1', '', '', null, '2014-11-25 18:20:58', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('9', '-1', '', '', null, '2014-11-25 18:21:03', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('10', '-1', '', '', null, '2014-11-25 18:22:29', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('11', '1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', null, '2014-11-25 18:22:39', '127.0.0.1', '1');
INSERT INTO "public"."green_login_log" VALUES ('12', '-1', '', '', null, '2014-11-27 12:39:15', '127.0.0.1', '-1');
INSERT INTO "public"."green_login_log" VALUES ('13', '1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', null, '2014-11-27 12:39:27', '127.0.0.1', '1');

-- ----------------------------
-- Table structure for green_menu
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_menu";
CREATE TABLE "public"."green_menu" (
"menu_id" int8 NOT NULL,
"menu_pid" int8 NOT NULL,
"menu_sort" int8 NOT NULL,
"menu_name" varchar(255) COLLATE "default",
"menu_action" varchar(255) COLLATE "default",
"menu_function" varchar(255) COLLATE "default",
"menu_position" varchar(255) COLLATE "default",
"menu_url" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_menu" IS '菜单栏';

-- ----------------------------
-- Records of green_menu
-- ----------------------------
INSERT INTO "public"."green_menu" VALUES ('1', '0', '10000', '测试分类', '_self', 'getCatURLByID', 'head', '1');
INSERT INTO "public"."green_menu" VALUES ('2', '0', '99', '空链接', '_self', 'none', 'head', null);
INSERT INTO "public"."green_menu" VALUES ('3', '0', '99', '测试页面', '_self', 'getSingleURLByID', 'head', '["2","page"]');
INSERT INTO "public"."green_menu" VALUES ('4', '0', '99', '测试文章', '_self', 'getSingleURLByID', 'head', '["1","single"]');

-- ----------------------------
-- Table structure for green_mysql
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_mysql";
CREATE TABLE "public"."green_mysql" (
"mysql_id" int8 NOT NULL
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of green_mysql
-- ----------------------------

-- ----------------------------
-- Table structure for green_node
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_node";
CREATE TABLE "public"."green_node" (
"id" int4 NOT NULL,
"name" varchar(20) COLLATE "default" NOT NULL,
"title" varchar(50) COLLATE "default",
"status" int2,
"remark" varchar(255) COLLATE "default",
"sort" int4,
"pid" int4 NOT NULL,
"level" int2 NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_node" IS '权限节点表';

-- ----------------------------
-- Records of green_node
-- ----------------------------
INSERT INTO "public"."green_node" VALUES ('1', 'Admin', '后台管理', '1', '后台管理', '0', '0', '1');
INSERT INTO "public"."green_node" VALUES ('2', 'Index', 'IndexController', '1', 'IndexController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('3', 'Access', 'AccessController', '1', 'AccessController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('4', 'Custom', 'CustomController', '1', 'CustomController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('5', 'Data', 'DataController', '1', 'DataController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('6', 'System', 'SystemController', '1', 'SystemController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('7', 'Media', 'MediaController', '1', 'MediaController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('8', 'Ueditor', 'UeditorController', '1', 'UeditorController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('9', 'Posts', 'PostsController', '1', 'PostsController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('10', 'Tools', 'ToolsController', '1', 'ToolsController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('11', 'Addons', 'AddonsController', '1', 'AddonsController', '0', '1', '2');
INSERT INTO "public"."green_node" VALUES ('12', 'index', 'index', '1', 'index', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('13', 'main', 'main', '1', 'main', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('14', 'checkTodo', 'checkTodo', '1', 'checkTodo', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('15', 'ajaxCron', 'ajaxCron', '1', 'ajaxCron', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('16', 'changePass', 'changePass', '1', 'changePass', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('17', 'changepassHandle', 'changepassHandle', '1', 'changepassHandle', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('18', 'profile', 'profile', '1', 'profile', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('19', 'sns', 'sns', '1', 'sns', '0', '2', '3');
INSERT INTO "public"."green_node" VALUES ('20', 'index', 'index', '1', 'index', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('21', 'guest', 'guest', '1', 'guest', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('22', 'indexlocked', 'indexlocked', '1', 'indexlocked', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('23', 'indexlock', 'indexlock', '1', 'indexlock', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('24', 'rolelist', 'rolelist', '1', 'rolelist', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('25', 'nodelist', 'nodelist', '1', 'nodelist', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('26', 'rebuildAccess', 'rebuildAccess', '1', 'rebuildAccess', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('27', 'addUser', 'addUser', '1', 'addUser', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('28', 'addUserHandle', 'addUserHandle', '1', 'addUserHandle', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('29', 'editUser', 'editUser', '1', 'editUser', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('30', 'delUser', 'delUser', '1', 'delUser', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('31', 'addRole', 'addRole', '1', 'addRole', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('32', 'editRole', 'editRole', '1', 'editRole', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('33', 'changeRole', 'changeRole', '1', 'changeRole', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('34', 'setrolecat', 'setrolecat', '1', 'setrolecat', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('35', 'opNodeStatus', 'opNodeStatus', '1', 'opNodeStatus', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('36', 'opRoleStatus', 'opRoleStatus', '1', 'opRoleStatus', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('37', 'opSort', 'opSort', '1', 'opSort', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('38', 'addRoleHandle', 'addRoleHandle', '1', 'addRoleHandle', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('39', 'addNode', 'addNode', '1', 'addNode', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('40', 'editNode', 'editNode', '1', 'editNode', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('41', 'addNodeHandle', 'addNodeHandle', '1', 'addNodeHandle', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('42', 'loginlogclearHandle', 'loginlogclearHandle', '1', 'loginlogclearHandle', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('43', 'loginlog', 'loginlog', '1', 'loginlog', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('44', 'profile', 'profile', '1', 'profile', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('45', 'profileAll', 'profileAll', '1', 'profileAll', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('46', 'log', 'log', '1', 'log', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('47', 'logclearHandle', 'logclearHandle', '1', 'logclearHandle', '0', '3', '3');
INSERT INTO "public"."green_node" VALUES ('48', 'index', 'index', '1', 'index', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('49', 'theme', 'theme', '1', 'theme', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('50', 'menu', 'menu', '1', 'menu', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('51', 'menuInc', 'menuInc', '1', 'menuInc', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('52', 'menuDec', 'menuDec', '1', 'menuDec', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('53', 'menuDel', 'menuDel', '1', 'menuDel', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('54', 'menuAdd', 'menuAdd', '1', 'menuAdd', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('55', 'menuAddHandle', 'menuAddHandle', '1', 'menuAddHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('56', 'menuEdit', 'menuEdit', '1', 'menuEdit', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('57', 'menuEditHandle', 'menuEditHandle', '1', 'menuEditHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('58', 'plugin', 'plugin', '1', 'plugin', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('59', 'pluginAdd', 'pluginAdd', '1', 'pluginAdd', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('60', 'pluginAddLocal', 'pluginAddLocal', '1', 'pluginAddLocal', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('61', 'pluginDelHandle', 'pluginDelHandle', '1', 'pluginDelHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('62', 'create', 'create', '1', 'create', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('63', 'checkForm', 'checkForm', '1', 'checkForm', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('64', 'build', 'build', '1', 'build', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('65', 'preview', 'preview', '1', 'preview', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('66', 'adminList', 'adminList', '1', 'adminList', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('67', 'pluginEnable', 'pluginEnable', '1', 'pluginEnable', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('68', 'pluginDisable', 'pluginDisable', '1', 'pluginDisable', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('69', 'config', 'config', '1', 'config', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('70', 'install', 'install', '1', 'install', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('71', 'uninstall', 'uninstall', '1', 'uninstall', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('72', 'hooks', 'hooks', '1', 'hooks', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('73', 'addhook', 'addhook', '1', 'addhook', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('74', 'edithook', 'edithook', '1', 'edithook', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('75', 'delhook', 'delhook', '1', 'delhook', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('76', 'updateHook', 'updateHook', '1', 'updateHook', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('77', 'execute', 'execute', '1', 'execute', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('78', 'linkgroup', 'linkgroup', '1', 'linkgroup', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('79', 'addlinkgroup', 'addlinkgroup', '1', 'addlinkgroup', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('80', 'addlinkgroupHandle', 'addlinkgroupHandle', '1', 'addlinkgroupHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('81', 'dellinkgroupHandle', 'dellinkgroupHandle', '1', 'dellinkgroupHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('82', 'editlinkgroup', 'editlinkgroup', '1', 'editlinkgroup', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('83', 'editlinkgroupHandle', 'editlinkgroupHandle', '1', 'editlinkgroupHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('84', 'links', 'links', '1', 'links', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('85', 'addlink', 'addlink', '1', 'addlink', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('86', 'editlink', 'editlink', '1', 'editlink', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('87', 'dellink', 'dellink', '1', 'dellink', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('88', 'themeInstallHandle', 'themeInstallHandle', '1', 'themeInstallHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('89', 'themeUninstallHandle', 'themeUninstallHandle', '1', 'themeUninstallHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('90', 'themeDetail', 'themeDetail', '1', 'themeDetail', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('91', 'themeConfig', 'themeConfig', '1', 'themeConfig', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('92', 'themeConfigHandle', 'themeConfigHandle', '1', 'themeConfigHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('93', 'themeAdd', 'themeAdd', '1', 'themeAdd', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('94', 'themeAddLocal', 'themeAddLocal', '1', 'themeAddLocal', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('95', 'themeDisableHandle', 'themeDisableHandle', '1', 'themeDisableHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('96', 'themeEnableHandle', 'themeEnableHandle', '1', 'themeEnableHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('97', 'themeChangeHandle', 'themeChangeHandle', '1', 'themeChangeHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('98', 'themeDelHandle', 'themeDelHandle', '1', 'themeDelHandle', '0', '4', '3');
INSERT INTO "public"."green_node" VALUES ('99', 'index', 'index', '1', 'index', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('100', 'backupHandle', 'backupHandle', '1', 'backupHandle', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('101', 'restore', 'restore', '1', 'restore', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('102', 'restoreData', 'restoreData', '1', 'restoreData', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('103', 'delSqlFiles', 'delSqlFiles', '1', 'delSqlFiles', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('104', 'sendSql', 'sendSql', '1', 'sendSql', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('105', 'zipSql', 'zipSql', '1', 'zipSql', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('106', 'zipList', 'zipList', '1', 'zipList', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('107', 'unzipSqlfile', 'unzipSqlfile', '1', 'unzipSqlfile', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('108', 'delZipFiles', 'delZipFiles', '1', 'delZipFiles', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('109', 'downFile', 'downFile', '1', 'downFile', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('110', 'integrity_testing', 'integrity_testing', '1', 'integrity_testing', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('111', 'repair', 'repair', '1', 'repair', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('112', 'clear', 'clear', '1', 'clear', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('113', 'clearAll', 'clearAll', '1', 'clearAll', '0', '5', '3');
INSERT INTO "public"."green_node" VALUES ('114', 'index', 'index', '1', 'index', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('115', 'attach', 'attach', '1', 'attach', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('116', 'user', 'user', '1', 'user', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('117', 'post', 'post', '1', 'post', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('118', 'saveHandle', 'saveHandle', '1', 'saveHandle', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('119', 'kvset', 'kvset', '1', 'kvset', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('120', 'kvsetHandle', 'kvsetHandle', '1', 'kvsetHandle', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('121', 'url', 'url', '1', 'url', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('122', 'email', 'email', '1', 'email', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('123', 'safe', 'safe', '1', 'safe', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('124', 'checkupdate', 'checkupdate', '1', 'checkupdate', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('125', 'update', 'update', '1', 'update', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('126', 'updateHandle', 'updateHandle', '1', 'updateHandle', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('127', 'updateComplete', 'updateComplete', '1', 'updateComplete', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('128', 'info', 'info', '1', 'info', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('129', 'green', 'green', '1', 'green', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('130', 'sns', 'sns', '1', 'sns', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('131', 'phpinfo', 'phpinfo', '1', 'phpinfo', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('132', 'db', 'db', '1', 'db', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('133', 'cache', 'cache', '1', 'cache', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('134', 'bugs', 'bugs', '1', 'bugs', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('135', 'bugsHandle', 'bugsHandle', '1', 'bugsHandle', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('136', 'file', 'file', '1', 'file', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('137', 'fileConnect', 'fileConnect', '1', 'fileConnect', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('138', 'backupFile', 'backupFile', '1', 'backupFile', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('139', 'backupFileHandle', 'backupFileHandle', '1', 'backupFileHandle', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('140', 'restoreFile', 'restoreFile', '1', 'restoreFile', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('141', 'delFileHandle', 'delFileHandle', '1', 'delFileHandle', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('142', 'restoreFileHandle', 'restoreFileHandle', '1', 'restoreFileHandle', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('143', 'downFile', 'downFile', '1', 'downFile', '0', '7', '3');
INSERT INTO "public"."green_node" VALUES ('144', 'index', 'index', '1', 'index', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('145', 'getContent', 'getContent', '1', 'getContent', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('146', 'fileUp', 'fileUp', '1', 'fileUp', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('147', 'getRemoteImage', 'getRemoteImage', '1', 'getRemoteImage', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('148', 'getRemoteImage2', 'getRemoteImage2', '1', 'getRemoteImage2', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('149', 'getMovie', 'getMovie', '1', 'getMovie', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('150', 'imageManager', 'imageManager', '1', 'imageManager', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('151', 'imageUp', 'imageUp', '1', 'imageUp', '0', '8', '3');
INSERT INTO "public"."green_node" VALUES ('152', 'indexHandle', 'indexHandle', '1', 'indexHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('153', 'single', 'single', '1', 'single', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('154', 'index', 'index', '1', 'index', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('155', 'page', 'page', '1', 'page', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('156', 'recycle', 'recycle', '1', 'recycle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('157', 'reverify', 'reverify', '1', 'reverify', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('158', 'draft', 'draft', '1', 'draft', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('159', 'unverified', 'unverified', '1', 'unverified', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('160', 'add', 'add', '1', 'add', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('161', 'autoSave', 'autoSave', '1', 'autoSave', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('162', 'addHandle', 'addHandle', '1', 'addHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('163', 'recycleHandle', 'recycleHandle', '1', 'recycleHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('164', 'unverifiedHandle', 'unverifiedHandle', '1', 'unverifiedHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('165', 'reverifyHandle', 'reverifyHandle', '1', 'reverifyHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('166', 'preDel', 'preDel', '1', 'preDel', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('167', 'del', 'del', '1', 'del', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('168', 'emptyRecycleHandle', 'emptyRecycleHandle', '1', 'emptyRecycleHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('169', 'posts', 'posts', '1', 'posts', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('170', 'category', 'category', '1', 'category', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('171', 'addCategory', 'addCategory', '1', 'addCategory', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('172', 'addCategoryHandle', 'addCategoryHandle', '1', 'addCategoryHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('173', 'editCategory', 'editCategory', '1', 'editCategory', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('174', 'editCategoryHandle', 'editCategoryHandle', '1', 'editCategoryHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('175', 'delCategory', 'delCategory', '1', 'delCategory', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('176', 'tag', 'tag', '1', 'tag', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('177', 'addTag', 'addTag', '1', 'addTag', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('178', 'addTagHandle', 'addTagHandle', '1', 'addTagHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('179', 'editTag', 'editTag', '1', 'editTag', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('180', 'editTagHandle', 'editTagHandle', '1', 'editTagHandle', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('181', 'delTag', 'delTag', '1', 'delTag', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('182', '_empty', '_empty', '1', '_empty', '0', '9', '3');
INSERT INTO "public"."green_node" VALUES ('183', 'index', 'index', '1', 'index', '0', '10', '3');
INSERT INTO "public"."green_node" VALUES ('184', 'wordpress', 'wordpress', '1', 'wordpress', '0', '10', '3');
INSERT INTO "public"."green_node" VALUES ('185', 'wordpressHandle', 'wordpressHandle', '1', 'wordpressHandle', '0', '10', '3');
INSERT INTO "public"."green_node" VALUES ('186', 'log', 'log', '1', 'log', '0', '10', '3');
INSERT INTO "public"."green_node" VALUES ('187', 'logClearHandle', 'logClearHandle', '1', 'logClearHandle', '0', '10', '3');
INSERT INTO "public"."green_node" VALUES ('188', 'downFile', 'downFile', '1', 'downFile', '0', '10', '3');
INSERT INTO "public"."green_node" VALUES ('189', 'execute', 'execute', '1', 'execute', '0', '11', '3');
INSERT INTO "public"."green_node" VALUES ('190', 'Weixin', '微信管理', '1', '微信管理', '100', '0', '1');
INSERT INTO "public"."green_node" VALUES ('191', 'Home', 'HomeController', '1', 'HomeController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('192', 'Menu', 'MenuController', '1', 'MenuController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('193', 'Message', 'MessageController', '1', 'MessageController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('194', 'Reply', 'ReplyController', '1', 'ReplyController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('195', 'System', 'SystemController', '1', 'SystemController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('196', 'Rule', 'RuleController', '1', 'RuleController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('197', 'User', 'UserController', '1', 'UserController', '0', '190', '2');
INSERT INTO "public"."green_node" VALUES ('198', 'index', 'index', '1', 'index', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('199', 'indexHandle', 'indexHandle', '1', 'indexHandle', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('200', 'index', 'index', '1', 'index', '0', '191', '3');
INSERT INTO "public"."green_node" VALUES ('201', 'changePass', 'changePass', '1', 'changePass', '0', '191', '3');
INSERT INTO "public"."green_node" VALUES ('202', 'changePassHandle', 'changePassHandle', '1', 'changePassHandle', '0', '191', '3');
INSERT INTO "public"."green_node" VALUES ('203', 'index', 'index', '1', 'index', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('204', 'edit', 'edit', '1', 'edit', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('205', 'editHandle', 'editHandle', '1', 'editHandle', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('206', 'additem', 'additem', '1', 'additem', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('207', 'additemHandle', 'additemHandle', '1', 'additemHandle', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('208', 'delitem', 'delitem', '1', 'delitem', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('209', 'del', 'del', '1', 'del', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('210', 'restore', 'restore', '1', 'restore', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('211', 'sync', 'sync', '1', 'sync', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('212', 'update', 'update', '1', 'update', '0', '192', '3');
INSERT INTO "public"."green_node" VALUES ('213', 'index', 'index', '1', 'index', '0', '193', '3');
INSERT INTO "public"."green_node" VALUES ('214', 'del', 'del', '1', 'del', '0', '193', '3');
INSERT INTO "public"."green_node" VALUES ('215', 'delAll', 'delAll', '1', 'delAll', '0', '193', '3');
INSERT INTO "public"."green_node" VALUES ('216', 'send', 'send', '1', 'send', '0', '193', '3');
INSERT INTO "public"."green_node" VALUES ('217', 'sendHandle', 'sendHandle', '1', 'sendHandle', '0', '193', '3');
INSERT INTO "public"."green_node" VALUES ('218', 'index', 'index', '1', 'index', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('219', 'text', 'text', '1', 'text', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('220', 'textAddHandle', 'textAddHandle', '1', 'textAddHandle', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('221', 'textEdit', 'textEdit', '1', 'textEdit', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('222', 'textEditHandle', 'textEditHandle', '1', 'textEditHandle', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('223', 'pic', 'pic', '1', 'pic', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('224', 'picAddHandle', 'picAddHandle', '1', 'picAddHandle', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('225', 'news', 'news', '1', 'news', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('226', 'newsEdit', 'newsEdit', '1', 'newsEdit', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('227', 'newsEditHandle', 'newsEditHandle', '1', 'newsEditHandle', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('228', 'newsAddHandle', 'newsAddHandle', '1', 'newsAddHandle', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('229', 'del', 'del', '1', 'del', '0', '194', '3');
INSERT INTO "public"."green_node" VALUES ('230', 'index', 'index', '1', 'index', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('231', 'indexHandle', 'indexHandle', '1', 'indexHandle', '0', '6', '3');
INSERT INTO "public"."green_node" VALUES ('232', 'index', 'index', '1', 'index', '0', '196', '3');
INSERT INTO "public"."green_node" VALUES ('233', 'add', 'add', '1', 'add', '0', '196', '3');
INSERT INTO "public"."green_node" VALUES ('234', 'edit', 'edit', '1', 'edit', '0', '196', '3');
INSERT INTO "public"."green_node" VALUES ('235', 'editHandle', 'editHandle', '1', 'editHandle', '0', '196', '3');
INSERT INTO "public"."green_node" VALUES ('236', 'addHandle', 'addHandle', '1', 'addHandle', '0', '196', '3');
INSERT INTO "public"."green_node" VALUES ('237', 'del', 'del', '1', 'del', '0', '196', '3');
INSERT INTO "public"."green_node" VALUES ('238', 'index', 'index', '1', 'index', '0', '197', '3');
INSERT INTO "public"."green_node" VALUES ('239', 'updatenew', 'updatenew', '1', 'updatenew', '0', '197', '3');
INSERT INTO "public"."green_node" VALUES ('240', 'updateall', 'updateall', '1', 'updateall', '0', '197', '3');
INSERT INTO "public"."green_node" VALUES ('241', 'detail', 'detail', '1', 'detail', '0', '197', '3');
INSERT INTO "public"."green_node" VALUES ('242', 'chat', 'chat', '1', 'chat', '0', '197', '3');
INSERT INTO "public"."green_node" VALUES ('243', 'chatHandle', 'chatHandle', '1', 'chatHandle', '0', '197', '3');

-- ----------------------------
-- Table structure for green_options
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_options";
CREATE TABLE "public"."green_options" (
"option_id" numeric(20) NOT NULL,
"option_name" varchar(64) COLLATE "default" NOT NULL,
"option_value" text COLLATE "default" NOT NULL,
"autoload" varchar(20) COLLATE "default" NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_options" IS '选项表';

-- ----------------------------
-- Records of green_options
-- ----------------------------
INSERT INTO "public"."green_options" VALUES ('1', 'site_url', 'http://127.0.0.1/green2014', 'yes');
INSERT INTO "public"."green_options" VALUES ('2', 'title', 'GreenCMS 2014', 'yes');
INSERT INTO "public"."green_options" VALUES ('3', 'keywords', 'GreenCMS v2 based on ThinkPHP 3.2.2', 'yes');
INSERT INTO "public"."green_options" VALUES ('4', 'description', 'GreenCMS created by GreenStudio ', 'yes');
INSERT INTO "public"."green_options" VALUES ('5', 'foot', '', 'yes');
INSERT INTO "public"."green_options" VALUES ('6', 'ip_geo', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('7', 'software_author', 'GreenStudio', 'yes');
INSERT INTO "public"."green_options" VALUES ('8', 'widget_about_us', '关于我们', 'yes');
INSERT INTO "public"."green_options" VALUES ('9', 'software_homepage', 'http://www.greencms.net', 'yes');
INSERT INTO "public"."green_options" VALUES ('10', 'software_version', 'v2.1.1107', 'yes');
INSERT INTO "public"."green_options" VALUES ('11', 'software_name', 'GreenCMS v2', 'yes');
INSERT INTO "public"."green_options" VALUES ('12', 'LOG_RECORD', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('13', 'software_build', '20141107', 'yes');
INSERT INTO "public"."green_options" VALUES ('14', 'HTML_CACHE_ON', '0', 'false');
INSERT INTO "public"."green_options" VALUES ('15', 'sqlFileSize', '500000000', 'yes');
INSERT INTO "public"."green_options" VALUES ('16', 'send_mail', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('17', 'smtp_host', 'mail.njut.edu.cn', 'yes');
INSERT INTO "public"."green_options" VALUES ('18', 'smtp_port', '25', 'yes');
INSERT INTO "public"."green_options" VALUES ('19', 'smtp_user', 'test@njut.edu.cn', 'yes');
INSERT INTO "public"."green_options" VALUES ('20', 'from_email', 'test@njut.edu.cn', 'yes');
INSERT INTO "public"."green_options" VALUES ('21', 'smtp_pass', ' ', 'yes');
INSERT INTO "public"."green_options" VALUES ('22', 'PAGER', '20', 'yes');
INSERT INTO "public"."green_options" VALUES ('23', 'oem_info', 'original', 'yes');
INSERT INTO "public"."green_options" VALUES ('24', 'db_fieldtype_check', '0', 'yes');
INSERT INTO "public"."green_options" VALUES ('25', 'DEFAULT_FILTER', 'htmlspecialchars', 'yes');
INSERT INTO "public"."green_options" VALUES ('26', 'COOKIE_PREFIX', 'greencms_', 'yes');
INSERT INTO "public"."green_options" VALUES ('27', 'COOKIE_EXPIRE', '3600', 'yes');
INSERT INTO "public"."green_options" VALUES ('28', 'COOKIE_DOMAIN', '', 'yes');
INSERT INTO "public"."green_options" VALUES ('29', 'COOKIE_PATH', '/', 'yes');
INSERT INTO "public"."green_options" VALUES ('30', 'DB_FIELDS_CACHE', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('31', 'DB_SQL_BUILD_CACHE', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('32', 'sql_mail', '', 'yes');
INSERT INTO "public"."green_options" VALUES ('33', 'SHOW_CHROME_TRACE', '0', 'yes');
INSERT INTO "public"."green_options" VALUES ('34', 'users_can_register', 'on', 'yes');
INSERT INTO "public"."green_options" VALUES ('35', 'feed_open', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('36', 'feed_num', '20', 'yes');
INSERT INTO "public"."green_options" VALUES ('37', 'Weixin_reply_subscribe', '欢迎使用Z的博客微信服务平台！回复help获得使用帮助', 'yes');
INSERT INTO "public"."green_options" VALUES ('38', 'Weixin_appid', ' ', 'yes');
INSERT INTO "public"."green_options" VALUES ('39', 'Weixin_secret', ' ', 'yes');
INSERT INTO "public"."green_options" VALUES ('40', 'Weixin_menu', ' ', 'yes');
INSERT INTO "public"."green_options" VALUES ('41', 'weixin_token', ' ', 'yes');
INSERT INTO "public"."green_options" VALUES ('42', 'home_url_model', '0', 'yes');
INSERT INTO "public"."green_options" VALUES ('43', 'home_cat_model', 'native', 'yes');
INSERT INTO "public"."green_options" VALUES ('44', 'home_tag_model', 'native', 'yes');
INSERT INTO "public"."green_options" VALUES ('45', 'home_post_model', 'native', 'yes');
INSERT INTO "public"."green_options" VALUES ('46', 'DATA_CACHE_TIME', '30', 'yes');
INSERT INTO "public"."green_options" VALUES ('47', 'HTML_CACHE_TIME', '60', 'yes');
INSERT INTO "public"."green_options" VALUES ('48', 'vertify_code', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('49', 'SHOW_PAGE_TRACE', '1', 'yes');
INSERT INTO "public"."green_options" VALUES ('50', 'DATA_CACHE_TYPE', 'File', 'yes');

-- ----------------------------
-- Table structure for green_plugin
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_plugin";
CREATE TABLE "public"."green_plugin" (
"plugin_id" int4 NOT NULL,
"plugin_status" int2 NOT NULL,
"plugin_title" char(20) COLLATE "default" NOT NULL,
"plugin_description" text COLLATE "default" NOT NULL,
"plugin_author" char(20) COLLATE "default" NOT NULL,
"plugin_copyright" char(50) COLLATE "default" NOT NULL,
"plugin_pubdate" timestamp(6) NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_plugin" IS '插件信息表';

-- ----------------------------
-- Records of green_plugin
-- ----------------------------

-- ----------------------------
-- Table structure for green_post_cat
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_post_cat";
CREATE TABLE "public"."green_post_cat" (
"pc_id" int8 NOT NULL,
"cat_id" int8 NOT NULL,
"post_id" int8 NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_post_cat" IS '文章分类';

-- ----------------------------
-- Records of green_post_cat
-- ----------------------------
INSERT INTO "public"."green_post_cat" VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for green_post_meta
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_post_meta";
CREATE TABLE "public"."green_post_meta" (
"meta_id" numeric(20) NOT NULL,
"post_id" numeric(20) NOT NULL,
"meta_key" varchar(255) COLLATE "default",
"meta_value" text COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_post_meta" IS '文章meta';

-- ----------------------------
-- Records of green_post_meta
-- ----------------------------

-- ----------------------------
-- Table structure for green_post_tag
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_post_tag";
CREATE TABLE "public"."green_post_tag" (
"pt_id" int8 NOT NULL,
"tag_id" int8 NOT NULL,
"post_id" int8 NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_post_tag" IS '文章标签';

-- ----------------------------
-- Records of green_post_tag
-- ----------------------------
INSERT INTO "public"."green_post_tag" VALUES ('1', '1', '1');

-- ----------------------------
-- Table structure for green_posts
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_posts";
CREATE TABLE "public"."green_posts" (
"post_id" numeric(20) NOT NULL,
"user_id" numeric(20),
"post_date" timestamp(6),
"post_content" text COLLATE "default" NOT NULL,
"post_title" varchar(255) COLLATE "default" NOT NULL,
"post_status" varchar(20) COLLATE "default",
"post_comment_status" varchar(20) COLLATE "default",
"post_password" varchar(20) COLLATE "default",
"post_name" varchar(200) COLLATE "default",
"post_template" varchar(50) COLLATE "default" NOT NULL,
"post_modified" timestamp(6),
"post_comment_count" int8,
"post_view_count" int8,
"post_type" varchar(50) COLLATE "default" NOT NULL,
"post_img" varchar(255) COLLATE "default",
"post_top" int2,
"post_url" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_posts" IS '文章列表';

-- ----------------------------
-- Records of green_posts
-- ----------------------------
INSERT INTO "public"."green_posts" VALUES ('1', '1', '2014-11-19 18:03:24', '你好,世界', '欢迎使用GreenCMS', 'publish', 'open', '', 'helloworld', 'detail', '2014-11-19 18:03:24', '0', '7', 'single', null, '0', '');
INSERT INTO "public"."green_posts" VALUES ('2', '1', '2014-11-19 18:03:24', '欢迎使用', '这是一个文章测试页面', 'publish', 'open', '', 'testpage', 'detail', '2014-11-19 18:03:24', '0', '1', 'page', null, '0', '');
INSERT INTO "public"."green_posts" VALUES ('7', '1', '2014-11-28 13:11:36', 'content', 'title', 'draft', 'open', '', 'title', 'single', '2014-11-28 13:11:36', '0', '0', 'single', null, '0', null);

-- ----------------------------
-- Table structure for green_role
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_role";
CREATE TABLE "public"."green_role" (
"id" int4 NOT NULL,
"name" varchar(20) COLLATE "default" NOT NULL,
"pid" int2,
"status" int2,
"remark" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_role" IS '角色';

-- ----------------------------
-- Records of green_role
-- ----------------------------
INSERT INTO "public"."green_role" VALUES ('1', '超级管理员', '1', '1', '系统内置超级管理员组');
INSERT INTO "public"."green_role" VALUES ('2', '网站管理员', '2', '1', '拥有系统仅此于超级管理员的权限');
INSERT INTO "public"."green_role" VALUES ('3', '内容管理员', '3', '1', '拥有发布文章权利');
INSERT INTO "public"."green_role" VALUES ('4', '投稿员', '4', '1', '只能投稿默认为未审核');
INSERT INTO "public"."green_role" VALUES ('5', '游客', '5', '1', '基本信息修改');

-- ----------------------------
-- Table structure for green_role_users
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_role_users";
CREATE TABLE "public"."green_role_users" (
"role_id" int4,
"user_id" int8 NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_role_users" IS '用户角色表';

-- ----------------------------
-- Records of green_role_users
-- ----------------------------
INSERT INTO "public"."green_role_users" VALUES ('1', '1');

-- ----------------------------
-- Table structure for green_tags
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_tags";
CREATE TABLE "public"."green_tags" (
"tag_id" numeric(20) NOT NULL,
"tag_name" varchar(200) COLLATE "default" NOT NULL,
"tag_slug" varchar(200) COLLATE "default" NOT NULL,
"tag_group" int8 NOT NULL
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_tags" IS '标签页';

-- ----------------------------
-- Records of green_tags
-- ----------------------------
INSERT INTO "public"."green_tags" VALUES ('1', '测试标签', 'test_tag', '0');
INSERT INTO "public"."green_tags" VALUES ('2', 'hello', 'hello', '0');

-- ----------------------------
-- Table structure for green_theme
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_theme";
CREATE TABLE "public"."green_theme" (
"theme_id" int8 NOT NULL,
"theme_name" varchar(255) COLLATE "default",
"theme_description" varchar(255) COLLATE "default",
"theme_build" varchar(255) COLLATE "default",
"theme_versioin" varchar(255) COLLATE "default",
"theme_preview" varchar(255) COLLATE "default",
"theme_copyright" varchar(255) COLLATE "default",
"theme_xml" text COLLATE "default",
"theme_config" text COLLATE "default"
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of green_theme
-- ----------------------------
INSERT INTO "public"."green_theme" VALUES ('13', 'NovaGreenStudio', 'Nova Theme For GreenStudio', '20140527', '2', '/NovaGreenStudio/preview.jpg', 'GreenStudio 2014', '<theme>
    <name>NovaGreenStudio</name>
    <version>2</version>
    <build>20140527</build>
    <description>Nova Theme For GreenStudio</description>
    <author>ZTS</author>
    <preview>/NovaGreenStudio/preview.jpg</preview>
    <preview_big>/NovaGreenStudio/preview_big.jpg</preview_big>
    <copyright>GreenStudio 2014</copyright>
    <tpl_view>Application/Home/View/NovaGreenStudio</tpl_view>
    <tpl_static>Public/NovaGreenStudio</tpl_static>
    <introduction>GreenStudio 2015主题</introduction>
    <post>
        <single>
            <name>文章</name>
            <tpl>single</tpl>
        </single>
        <page>
            <name>无侧栏</name>
            <tpl>page</tpl>
        </page>
        <guestbook>
            <name>留言板</name>
            <tpl>guestbook</tpl>
        </guestbook>
        <joinus>
            <name>加入我们</name>
            <tpl>joinus</tpl>
        </joinus>
    </post>
    <config>
        <kv>
            <title>
                <title>显示标题</title>
                <type>text</type>
                <value>天气预报</value>

            </title>

            <showplace>
                <title>显示</title>
                <type>checkbox</type>
                <options>
                    <true>显示</true>
                    <false>不显示</false>
                </options>
                <value>true</value>

            </showplace>


        </kv>


    </config>
</theme>', '{"kv":{"title":{"title":"\u663e\u793a\u6807\u9898","type":"text","value":"\u5929\u6c14\u9884\u62a5"},"showplace":{"title":"\u663e\u793a","type":"checkbox","options":{"true":"\u663e\u793a","false":"\u4e0d\u663e\u793a"},"value":"true"}},"post_type":{"single":{"name":"\u6587\u7ae0","tpl":"single"},"page":{"name":"\u65e0\u4fa7\u680f","tpl":"page"},"guestbook":{"name":"\u7559\u8a00\u677f","tpl":"guestbook"},"joinus":{"name":"\u52a0\u5165\u6211\u4eec","tpl":"joinus"}}}');
INSERT INTO "public"."green_theme" VALUES ('15', 'Xsc', '南工大学生处学院网站', '20140415', '1', '/Xsc/preview.jpg', 'GreenStudio 2014', '<theme>
    <name>Xsc</name>
    <version>1</version>
    <build>20140415</build>
    <description>南工大学生处学院网站</description>
    <author>GreenStudio</author>
    <preview>/Xsc/preview.jpg</preview>
    <preview_big>/Xsc/preview_big.jpg</preview_big>
    <copyright>GreenStudio 2014</copyright>
    <tpl_view>Apllication/Home/View/Xsc</tpl_view>
    <tpl_static>Public/Xsc</tpl_static>
    <introduction>南工大学生处学院网站</introduction>
    <post>
        <single>
            <name>文章</name>
            <tpl>single</tpl>
        </single>
        <page>
            <name>无侧栏</name>
            <tpl>page</tpl>
        </page>
        <guestbook>
            <name>留言板</name>
            <tpl>guestbook</tpl>
        </guestbook>
    </post>


    <config>
        <kv>
            <zt1>
                <title>专题一</title>
                <type>text</type>
                <value>周恩来版</value>
            </zt1>

            <zt1url>
                <title>专题一链接</title>
                <type>text</type>
                <value>周恩来版</value>
            </zt1url>

        </kv>
    </config>

</theme>', '{"kv":{"zt1":{"title":"\u4e13\u9898\u4e00","type":"text","value":"\u5468\u6069\u6765\u7248"},"zt1url":{"title":"\u4e13\u9898\u4e00\u94fe\u63a5","type":"text","value":"\u5468\u6069\u6765\u7248"}},"post_type":{"single":{"name":"\u6587\u7ae0","tpl":"single"},"page":{"name":"\u65e0\u4fa7\u680f","tpl":"page"},"guestbook":{"name":"\u7559\u8a00\u677f","tpl":"guestbook"}}}');
INSERT INTO "public"."green_theme" VALUES ('16', 'RootStrap', 'RootStrap from wordpress', '20140303', '2', '/RootStrap/preview.jpg', 'GreenStudio 2011', '<theme>
    <name>RootStrap</name>
    <version>2</version>
    <build>20140303</build>
    <description>RootStrap from wordpress</description>
    <author>ZTS</author>
    <preview>/RootStrap/preview.jpg</preview>
    <preview_big>/RootStrap/preview_big.jpg</preview_big>
    <copyright>GreenStudio 2011</copyright>
    <tpl_view>Apllication/Home/View/RootStrap</tpl_view>
    <tpl_static>Public/RootStrap</tpl_static>
    <introduction>RootStrap博客主题</introduction>
    <post>
        <single>
            <name>文章</name>
            <tpl>single</tpl>
        </single>
        <page>
            <name>无侧栏</name>
            <tpl>page</tpl>
        </page>

    </post>
</theme>', '{"post_type":{"single":{"name":"\u6587\u7ae0","tpl":"single"},"page":{"name":"\u65e0\u4fa7\u680f","tpl":"page"}}}');

-- ----------------------------
-- Table structure for green_user
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_user";
CREATE TABLE "public"."green_user" (
"user_id" numeric(20) NOT NULL,
"user_login" varchar(60) COLLATE "default" NOT NULL,
"user_pass" varchar(64) COLLATE "default" NOT NULL,
"user_nicename" varchar(50) COLLATE "default" NOT NULL,
"user_email" varchar(100) COLLATE "default" NOT NULL,
"user_url" varchar(100) COLLATE "default",
"user_registered" timestamp(6),
"user_activation_key" varchar(60) COLLATE "default",
"user_status" int4,
"user_intro" text COLLATE "default",
"user_level" int2 NOT NULL,
"user_session" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_user" IS '用户表';

-- ----------------------------
-- Records of green_user
-- ----------------------------
INSERT INTO "public"."green_user" VALUES ('1', 'admin', 'b7c60757f3dbae118fb9acfc0ff01685', '管理员', 'admin@njut.asia', '', '2014-11-19 18:11:22', '', '1', '我是admin，欢迎使用', '2', '412969fca9da88baf017694cd44e207f');

-- ----------------------------
-- Table structure for green_user_detail
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_user_detail";
CREATE TABLE "public"."green_user_detail" (
"user_id" int8 NOT NULL,
"user_into" varchar(255) COLLATE "default"
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_user_detail" IS '用户详细信息';

-- ----------------------------
-- Records of green_user_detail
-- ----------------------------

-- ----------------------------
-- Table structure for green_user_sns
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_user_sns";
CREATE TABLE "public"."green_user_sns" (
"us_id" int8 NOT NULL,
"user_id" int8,
"access_token" varchar(50) COLLATE "default",
"refresh_token" varchar(50) COLLATE "default",
"remind_in" varchar(50) COLLATE "default",
"expires_in" varchar(50) COLLATE "default",
"openid" varchar(50) COLLATE "default",
"type" varchar(50) COLLATE "default",
"expires_time" timestamp(6)
)
WITH (OIDS=FALSE)

;

-- ----------------------------
-- Records of green_user_sns
-- ----------------------------

-- ----------------------------
-- Table structure for green_weixinaction
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_weixinaction";
CREATE TABLE "public"."green_weixinaction" (
"wx_action_id" int8 NOT NULL,
"action_type" varchar(20) COLLATE "default",
"action_name" varchar(20) COLLATE "default",
"reply_type" varchar(20) COLLATE "default",
"reply_id" int8,
"time" timestamp(6)
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_weixinaction" IS '微信事件表';

-- ----------------------------
-- Records of green_weixinaction
-- ----------------------------

-- ----------------------------
-- Table structure for green_weixinlog
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_weixinlog";
CREATE TABLE "public"."green_weixinlog" (
"log_id" int8 NOT NULL,
"MsgId" varchar(255) COLLATE "default",
"FromUserName" varchar(255) COLLATE "default",
"ToUserName" varchar(255) COLLATE "default",
"CreateTime" varchar(255) COLLATE "default",
"Content" varchar(255) COLLATE "default",
"MsgType" varchar(255) COLLATE "default",
"Location_X" varchar(255) COLLATE "default",
"Location_Y" varchar(255) COLLATE "default",
"Scale" varchar(255) COLLATE "default",
"Label" varchar(255) COLLATE "default",
"PicUrl" varchar(255) COLLATE "default",
"isread" int2
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_weixinlog" IS '微信记录表';

-- ----------------------------
-- Records of green_weixinlog
-- ----------------------------

-- ----------------------------
-- Table structure for green_weixinre
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_weixinre";
CREATE TABLE "public"."green_weixinre" (
"wx_re_id" int8 NOT NULL,
"content" varchar(255) COLLATE "default",
"type" varchar(64) COLLATE "default",
"mediaId" varchar(64) COLLATE "default",
"title" varchar(64) COLLATE "default",
"description" varchar(255) COLLATE "default",
"picurl" varchar(255) COLLATE "default",
"url" varchar(255) COLLATE "default",
"group" varchar(20) COLLATE "default",
"time" timestamp(6)
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_weixinre" IS '微信模块预设定回复 表';

-- ----------------------------
-- Records of green_weixinre
-- ----------------------------

-- ----------------------------
-- Table structure for green_weixinsend
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_weixinsend";
CREATE TABLE "public"."green_weixinsend" (
"weixin_send_id" int8 NOT NULL,
"MsgId_to" varchar(255) COLLATE "default",
"openid" varchar(255) COLLATE "default",
"type" varchar(50) COLLATE "default",
"content" varchar(255) COLLATE "default",
"CreateTime" timestamp(6)
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_weixinsend" IS '微信发送信息记录表';

-- ----------------------------
-- Records of green_weixinsend
-- ----------------------------

-- ----------------------------
-- Table structure for green_weixinuser
-- ----------------------------
DROP TABLE IF EXISTS "public"."green_weixinuser";
CREATE TABLE "public"."green_weixinuser" (
"weixin_userid" int8 NOT NULL,
"openid" varchar(255) COLLATE "default",
"subscribe" int2,
"nickname" varchar(255) COLLATE "default",
"sex" int2,
"language" varchar(50) COLLATE "default",
"city" varchar(50) COLLATE "default",
"province" varchar(50) COLLATE "default",
"country" varchar(50) COLLATE "default",
"headimgurl" varchar(255) COLLATE "default",
"subscribe_time" int8
)
WITH (OIDS=FALSE)

;
COMMENT ON TABLE "public"."green_weixinuser" IS '微信模块用户信息表';

-- ----------------------------
-- Records of green_weixinuser
-- ----------------------------

-- ----------------------------
-- Alter Sequences Owned By 
-- ----------------------------

-- ----------------------------
-- Primary Key structure for table green_addons
-- ----------------------------
ALTER TABLE "public"."green_addons" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table green_cats
-- ----------------------------
ALTER TABLE "public"."green_cats" ADD PRIMARY KEY ("cat_id");

-- ----------------------------
-- Primary Key structure for table green_comments
-- ----------------------------
ALTER TABLE "public"."green_comments" ADD PRIMARY KEY ("comment_ID");

-- ----------------------------
-- Primary Key structure for table green_form
-- ----------------------------
ALTER TABLE "public"."green_form" ADD PRIMARY KEY ("fa_id");

-- ----------------------------
-- Primary Key structure for table green_hooks
-- ----------------------------
ALTER TABLE "public"."green_hooks" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table green_kv
-- ----------------------------
ALTER TABLE "public"."green_kv" ADD PRIMARY KEY ("kv_id");

-- ----------------------------
-- Primary Key structure for table green_link_group
-- ----------------------------
ALTER TABLE "public"."green_link_group" ADD PRIMARY KEY ("link_group_id");

-- ----------------------------
-- Primary Key structure for table green_links
-- ----------------------------
ALTER TABLE "public"."green_links" ADD PRIMARY KEY ("link_id");

-- ----------------------------
-- Primary Key structure for table green_log
-- ----------------------------
ALTER TABLE "public"."green_log" ADD PRIMARY KEY ("log_id");

-- ----------------------------
-- Primary Key structure for table green_login_log
-- ----------------------------
ALTER TABLE "public"."green_login_log" ADD PRIMARY KEY ("login_log_id");

-- ----------------------------
-- Primary Key structure for table green_menu
-- ----------------------------
ALTER TABLE "public"."green_menu" ADD PRIMARY KEY ("menu_id");

-- ----------------------------
-- Primary Key structure for table green_mysql
-- ----------------------------
ALTER TABLE "public"."green_mysql" ADD PRIMARY KEY ("mysql_id");

-- ----------------------------
-- Primary Key structure for table green_node
-- ----------------------------
ALTER TABLE "public"."green_node" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table green_options
-- ----------------------------
ALTER TABLE "public"."green_options" ADD PRIMARY KEY ("option_id");

-- ----------------------------
-- Primary Key structure for table green_plugin
-- ----------------------------
ALTER TABLE "public"."green_plugin" ADD PRIMARY KEY ("plugin_id");

-- ----------------------------
-- Primary Key structure for table green_post_cat
-- ----------------------------
ALTER TABLE "public"."green_post_cat" ADD PRIMARY KEY ("pc_id");

-- ----------------------------
-- Primary Key structure for table green_post_meta
-- ----------------------------
ALTER TABLE "public"."green_post_meta" ADD PRIMARY KEY ("meta_id");

-- ----------------------------
-- Primary Key structure for table green_post_tag
-- ----------------------------
ALTER TABLE "public"."green_post_tag" ADD PRIMARY KEY ("pt_id");

-- ----------------------------
-- Primary Key structure for table green_posts
-- ----------------------------
ALTER TABLE "public"."green_posts" ADD PRIMARY KEY ("post_id");

-- ----------------------------
-- Primary Key structure for table green_role
-- ----------------------------
ALTER TABLE "public"."green_role" ADD PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table green_tags
-- ----------------------------
ALTER TABLE "public"."green_tags" ADD PRIMARY KEY ("tag_id");

-- ----------------------------
-- Primary Key structure for table green_theme
-- ----------------------------
ALTER TABLE "public"."green_theme" ADD PRIMARY KEY ("theme_id");

-- ----------------------------
-- Primary Key structure for table green_user
-- ----------------------------
ALTER TABLE "public"."green_user" ADD PRIMARY KEY ("user_id");

-- ----------------------------
-- Primary Key structure for table green_user_detail
-- ----------------------------
ALTER TABLE "public"."green_user_detail" ADD PRIMARY KEY ("user_id");

-- ----------------------------
-- Primary Key structure for table green_user_sns
-- ----------------------------
ALTER TABLE "public"."green_user_sns" ADD PRIMARY KEY ("us_id");

-- ----------------------------
-- Primary Key structure for table green_weixinaction
-- ----------------------------
ALTER TABLE "public"."green_weixinaction" ADD PRIMARY KEY ("wx_action_id");

-- ----------------------------
-- Primary Key structure for table green_weixinlog
-- ----------------------------
ALTER TABLE "public"."green_weixinlog" ADD PRIMARY KEY ("log_id");

-- ----------------------------
-- Primary Key structure for table green_weixinre
-- ----------------------------
ALTER TABLE "public"."green_weixinre" ADD PRIMARY KEY ("wx_re_id");

-- ----------------------------
-- Primary Key structure for table green_weixinsend
-- ----------------------------
ALTER TABLE "public"."green_weixinsend" ADD PRIMARY KEY ("weixin_send_id");

-- ----------------------------
-- Primary Key structure for table green_weixinuser
-- ----------------------------
ALTER TABLE "public"."green_weixinuser" ADD PRIMARY KEY ("weixin_userid");
