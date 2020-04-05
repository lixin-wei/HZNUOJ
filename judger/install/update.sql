-- ----------------------------
-- 2020/3/30 DATABASE update by lixun2015
-- ----------------------------
use jol;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
ALTER TABLE `contest` ADD `user_id` VARCHAR(48) NOT NULL DEFAULT 'admin' AFTER `password`;
ALTER TABLE `solution` CHANGE `pass_rate` `pass_rate` DECIMAL(3,2) UNSIGNED NOT NULL DEFAULT '0.00';
-- Dump completed on 2019-03-13 17:03:43
-- ----------------------------
-- Table structure for `class_list`
-- ----------------------------
DROP TABLE IF EXISTS `class_list`;

CREATE TABLE `class_list` (
  `class_name` varchar(100) NOT NULL,
  `enrollment_year` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
-- ----------------------------
-- Dumping data for table class_list
-- ----------------------------
LOCK TABLES `class_list` WRITE;
/*!40000 ALTER TABLE `class_list` DISABLE KEYS */;
INSERT INTO `class_list` VALUES ('??', '0');
/*!40000 ALTER TABLE `class_list` ENABLE KEYS */;
UNLOCK TABLES;

-- ----------------------------
-- Table structure for `reg_code`
-- ----------------------------
DROP TABLE IF EXISTS `reg_code`;
CREATE TABLE `reg_code` (
  `class_name` varchar(100) NOT NULL,
  `reg_code` varchar(100) NOT NULL,
  `remain_num` smallint(4) NOT NULL,
  PRIMARY KEY (`class_name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
-- ----------------------------
-- Dumping data for table reg_code
-- ----------------------------
LOCK TABLES `reg_code` WRITE;
/*!40000 ALTER TABLE `reg_code` DISABLE KEYS */;
INSERT INTO `reg_code` VALUES ('??', '', '0');
/*!40000 ALTER TABLE `reg_code` ENABLE KEYS */;
UNLOCK TABLES;