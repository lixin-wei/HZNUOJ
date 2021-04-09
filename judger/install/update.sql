-- ----------------------------
-- 2020/3/30 DATABASE update by lixun2015
-- ----------------------------
set names utf8;
use jol;
ALTER TABLE `contest` ADD COLUMN `user_id` VARCHAR(48) NOT NULL DEFAULT 'admin' AFTER `password`;
ALTER TABLE `contest` ADD COLUMN `isTop`  tinyint(1) NOT NULL DEFAULT 0 AFTER `practice`;
ALTER TABLE `solution` MODIFY COLUMN `pass_rate` DECIMAL(3,2) UNSIGNED NOT NULL DEFAULT '0.00';
ALTER TABLE `printer_code` MODIFY COLUMN `user_id` CHAR(48) NOT NULL;
ALTER TABLE `privilege` MODIFY COLUMN `user_id` CHAR(48) NOT NULL;
ALTER TABLE `solution` MODIFY COLUMN `user_id` CHAR(48) NOT NULL;
ALTER TABLE `problemset` MODIFY COLUMN `index` int(11) NOT NULL AUTO_INCREMENT FIRST ;
ALTER TABLE `problemset` ADD COLUMN `access_level` tinyint NOT NULL DEFAULT 0;
ALTER TABLE `hit_log` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `loginlog` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `online` MODIFY COLUMN `ip` varchar(46) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE `reply` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `solution` MODIFY COLUMN `ip` char(46) NOT NULL;
ALTER TABLE `team` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `users` MODIFY COLUMN `ip` varchar(46) NOT NULL DEFAULT '';
ALTER TABLE `users` ADD COLUMN `access_level` tinyint NOT NULL DEFAULT 0;
-- Dump completed on 2019-03-13 17:03:43
-- ----------------------------
-- Table structure for `class_list`
-- ----------------------------
CREATE TABLE `class_list` (
  `class_name` varchar(100) NOT NULL,
  `enrollment_year` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Dumping data for table class_list
-- ----------------------------
INSERT INTO `class_list` VALUES ('其它', '0');

-- ----------------------------
-- Table structure for `reg_code`
-- ----------------------------
CREATE TABLE `reg_code` (
  `class_name` varchar(100) NOT NULL,
  `reg_code` varchar(100) NOT NULL,
  `remain_num` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Dumping data for table reg_code
-- ----------------------------
INSERT INTO `reg_code` VALUES ('其它', '', '0');

-- ----------------------------
-- Table structure for `course`
-- ----------------------------
CREATE TABLE `course` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT '10000',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `isProblem` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- ----------------------------
-- Records of course
-- ----------------------------
INSERT INTO `course` VALUES ('1', '入门篇', '0', '0', '0');
INSERT INTO `course` VALUES ('2', '九阴真经', '1', '0', '0');
INSERT INTO `course` VALUES ('3', '九阳神功', '2', '0', '0');
INSERT INTO `course` VALUES ('4', '葵花宝典', '3', '0', '0');
INSERT INTO `course` VALUES ('5', '辟邪剑谱', '4', '0', '0');
INSERT INTO `course` VALUES ('6', '平台操作题', '0', '1', '0');
INSERT INTO `course` VALUES ('7', '输出题入门', '1', '1', '0');
INSERT INTO `course` VALUES ('8', '计算题入门', '2', '1', '0');
INSERT INTO `course` VALUES ('9', '分支结构入门', '3', '1', '0');
INSERT INTO `course` VALUES ('10', '循环结构入门', '4', '1', '0');
INSERT INTO `course` VALUES ('11', '1000', '0', '6', '1');

