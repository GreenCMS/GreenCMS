/*
Navicat MySQL Data Transfer

Source Server         : MySQL
Source Server Version : 50612
Source Host           : localhost:3306
Source Database       : green

Target Server Type    : MYSQL
Target Server Version : 50612
File Encoding         : 65001

Date: 2014-02-15 17:27:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `green_kv`
-- ----------------------------
DROP TABLE IF EXISTS `green_kv`;
CREATE TABLE `green_kv` (
  `kv_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `kv_key` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `kv_value` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`kv_id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='key-value通用信息存储';

-- ----------------------------
-- Records of green_kv
-- ----------------------------
INSERT INTO `green_kv` VALUES ('1', 'home_theme', 'Vena');
INSERT INTO `green_kv` VALUES ('2', 'home_url_model', '2');
INSERT INTO `green_kv` VALUES ('3', 'home_cat_model', 'native');
INSERT INTO `green_kv` VALUES ('4', 'home_tag_model', 'native');
INSERT INTO `green_kv` VALUES ('5', 'home_post_model', 'native');


ALTER TABLE `plugin`
CHANGE `plugin_pubdate` `plugin_pubdate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP;

