-- ----------------------------
-- 2020/3/30 DATABASE update by lixun2015
-- ----------------------------
set names utf8;
use jol;
ALTER TABLE `contest` ADD COLUMN `user_id` VARCHAR(48) NOT NULL DEFAULT 'admin' AFTER `password`;
ALTER TABLE `solution` MODIFY COLUMN `pass_rate` DECIMAL(3,2) UNSIGNED NOT NULL DEFAULT '0.00';
ALTER TABLE `printer_code` MODIFY COLUMN `user_id` CHAR(48) NOT NULL;
ALTER TABLE `privilege` MODIFY COLUMN `user_id` CHAR(48) NOT NULL;
ALTER TABLE `solution` MODIFY COLUMN `user_id` CHAR(48) NOT NULL;
ALTER TABLE `problemset` MODIFY COLUMN `index` int(11) NOT NULL AUTO_INCREMENT FIRST ;
ALTER TABLE `hit_log` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `loginlog` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `online` MODIFY COLUMN `ip` varchar(46) CHARACTER SET utf8 NOT NULL DEFAULT '';
ALTER TABLE `reply` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `solution` MODIFY COLUMN `ip` char(46) NOT NULL;
ALTER TABLE `team` MODIFY COLUMN `ip` varchar(46) DEFAULT NULL;
ALTER TABLE `users` MODIFY COLUMN `ip` varchar(46) NOT NULL DEFAULT '';
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
