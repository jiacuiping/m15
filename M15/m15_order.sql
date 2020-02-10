/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : M15

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-01-08 17:29:49
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `m15_order`
-- ----------------------------
DROP TABLE IF EXISTS `m15_order`;
CREATE TABLE `m15_order` (
  `order_id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '订单主键id',
  `order_user` int(11) NOT NULL COMMENT '下单用户',
  `order_sn` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '订单号',
  `order_type` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '订单类型 0-开通特权、1-购买积分、2-购买课程',
  `order_price` decimal(10,2) NOT NULL COMMENT '订单价格',
  `order_paytime` int(11) NOT NULL COMMENT '付款时间',
  `order_payprice` decimal(10,2) NOT NULL COMMENT '实际支付价格',
  `order_invoice` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票状态 0-未索要 1-已索要',
  `order_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0:已下单 10:已支付 -1:支付失败',
  `order_time` int(11) NOT NULL COMMENT '下单时间',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of m15_order
-- ----------------------------
INSERT INTO `m15_order` VALUES ('1', '1', '12321', '0', '123.00', '1578380912', '123.50', '0', '10', '1578380912');
INSERT INTO `m15_order` VALUES ('2', '1', '5432634', '1', '432.00', '1578380971', '432.33', '0', '10', '1578380971');
INSERT INTO `m15_order` VALUES ('3', '1', '534634554', '0', '100.00', '1578208171', '100.00', '0', '10', '1578208171');
