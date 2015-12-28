

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `account`
-- ----------------------------
DROP TABLE IF EXISTS `account`;
CREATE TABLE `account` (
  `ACCOUNT_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '账户编号',
  `USER_ID` varchar(50) NOT NULL COMMENT '用户编号 : 外部平台的用户编号，用于映射BOSS内账户编号',
  `BALANCE` bigint(20) unsigned NOT NULL COMMENT '账户余额 : 单位人民币分',
  `STATUS` int(11) NOT NULL DEFAULT '1' COMMENT '账户状态',
  PRIMARY KEY (`ACCOUNT_ID`),
  UNIQUE KEY `USER_ID` (`USER_ID`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='帐户表';

-- ----------------------------
-- Records of account
-- ----------------------------
INSERT INTO `account` VALUES ('1', '123456', '18840', '1');

-- ----------------------------
-- Table structure for `cfg_platform`
-- ----------------------------
DROP TABLE IF EXISTS `cfg_platform`;
CREATE TABLE `cfg_platform` (
  `ID` int(10) unsigned NOT NULL COMMENT '平台编号',
  `NAME` varchar(50) NOT NULL COMMENT '平台名称',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='交易平台配置表';

-- ----------------------------
-- Records of cfg_platform
-- ----------------------------
INSERT INTO `cfg_platform` VALUES ('1', '默认第一游戏平台');
INSERT INTO `cfg_platform` VALUES ('2', '默认第二游戏平台');

-- ----------------------------
-- Table structure for `cfg_recharge_way`
-- ----------------------------
DROP TABLE IF EXISTS `cfg_recharge_way`;
CREATE TABLE `cfg_recharge_way` (
  `ID` int(10) unsigned NOT NULL COMMENT '通道编号',
  `NAME` varchar(50) NOT NULL COMMENT '通道名称',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='充值通道配置表';

-- ----------------------------
-- Records of cfg_recharge_way
-- ----------------------------
INSERT INTO `cfg_recharge_way` VALUES ('1', '默认第一支付通道');
INSERT INTO `cfg_recharge_way` VALUES ('2', '默认第二支付通道');

-- ----------------------------
-- Table structure for `product`
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product` (
  `PRODUCT_CODE` char(6) NOT NULL COMMENT '产品代码 : 6位数字或字母',
  `PRODUCT_NAME` varchar(50) NOT NULL COMMENT '产品名称',
  `DESCRIPTION` varchar(200) DEFAULT NULL COMMENT '产品描述',
  `PRICE` int(10) unsigned NOT NULL COMMENT '产品价格 : 单位人民币分',
  `PRICE_UNIT` int(10) unsigned NOT NULL COMMENT '价格单位 : 1：次，2：天；3：周，4：月，5：年',
  `STATUS` int(10) unsigned NOT NULL DEFAULT '1' COMMENT '产品状态 : 0：无效，1：有效',
  PRIMARY KEY (`PRODUCT_CODE`),
  KEY `PRICE_UNIT` (`PRICE_UNIT`),
  KEY `STATUS` (`STATUS`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='产品定义表';

-- ----------------------------
-- Records of product
-- ----------------------------
INSERT INTO `product` VALUES ('100001', '测试用按次产品', '产品描述', '20', '1', '1');
INSERT INTO `product` VALUES ('100002', '测试用包天产品', '产品描述', '6', '2', '1');
INSERT INTO `product` VALUES ('100003', '测试包周产品', '产品描述', '5', '3', '1');
INSERT INTO `product` VALUES ('100004', '测试包月产品', '产品描述', '8', '4', '1');
INSERT INTO `product` VALUES ('100005', '测试包年产品', '产品描述', '50', '5', '1');

-- ----------------------------
-- Table structure for `recharge_history`
-- ----------------------------
DROP TABLE IF EXISTS `recharge_history`;
CREATE TABLE `recharge_history` (
  `REC_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录编号',
  `ACCOUNT_ID` bigint(20) unsigned NOT NULL COMMENT '账户编号',
  `AMOUNT` int(10) unsigned NOT NULL COMMENT '充值金额 : 单位人民币分。',
  `TIME` datetime NOT NULL COMMENT '交易时间',
  `RECHARGE_WAY_ID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '充值通道',
  PRIMARY KEY (`REC_ID`),
  KEY `RECHARGE_WAY_ID` (`RECHARGE_WAY_ID`),
  KEY `ACCOUNT_ID` (`ACCOUNT_ID`),
  CONSTRAINT `recharge_history_ibfk_1` FOREIGN KEY (`ACCOUNT_ID`) REFERENCES `account` (`ACCOUNT_ID`) ON UPDATE CASCADE,
  CONSTRAINT `recharge_history_ibfk_2` FOREIGN KEY (`RECHARGE_WAY_ID`) REFERENCES `cfg_recharge_way` (`ID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=270 DEFAULT CHARSET=utf8 COMMENT='账户充值记录表';


-- ----------------------------
-- Table structure for `service_time`
-- ----------------------------
DROP TABLE IF EXISTS `service_time`;
CREATE TABLE `service_time` (
  `REC_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录编号',
  `ACCOUNT_ID` bigint(20) unsigned NOT NULL COMMENT '账户编号',
  `PRODUCT_CODE` char(6) NOT NULL COMMENT '产品代码 : 6位数字或字母，仅记录时间类产品。',
  `EXPIRY_DATE` datetime DEFAULT NULL COMMENT '产品过期时间 : 产品过期时间=产品过期时间+交易数量*价格单位(天周月年)',
  PRIMARY KEY (`REC_ID`),
  KEY `ACCOUNT_ID` (`ACCOUNT_ID`),
  KEY `PRODUCT_CODE` (`PRODUCT_CODE`),
  CONSTRAINT `service_time_ibfk_1` FOREIGN KEY (`ACCOUNT_ID`) REFERENCES `account` (`ACCOUNT_ID`) ON UPDATE CASCADE,
  CONSTRAINT `service_time_ibfk_2` FOREIGN KEY (`PRODUCT_CODE`) REFERENCES `product` (`PRODUCT_CODE`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='服务时间表 : 时间类产品的服务时间记录表，用于服务有效期查询。';


-- ----------------------------
-- Table structure for `transaction_history`
-- ----------------------------
DROP TABLE IF EXISTS `transaction_history`;
CREATE TABLE `transaction_history` (
  `REC_ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT COMMENT '记录编号',
  `ACCOUNT_ID` bigint(20) unsigned NOT NULL COMMENT '账户编号',
  `PRODUCT_CODE` char(6) NOT NULL COMMENT '产品代码 : 6位数字或字母',
  `PRODUCT_NAME` varchar(50) NOT NULL COMMENT '产品名称 : 冗余字段，用于历史备查。',
  `ORDER_COUNT` int(10) unsigned NOT NULL COMMENT '交易数量',
  `ORDER_PRICE` bigint(20) unsigned NOT NULL COMMENT '交易单价 : 冗余字段，用于历史备查。',
  `ORDER_PRICE_UNIT` int(10) unsigned NOT NULL COMMENT '交易价格单位',
  `ORDER_AMOUNT` int(11) NOT NULL COMMENT '交易总额 : 单位人民币分。',
  `TIME` datetime NOT NULL COMMENT '交易时间',
  `PLATFORM_ID` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '交易发起平台',
  PRIMARY KEY (`REC_ID`),
  KEY `PLATFORM_ID` (`PLATFORM_ID`),
  KEY `ACCOUNT_ID` (`ACCOUNT_ID`),
  KEY `PRODUCT_CODE` (`PRODUCT_CODE`),
  CONSTRAINT `transaction_history_ibfk_1` FOREIGN KEY (`ACCOUNT_ID`) REFERENCES `account` (`ACCOUNT_ID`) ON UPDATE CASCADE,
  CONSTRAINT `transaction_history_ibfk_2` FOREIGN KEY (`PLATFORM_ID`) REFERENCES `cfg_platform` (`ID`) ON UPDATE CASCADE,
  CONSTRAINT `transaction_history_ibfk_3` FOREIGN KEY (`PRODUCT_CODE`) REFERENCES `product` (`PRODUCT_CODE`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=140 DEFAULT CHARSET=utf8 COMMENT='交易记录表';
