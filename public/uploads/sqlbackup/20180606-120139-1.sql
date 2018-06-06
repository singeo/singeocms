-- -----------------------------
-- Think MySQL Data Transfer 
-- 
-- Host     : 127.0.0.1
-- Port     : 
-- Database : singeo
-- 
-- Part : #1
-- Date : 2018-06-06 12:01:39
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;


-- -----------------------------
-- Table structure for `g_advert`
-- -----------------------------
DROP TABLE IF EXISTS `g_advert`;
CREATE TABLE `g_advert` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `category_id` tinyint(3) DEFAULT NULL COMMENT '广告分类ID',
  `a_title` varchar(50) DEFAULT NULL COMMENT '广告名称',
  `a_desc` varchar(255) DEFAULT NULL COMMENT '广告描述',
  `a_pic` varchar(100) DEFAULT NULL COMMENT '广告图片',
  `sort` smallint(6) DEFAULT '50' COMMENT '排序',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态1正常，0失效',
  `add_time` int(10) DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`aid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告';

