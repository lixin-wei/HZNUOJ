-- ----------------------------
-- 2020/3/30 DATABASE update by lixun2015
-- ----------------------------

ALTER TABLE `contest` ADD `user_id` VARCHAR(48) NOT NULL DEFAULT 'admin' AFTER `password`;
ALTER TABLE `solution` CHANGE `pass_rate` `pass_rate` DECIMAL(3,2) UNSIGNED NOT NULL DEFAULT '0.00';
-- ----------------------------
-- Table structure for `class_list`
-- ----------------------------
DROP TABLE IF EXISTS `class_list`;
CREATE TABLE `class_list` (
  `class_name` varchar(100) NOT NULL,
  `enrollment_year` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of class_list
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
-- Records of reg_code
-- ----------------------------
INSERT INTO `reg_code` VALUES ('其它', '', '0');

