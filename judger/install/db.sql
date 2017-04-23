set names utf8; 
create database jol;
use jol;

/*
Navicat MySQL Data Transfer

Source Server         : V_Ubuntu_16.04
Source Server Version : 50717
Source Host           : 192.168.180.153:3306
Source Database       : jol

Target Server Type    : MYSQL
Target Server Version : 50717
File Encoding         : 65001

Date: 2017-04-23 11:41:11
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for compileinfo
-- ----------------------------
DROP TABLE IF EXISTS `compileinfo`;
CREATE TABLE `compileinfo` (
  `solution_id` int(11) NOT NULL DEFAULT '0',
  `error` text,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for contest
-- ----------------------------
DROP TABLE IF EXISTS `contest`;
CREATE TABLE `contest` (
  `contest_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `description` text,
  `private` tinyint(4) NOT NULL DEFAULT '0',
  `langmask` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'bits for LANG to mask',
  `password` char(16) NOT NULL DEFAULT '',
  `user_limit` char(1) NOT NULL,
  `defunct_TA` char(1) NOT NULL,
  `open_source` char(1) NOT NULL,
  `lock_time` int(11) DEFAULT NULL,
  `unlock` tinyint(4) DEFAULT '0',
  `first_prize` int(11) DEFAULT '0',
  `second_prize` int(11) DEFAULT '0',
  `third_prize` int(11) DEFAULT '0',
  PRIMARY KEY (`contest_id`),
  KEY `contest_id` (`contest_id`,`defunct`,`private`,`defunct_TA`,`open_source`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1090 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for contest_excluded_user
-- ----------------------------
DROP TABLE IF EXISTS `contest_excluded_user`;
CREATE TABLE `contest_excluded_user` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `contest_id` int(11) DEFAULT NULL,
  `user_id` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`index`),
  KEY `contest_id` (`contest_id`,`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=244 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for contest_hznu_2016
-- ----------------------------
DROP TABLE IF EXISTS `contest_hznu_2016`;
CREATE TABLE `contest_hznu_2016` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `institute` varchar(255) CHARACTER SET utf8 NOT NULL,
  `stu_id` varchar(255) NOT NULL,
  `class` varchar(255) CHARACTER SET utf8 NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 NOT NULL,
  `register_time` datetime DEFAULT NULL,
  `anonymous` tinyint(4) DEFAULT '0',
  `phone` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=305 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for contest_hznu_2016_news
-- ----------------------------
DROP TABLE IF EXISTS `contest_hznu_2016_news`;
CREATE TABLE `contest_hznu_2016_news` (
  `index` int(255) NOT NULL AUTO_INCREMENT,
  `content` varchar(10000) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for contest_problem
-- ----------------------------
DROP TABLE IF EXISTS `contest_problem`;
CREATE TABLE `contest_problem` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL DEFAULT '0',
  `contest_id` int(11) NOT NULL,
  `title` char(200) NOT NULL DEFAULT '',
  `num` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`index`),
  KEY `Index_contest_id` (`contest_id`),
  KEY `contest_id` (`contest_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2725 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for custominput
-- ----------------------------
DROP TABLE IF EXISTS `custominput`;
CREATE TABLE `custominput` (
  `solution_id` int(11) NOT NULL DEFAULT '0',
  `input_text` text,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for faq_codes
-- ----------------------------
DROP TABLE IF EXISTS `faq_codes`;
CREATE TABLE `faq_codes` (
  `language` varchar(255) CHARACTER SET utf8 NOT NULL,
  `language_show` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `code` varchar(10000) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for faqs
-- ----------------------------
DROP TABLE IF EXISTS `faqs`;
CREATE TABLE `faqs` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for hit_log
-- ----------------------------
DROP TABLE IF EXISTS `hit_log`;
CREATE TABLE `hit_log` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(20) DEFAULT NULL,
  `path` text,
  `time` datetime DEFAULT NULL,
  `user_id` text,
  PRIMARY KEY (`index`),
  KEY `time` (`time`) USING BTREE,
  KEY `ip` (`ip`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1575686 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for loginlog
-- ----------------------------
DROP TABLE IF EXISTS `loginlog`;
CREATE TABLE `loginlog` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `password` varchar(40) DEFAULT NULL,
  `ip` varchar(100) DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`index`),
  KEY `user_time_index` (`user_id`,`time`)
) ENGINE=MyISAM AUTO_INCREMENT=123344 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for mail
-- ----------------------------
DROP TABLE IF EXISTS `mail`;
CREATE TABLE `mail` (
  `mail_id` int(11) NOT NULL AUTO_INCREMENT,
  `to_user` varchar(48) NOT NULL DEFAULT '' COMMENT 'user_id',
  `from_user` varchar(48) NOT NULL DEFAULT '' COMMENT 'user_id',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text,
  `new_mail` tinyint(1) NOT NULL DEFAULT '1',
  `reply` tinyint(4) DEFAULT '0',
  `in_date` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`mail_id`),
  KEY `uid` (`to_user`)
) ENGINE=MyISAM AUTO_INCREMENT=1077 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for message
-- ----------------------------
DROP TABLE IF EXISTS `message`;
CREATE TABLE `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL DEFAULT '0',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `thread_id` int(11) NOT NULL DEFAULT '0',
  `depth` int(11) NOT NULL DEFAULT '0',
  `orderNum` int(11) NOT NULL DEFAULT '0',
  `user_id` varchar(20) NOT NULL DEFAULT '',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text,
  `in_date` datetime DEFAULT NULL,
  `defunct` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1000 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for news
-- ----------------------------
DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `news_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(48) NOT NULL DEFAULT '' COMMENT 'user_id',
  `title` varchar(200) NOT NULL DEFAULT '',
  `content` text NOT NULL,
  `time` datetime NOT NULL,
  `importance` tinyint(4) NOT NULL DEFAULT '0',
  `defunct` char(1) NOT NULL DEFAULT 'N',
  PRIMARY KEY (`news_id`)
) ENGINE=MyISAM AUTO_INCREMENT=1022 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for online
-- ----------------------------
DROP TABLE IF EXISTS `online`;
CREATE TABLE `online` (
  `hash` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `ip` varchar(20) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `ua` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `refer` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lastmove` int(10) NOT NULL,
  `firsttime` int(10) DEFAULT NULL,
  `uri` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`hash`),
  UNIQUE KEY `hash` (`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- ----------------------------
-- Table structure for privilege
-- ----------------------------
DROP TABLE IF EXISTS `privilege`;
CREATE TABLE `privilege` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` char(20) NOT NULL DEFAULT '',
  `rightstr` char(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`index`)
) ENGINE=MyISAM AUTO_INCREMENT=125 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for privilege_distribution
-- ----------------------------
DROP TABLE IF EXISTS `privilege_distribution`;
CREATE TABLE `privilege_distribution` (
  `group_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `enter_admin_page` tinyint(4) DEFAULT NULL,
  `edit_hznu_problem` tinyint(4) DEFAULT NULL,
  `edit_c_problem` tinyint(4) DEFAULT NULL,
  `edit_ds_problem` tinyint(4) DEFAULT NULL,
  `rejudge` tinyint(4) DEFAULT NULL,
  `edit_news` tinyint(4) DEFAULT NULL,
  `edit_contest` tinyint(4) DEFAULT NULL,
  `download_ranklist` tinyint(4) DEFAULT NULL,
  `generate_team` tinyint(4) DEFAULT NULL,
  `edit_user_profile` tinyint(4) DEFAULT NULL,
  `edit_privilege_group` tinyint(4) DEFAULT NULL,
  `edit_privilege_distribution` tinyint(4) DEFAULT NULL,
  `inner_function` tinyint(4) DEFAULT NULL,
  `see_hidden_hznu_problem` tinyint(4) DEFAULT NULL,
  `see_hidden_c_problem` tinyint(4) DEFAULT NULL,
  `see_hidden_ds_problem` tinyint(4) DEFAULT NULL,
  `see_hidden_user_info` tinyint(4) DEFAULT NULL,
  `see_wa_info_out_of_contest` tinyint(4) DEFAULT NULL,
  `see_wa_info_in_contest` tinyint(4) DEFAULT NULL,
  `see_source_out_of_contest` tinyint(4) DEFAULT NULL,
  `see_source_in_contest` tinyint(4) DEFAULT NULL,
  `see_compare` tinyint(4) DEFAULT NULL,
  `upload_files` tinyint(4) DEFAULT NULL,
  `watch_solution_video` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`group_name`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for privilege_groups
-- ----------------------------
DROP TABLE IF EXISTS `privilege_groups`;
CREATE TABLE `privilege_groups` (
  `group_order` int(11) NOT NULL DEFAULT '0',
  `group_name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`group_order`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for problem
-- ----------------------------
DROP TABLE IF EXISTS `problem`;
CREATE TABLE `problem` (
  `problem_id` int(9) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '',
  `description` text,
  `input` text,
  `output` text,
  `sample_input` text,
  `sample_output` text,
  `spj` char(1) NOT NULL DEFAULT '0',
  `hint` text,
  `author` varchar(30) DEFAULT NULL,
  `source` varchar(100) DEFAULT NULL,
  `in_date` datetime DEFAULT NULL,
  `time_limit` int(11) NOT NULL DEFAULT '0',
  `memory_limit` int(11) NOT NULL DEFAULT '0',
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `accepted` int(11) DEFAULT '0',
  `submit` int(9) DEFAULT '0',
  `solved_user` int(9) DEFAULT '0',
  `submit_user` int(9) DEFAULT NULL,
  `score` decimal(6,2) unsigned DEFAULT '100.00',
  `tag1` varchar(250) DEFAULT NULL,
  `tag2` varchar(250) DEFAULT NULL,
  `tag3` varchar(250) DEFAULT NULL,
  `problemset` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`problem_id`),
  UNIQUE KEY `problem_id` (`problem_id`) USING BTREE,
  KEY `spj` (`problemset`,`defunct`,`spj`,`problem_id`) USING BTREE,
  KEY `score` (`score`,`accepted`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=2298 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for problem_samples
-- ----------------------------
DROP TABLE IF EXISTS `problem_samples`;
CREATE TABLE `problem_samples` (
  `problem_id` int(11) NOT NULL,
  `sample_id` int(11) NOT NULL DEFAULT '0',
  `input` text CHARACTER SET utf8,
  `output` text CHARACTER SET utf8,
  `show_after` int(11) DEFAULT '0',
  PRIMARY KEY (`problem_id`,`sample_id`),
  KEY `problem_id` (`problem_id`,`sample_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for problemset
-- ----------------------------
DROP TABLE IF EXISTS `problemset`;
CREATE TABLE `problemset` (
  `index` int(11) NOT NULL,
  `set_name` varchar(255) CHARACTER SET utf8 NOT NULL DEFAULT '',
  `set_name_show` varchar(255) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for reply
-- ----------------------------
DROP TABLE IF EXISTS `reply`;
CREATE TABLE `reply` (
  `rid` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` varchar(48) NOT NULL DEFAULT '' COMMENT 'user_id',
  `time` datetime NOT NULL,
  `content` text NOT NULL,
  `topic_id` int(11) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `ip` varchar(30) NOT NULL,
  PRIMARY KEY (`rid`),
  KEY `author_id` (`author_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=27 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Table structure for runtimeinfo
-- ----------------------------
DROP TABLE IF EXISTS `runtimeinfo`;
CREATE TABLE `runtimeinfo` (
  `solution_id` int(11) NOT NULL DEFAULT '0',
  `error` text,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for sim
-- ----------------------------
DROP TABLE IF EXISTS `sim`;
CREATE TABLE `sim` (
  `s_id` int(11) NOT NULL,
  `sim_s_id` int(11) DEFAULT NULL,
  `sim` int(11) DEFAULT NULL,
  PRIMARY KEY (`s_id`),
  KEY `Index_sim_id` (`sim_s_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for slide
-- ----------------------------
DROP TABLE IF EXISTS `slide`;
CREATE TABLE `slide` (
  `img_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `url` varchar(200) NOT NULL,
  `defunct` char(1) DEFAULT NULL,
  PRIMARY KEY (`img_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for solution
-- ----------------------------
DROP TABLE IF EXISTS `solution`;
CREATE TABLE `solution` (
  `solution_id` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(11) NOT NULL DEFAULT '0',
  `user_id` char(20) NOT NULL,
  `time` int(11) NOT NULL DEFAULT '0',
  `memory` int(11) NOT NULL DEFAULT '0',
  `in_date` datetime NOT NULL,
  `result` smallint(6) NOT NULL DEFAULT '0',
  `language` tinyint(4) NOT NULL DEFAULT '0',
  `ip` char(15) NOT NULL,
  `contest_id` int(11) DEFAULT NULL,
  `valid` tinyint(4) NOT NULL DEFAULT '1',
  `num` tinyint(4) NOT NULL DEFAULT '-1',
  `code_length` int(11) NOT NULL DEFAULT '0',
  `judgetime` datetime DEFAULT NULL,
  `pass_rate` decimal(2,2) unsigned NOT NULL DEFAULT '0.00',
  `judger` char(16) NOT NULL DEFAULT 'LOCAL',
  PRIMARY KEY (`solution_id`),
  KEY `pid` (`problem_id`) USING BTREE,
  KEY `res` (`result`) USING BTREE,
  KEY `in_date` (`in_date`) USING BTREE,
  KEY `uid` (`user_id`,`result`) USING BTREE,
  KEY `cid` (`contest_id`,`result`,`num`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=239851 DEFAULT CHARSET=utf8 ROW_FORMAT=FIXED;

-- ----------------------------
-- Table structure for solution_video_watch_log
-- ----------------------------
DROP TABLE IF EXISTS `solution_video_watch_log`;
CREATE TABLE `solution_video_watch_log` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `video_id` int(11) NOT NULL,
  `user_id` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`index`),
  KEY `video_id` (`video_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=682 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for source_code
-- ----------------------------
DROP TABLE IF EXISTS `source_code`;
CREATE TABLE `source_code` (
  `solution_id` int(11) NOT NULL,
  `source` text NOT NULL,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for source_code_user
-- ----------------------------
DROP TABLE IF EXISTS `source_code_user`;
CREATE TABLE `source_code_user` (
  `solution_id` int(11) NOT NULL,
  `source` text NOT NULL,
  PRIMARY KEY (`solution_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for tag
-- ----------------------------
DROP TABLE IF EXISTS `tag`;
CREATE TABLE `tag` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `problem_id` int(10) unsigned zerofill NOT NULL,
  `user_id` varchar(100) CHARACTER SET utf8 NOT NULL,
  `tag` varchar(100) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=231 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for team
-- ----------------------------
DROP TABLE IF EXISTS `team`;
CREATE TABLE `team` (
  `user_id` varchar(30) NOT NULL,
  `prefix` varchar(30) DEFAULT NULL,
  `NO` int(10) DEFAULT NULL,
  `password` varchar(32) NOT NULL,
  `nick` varchar(100) NOT NULL,
  `contest_id` int(13) NOT NULL,
  `stu_id` varchar(255) DEFAULT NULL,
  `institute` varchar(255) DEFAULT NULL,
  `class` varchar(30) DEFAULT NULL,
  `real_name` varchar(255) DEFAULT NULL,
  `seat` varchar(255) DEFAULT NULL,
  `school` varchar(100) DEFAULT NULL,
  `accesstime` datetime DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`contest_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for topic
-- ----------------------------
DROP TABLE IF EXISTS `topic`;
CREATE TABLE `topic` (
  `tid` int(11) NOT NULL AUTO_INCREMENT,
  `title` varbinary(60) NOT NULL,
  `status` int(2) NOT NULL DEFAULT '0',
  `top_level` int(2) NOT NULL DEFAULT '0',
  `cid` int(11) DEFAULT NULL,
  `pid` int(11) NOT NULL,
  `author_id` varchar(48) NOT NULL DEFAULT '' COMMENT 'user_id',
  PRIMARY KEY (`tid`),
  KEY `cid` (`cid`,`pid`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` varchar(48) NOT NULL DEFAULT '' COMMENT 'user_id',
  `stu_id` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `submit` int(11) DEFAULT '0',
  `solved` int(11) DEFAULT '0',
  `defunct` char(1) NOT NULL DEFAULT 'N',
  `ip` varchar(20) NOT NULL DEFAULT '',
  `accesstime` datetime DEFAULT NULL,
  `volume` int(11) NOT NULL DEFAULT '1',
  `volume_c` int(11) DEFAULT NULL,
  `language` int(11) NOT NULL DEFAULT '1',
  `password` varchar(32) DEFAULT NULL,
  `reg_time` datetime DEFAULT NULL,
  `real_name` varchar(100) DEFAULT NULL,
  `nick` varchar(100) NOT NULL DEFAULT '',
  `school` varchar(100) NOT NULL DEFAULT '',
  `class` varchar(10) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `strength` double(10,2) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `ZJU` int(9) unsigned DEFAULT NULL,
  `HDU` int(9) unsigned DEFAULT NULL,
  `PKU` int(9) unsigned DEFAULT NULL,
  `UVA` int(9) unsigned DEFAULT NULL,
  `CF` int(9) DEFAULT NULL,
  `like` int(9) DEFAULT '0',
  `dislike` int(9) DEFAULT '0',
  `tag` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for users_cache
-- ----------------------------
DROP TABLE IF EXISTS `users_cache`;
CREATE TABLE `users_cache` (
  `user_id` varchar(48) NOT NULL,
  `class` varchar(15) CHARACTER SET utf8 NOT NULL,
  `AC_day` int(10) unsigned zerofill DEFAULT NULL,
  `sub_day` int(10) unsigned zerofill DEFAULT NULL,
  `activity` int(10) unsigned zerofill DEFAULT NULL,
  `total_score` decimal(10,2) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- Table structure for users_cache_array
-- ----------------------------
DROP TABLE IF EXISTS `users_cache_array`;
CREATE TABLE `users_cache_array` (
  `index` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` varchar(48) NOT NULL,
  `type` varchar(15) NOT NULL,
  `week` int(9) unsigned zerofill DEFAULT NULL,
  `value_int` int(10) unsigned zerofill NOT NULL,
  `value_double` decimal(10,2) unsigned zerofill DEFAULT NULL,
  PRIMARY KEY (`index`)
) ENGINE=InnoDB AUTO_INCREMENT=3787 DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

-- ----------------------------
-- View structure for squid
-- ----------------------------
DROP VIEW IF EXISTS `squid`;
CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`%` SQL SECURITY DEFINER VIEW `squid` AS select `users`.`user_id` AS `user_id`,`users`.`password` AS `password`,`users`.`solved` AS `solved` from `users` where ((`users`.`solved` > pow(2,(minute(now()) / 8))) or `users`.`user_id` in (select `privilege`.`user_id` AS `user_id` from `privilege` where (`privilege`.`rightstr` = 'source_browser'))) ;

INSERT INTO `jol`.`privilege_distribution` (`group_name`, `enter_admin_page`, `edit_hznu_problem`, `edit_c_problem`, `edit_ds_problem`, `rejudge`, `edit_news`, `edit_contest`, `download_ranklist`, `generate_team`, `edit_user_profile`, `edit_privilege_group`, `edit_privilege_distribution`, `inner_function`, `see_hidden_hznu_problem`, `see_hidden_c_problem`, `see_hidden_ds_problem`, `see_hidden_user_info`, `see_wa_info_out_of_contest`, `see_wa_info_in_contest`, `see_source_out_of_contest`, `see_source_in_contest`, `see_compare`, `upload_files`, `watch_solution_video`) VALUES ('administrator', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `jol`.`privilege_distribution` (`group_name`, `enter_admin_page`, `edit_hznu_problem`, `edit_c_problem`, `edit_ds_problem`, `rejudge`, `edit_news`, `edit_contest`, `download_ranklist`, `generate_team`, `edit_user_profile`, `edit_privilege_group`, `edit_privilege_distribution`, `inner_function`, `see_hidden_hznu_problem`, `see_hidden_c_problem`, `see_hidden_ds_problem`, `see_hidden_user_info`, `see_wa_info_out_of_contest`, `see_wa_info_in_contest`, `see_source_out_of_contest`, `see_source_in_contest`, `see_compare`, `upload_files`, `watch_solution_video`) VALUES ('hznu_viewer', '1', '1', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '1', '0');
INSERT INTO `jol`.`privilege_distribution` (`group_name`, `enter_admin_page`, `edit_hznu_problem`, `edit_c_problem`, `edit_ds_problem`, `rejudge`, `edit_news`, `edit_contest`, `download_ranklist`, `generate_team`, `edit_user_profile`, `edit_privilege_group`, `edit_privilege_distribution`, `inner_function`, `see_hidden_hznu_problem`, `see_hidden_c_problem`, `see_hidden_ds_problem`, `see_hidden_user_info`, `see_wa_info_out_of_contest`, `see_wa_info_in_contest`, `see_source_out_of_contest`, `see_source_in_contest`, `see_compare`, `upload_files`, `watch_solution_video`) VALUES ('root', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `jol`.`privilege_distribution` (`group_name`, `enter_admin_page`, `edit_hznu_problem`, `edit_c_problem`, `edit_ds_problem`, `rejudge`, `edit_news`, `edit_contest`, `download_ranklist`, `generate_team`, `edit_user_profile`, `edit_privilege_group`, `edit_privilege_distribution`, `inner_function`, `see_hidden_hznu_problem`, `see_hidden_c_problem`, `see_hidden_ds_problem`, `see_hidden_user_info`, `see_wa_info_out_of_contest`, `see_wa_info_in_contest`, `see_source_out_of_contest`, `see_source_in_contest`, `see_compare`, `upload_files`, `watch_solution_video`) VALUES ('source_browser', '1', '0', '0', '0', '1', '0', '0', '1', '0', '0', '0', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '0', '0');
INSERT INTO `jol`.`privilege_distribution` (`group_name`, `enter_admin_page`, `edit_hznu_problem`, `edit_c_problem`, `edit_ds_problem`, `rejudge`, `edit_news`, `edit_contest`, `download_ranklist`, `generate_team`, `edit_user_profile`, `edit_privilege_group`, `edit_privilege_distribution`, `inner_function`, `see_hidden_hznu_problem`, `see_hidden_c_problem`, `see_hidden_ds_problem`, `see_hidden_user_info`, `see_wa_info_out_of_contest`, `see_wa_info_in_contest`, `see_source_out_of_contest`, `see_source_in_contest`, `see_compare`, `upload_files`, `watch_solution_video`) VALUES ('teacher', '1', '1', '1', '1', '1', '0', '1', '1', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1');
INSERT INTO `jol`.`privilege_distribution` (`group_name`, `enter_admin_page`, `edit_hznu_problem`, `edit_c_problem`, `edit_ds_problem`, `rejudge`, `edit_news`, `edit_contest`, `download_ranklist`, `generate_team`, `edit_user_profile`, `edit_privilege_group`, `edit_privilege_distribution`, `inner_function`, `see_hidden_hznu_problem`, `see_hidden_c_problem`, `see_hidden_ds_problem`, `see_hidden_user_info`, `see_wa_info_out_of_contest`, `see_wa_info_in_contest`, `see_source_out_of_contest`, `see_source_in_contest`, `see_compare`, `upload_files`, `watch_solution_video`) VALUES ('teacher_assistant', '1', '1', '1', '1', '1', '0', '1', '1', '0', '0', '0', '0', '0', '1', '1', '1', '1', '1', '1', '1', '1', '1', '1', '0');

INSERT INTO `jol`.`privilege_groups` (`group_order`, `group_name`) VALUES ('0', 'root');
INSERT INTO `jol`.`privilege_groups` (`group_order`, `group_name`) VALUES ('1', 'administrator');
INSERT INTO `jol`.`privilege_groups` (`group_order`, `group_name`) VALUES ('2', 'teacher');
INSERT INTO `jol`.`privilege_groups` (`group_order`, `group_name`) VALUES ('3', 'teacher_assistant');
INSERT INTO `jol`.`privilege_groups` (`group_order`, `group_name`) VALUES ('4', 'source_browser');
INSERT INTO `jol`.`privilege_groups` (`group_order`, `group_name`) VALUES ('5', 'hznu_viewer');


INSERT INTO `jol`.`users` (`user_id`, `stu_id`, `email`, `submit`, `solved`, `defunct`, `ip`, `accesstime`, `volume`, `volume_c`, `language`, `password`, `reg_time`, `real_name`, `nick`, `school`, `class`, `level`, `strength`, `color`, `ZJU`, `HDU`, `PKU`, `UVA`, `CF`, `like`, `dislike`, `tag`) VALUES ('admin', '', 'temp@temp.com', '0', '0', 'N', '127.0.0.1', '2010-10-07 15:01:41', '10', '2', '1', 'ecRO2wW24yXkwG+t7lLe7UV2/ss1MWM3', '2010-10-07 15:01:41', '', 'admin', '', '其它', '大斗师八星', '6145.19', '#0072ff', '2', '1', '1', '0', '0', '0', '0', NULL);

INSERT INTO `jol`.`privilege` ( `user_id`, `rightstr`) VALUES ( 'admin', 'administrator');

INSERT INTO `jol`.`problemset` (`set_name`, `set_name_show`) VALUES ('hznu', 'HZNU');
INSERT INTO `jol`.`problemset` (`set_name`, `set_name_show`) VALUES ('c', 'C Course');
INSERT INTO `jol`.`problemset` (`set_name`, `set_name_show`) VALUES ('ds', 'DS Course');

INSERT INTO `jol`.`problem` (`problem_id`, `title`, `description`, `input`, `output`, `sample_input`, `sample_output`, `spj`, `hint`, `author`, `source`, `in_date`, `time_limit`, `memory_limit`, `defunct`, `accepted`, `submit`, `solved_user`, `submit_user`, `score`, `tag1`, `tag2`, `tag3`, `problemset`) VALUES ('1000', 'Input-Output Lecture (0) for ACM Freshman', '<div>\r\n	<div>\r\n		<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\"> \r\n		<div>\r\n			给出两个整数a和b，计算a+b的值。\r\n		</div>\r\n</span></span> \r\n	</div>\r\n</div>', '<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\"> \r\n	<div>\r\n		输入有多组，每组占一行，包含两个整数a和b (-100&lt;=a,b&lt;=100) 。\r\n	</div>\r\n</span></span> \r\n</div>\r\n<div>\r\n</div>', '<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">对于每组输入，输出一行，即a+b的值。</span></span> \r\n</div>', '1 5\r\n10 20\r\n', '6\r\n30\r\n', '0', '<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">这是一道四处可见的A+B练习题。</span></span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">此处输入有多组，因此我们需要写一个循环来执行多次的a+b操作。但由于题目并未告诉你具体有多少组</span></span><span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">输入，所以还需要在代码中判断当前的输入是否为最后一组。在C语言中，scanf若读取到末尾则返回EOF</span></span><span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">（实际值为-1），在C++中，cin读取到末尾返回0。</span></span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">因此我们可以写出如下代码：</span></span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">C语言版：<br />\r\n</span> \r\n<pre class=\"prettyprint\"><code>#include &lt;stdio.h&gt;\r\nint main()\r\n{\r\n    int a,b;\r\n    while(scanf(\"%d %d\",&amp;a, &amp;b) != EOF)\r\n        printf(\"%d\\n\",a+b);\r\n    return 0;\r\n}</code></pre>\r\n<br />\r\n</span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">C++版：<br />\r\n</span> \r\n<pre class=\"prettyprint\"><code>#include &lt;iostream&gt;\r\nusing namespace std;\r\nint main()\r\n{\r\n    int a,b;\r\n    while(cin &gt;&gt; a &gt;&gt; b)\r\n        cout &lt;&lt; a+b &lt;&lt; endl;\r\n}</code></pre>\r\n<br />\r\n</span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">Java版：<br />\r\n</span> \r\n<pre class=\"prettyprint\"><code>import java.util.Scanner;\r\npublic class Main {\r\n public static void main(String[] args) {\r\n  Scanner in = new Scanner(System.in);\r\n  while (in.hasNextInt()) {\r\n   int a = in.nextInt();\r\n   int b = in.nextInt();\r\n   System.out.println(a + b);\r\n  }\r\n }\r\n}</code></pre>\r\n<br />\r\n</span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">Pascal(FPC)版：<br />\r\n</span> \r\n<pre class=\"prettyprint\"><code>program p1000(Input,Output); \r\nvar \r\n  a,b:Integer; \r\nbegin \r\n   while not eof(Input) do \r\n     begin \r\n       Readln(a,b); \r\n       Writeln(a+b); \r\n     end; \r\nend.</code></pre>\r\n<br />\r\n</span> \r\n</div>\r\n<div>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">Python版：<br />\r\n</span> \r\n<pre class=\"prettyprint\"><code>import sys\r\nfor line in sys.stdin:\r\n    a = line.split()\r\n    print int(a[0]) + int(a[1])</code></pre>\r\n</span> \r\n</div>\r\n<p>\r\n	<span style=\"font-size:medium;\"><span style=\"font-family:Arial;\">PHP版：<br />\r\n</span> </span> \r\n</p>\r\n<pre class=\"prettyprint\"><code>&lt;?php\r\nwhile (fscanf(STDIN, \"%d%d\", $a, $b) == 2) {\r\n    print ($a + $b) . \"\\n\";\r\n}\r\n?&gt;</code></pre>\r\n<br />\r\n<p>\r\n	<br />\r\n</p>\r\n<p>\r\n	<br />\r\n</p>', 'CHEN, Yupeng', '', '2016-12-02 21:58:56', '1', '32', 'N', '853', '1261', '706', '723', '10.00', '', '1000', 'fgdef', 'hznu');

INSERT INTO `jol`.`problem_samples` (`problem_id`, `sample_id`, `input`, `output`, `show_after`) VALUES ('1000', '0', '1 5\n10 20\n', '6\n30\n', '0');
INSERT INTO `jol`.`problem_samples` (`problem_id`, `sample_id`, `input`, `output`, `show_after`) VALUES ('1000', '1', '1 2\n', '3\n', '0');

INSERT INTO `jol`.`contest` (`contest_id`, `title`, `start_time`, `end_time`, `defunct`, `description`, `private`, `langmask`, `password`, `user_limit`, `defunct_TA`, `open_source`, `lock_time`, `unlock`, `first_prize`, `second_prize`, `third_prize`) VALUES ('1000', '比赛题目测试', '2015-03-19 22:00:00', '2016-03-23 23:00:00', 'N', '', '1', '0', '1234', 'N', 'N', 'N', NULL, '0', '0', '0', '0');

