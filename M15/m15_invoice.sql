/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50726
Source Host           : localhost:3306
Source Database       : M15

Target Server Type    : MYSQL
Target Server Version : 50726
File Encoding         : 65001

Date: 2020-01-09 14:46:05
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `m15_invoice`
-- ----------------------------
DROP TABLE IF EXISTS `m15_invoice`;
CREATE TABLE `m15_invoice` (
  `invoice_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '发票信息主键id',
  `invoice_user` int(11) NOT NULL COMMENT '关联用户表',
  `invoice_order` varchar(100) COLLATE utf8_unicode_ci NOT NULL COMMENT '发票的订单id',
  `invoice_price` decimal(10,2) NOT NULL COMMENT '发票金额',
  `invoice_look_up` varchar(200) COLLATE utf8_unicode_ci NOT NULL COMMENT '发票抬头',
  `invoice_bank_name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '开户银行',
  `invoice_bank_code` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '银行卡号',
  `invoice_address` varchar(300) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '注册地址',
  `invoice_mobile` varchar(20) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '公司注册电话',
  `invoice_taxpayer_certificate` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '纳税人证明',
  `invoice_buimg` varchar(200) COLLATE utf8_unicode_ci NOT NULL DEFAULT '' COMMENT '营业执照',
  `invoice_time` int(11) NOT NULL COMMENT '提交时间',
  `invoice_type` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票类型 0-电子普通发票 1-增值税专用发票',
  `invoice_receive_email` varchar(50) COLLATE utf8_unicode_ci DEFAULT '' COMMENT '收票人电子邮箱',
  `invoice_receive_mobile` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '收票人手机号',
  `invoice_receive_address` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '收票人地址',
  `invoice_receive_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '收件人姓名',
  `invoice_receive_qq` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '收件人QQ',
  `invoice_tax_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '企业纳税识别号',
  `invoice_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '发票状态 0：未审核 1：审核通过 -1：审核驳回',
  PRIMARY KEY (`invoice_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Records of m15_invoice
-- ----------------------------
INSERT INTO `m15_invoice` VALUES ('6', '1', '3', '100.00', '56456345', '', '', '', '', '', '', '1578551652', '1', '653636@qq.com', '18811112222', null, null, null, '543564365', '1');
