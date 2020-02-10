/*
Navicat MySQL Data Transfer

Source Server         : m15
Source Server Version : 50562
Source Host           : 47.98.164.34:3306
Source Database       : M15

Target Server Type    : MYSQL
Target Server Version : 50562
File Encoding         : 65001

Date: 2020-01-08 16:00:29
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `m15_user_type`
-- ----------------------------
DROP TABLE IF EXISTS `m15_user_type`;
CREATE TABLE `m15_user_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(20) NOT NULL,
  `type_is_cert` tinyint(4) NOT NULL DEFAULT '0' COMMENT '是否需要认证',
  `type_desc` varchar(500) NOT NULL COMMENT '身份描述',
  `type_status` tinyint(4) NOT NULL COMMENT '类型状态',
  `type_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of m15_user_type
-- ----------------------------
INSERT INTO `m15_user_type` VALUES ('1', '用户', '0', '普通用户，有基本权限，MTD、KOL、数据监测、运营课堂', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('2', '达人', '0', '绑定抖音号的用户，绑定后可用任务市场', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('3', '广告商', '1', '认证广告商的用户，可以在任务市场发布任务', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('4', 'MCN', '1', '认证MCN机构的用户，可以管理旗下KOL，可查看KOL汇总的数据', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('5', '团长', '1', '认证团长的用户，审核自己广告商的任务，负责任务和KOL之间的对接工作', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('6', '讲师', '1', '认证讲师的用户，可以在运营课堂发布教学资料', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('7', '拍客', '1', '认证拍客的用户，可以在任务市场接素材类任务', '1', '1578448963');
INSERT INTO `m15_user_type` VALUES ('8', 'MCN+', '1', '认证MCN和团长的用户，同时拥有两者权限', '1', '1578448963');
