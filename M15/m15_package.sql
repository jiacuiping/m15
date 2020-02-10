/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : M15

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-01-10 18:12:14
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `m15_package`
-- ----------------------------
DROP TABLE IF EXISTS `m15_package`;
CREATE TABLE `m15_package` (
  `package_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主键id',
  `package_user` int(11) NOT NULL COMMENT '关联用户id',
  `package_title` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '套餐标题',
  `package_cover` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '套餐封面',
  `package_price` decimal(10,2) NOT NULL COMMENT '套餐价格',
  `package_number` int(11) NOT NULL DEFAULT '0' COMMENT '成交次数',
  `package_desc` varchar(1000) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '套餐描述',
  `package_rank` tinyint(2) DEFAULT '0' COMMENT '套餐权重',
  `package_isshow` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否展示 0-不展示 1-展示',
  `package_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`package_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of m15_package
-- ----------------------------
INSERT INTO `m15_package` VALUES ('1', '1', '套餐2', '/uploads/20200110\\8bcdfbac5f241c1ea9ecc2a154ec0582.jpeg', '1500.00', '0', ' 描述 ', '1', '1', '1578571213');
INSERT INTO `m15_package` VALUES ('3', '1', '李佳琪的大V定制', '/uploads/20200110\\5b742b1841f4187507a3df3de50fd543.jpg', '5000.00', '0', '  李佳琪的大V定制  ', '0', '0', '1578640881');
