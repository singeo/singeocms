
-- -----------------------------
-- Table structure for `g_console_role`
-- -----------------------------
DROP TABLE IF EXISTS `g_console_role`;
CREATE TABLE `g_console_role` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '状态;0:禁用;1:正常',
  `role_name` varchar(20) NOT NULL DEFAULT '' COMMENT '角色名称',
  `rules` varchar(1000) DEFAULT NULL COMMENT '角色权限,以","分割每个权限',
  `sort` smallint(6) NOT NULL DEFAULT '0' COMMENT '排序',
  `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='角色表';

-- -----------------------------
-- Records of `g_console_role`
-- -----------------------------
INSERT INTO `g_console_role` VALUES ('1', '1', '管理员', '1,4,8,9,10,11,12,13,26,27,28,29,30,31,32,33', '50', '0', '1527577950');
INSERT INTO `g_console_role` VALUES ('2', '1', '测试', '2,3,4', '1', '1527128443', '1527577962');

-- -----------------------------
-- Table structure for `g_console_role_user`
-- -----------------------------
DROP TABLE IF EXISTS `g_console_role_user`;
CREATE TABLE `g_console_role_user` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '角色 id',
  `user_id` bigint(20) unsigned NOT NULL DEFAULT '0' COMMENT '用户id',
  PRIMARY KEY (`id`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='用户角色对应表';

-- -----------------------------
-- Records of `g_console_role_user`
-- -----------------------------
INSERT INTO `g_console_role_user` VALUES ('2', '1', '18');

-- -----------------------------
-- Table structure for `g_console_user`
-- -----------------------------
DROP TABLE IF EXISTS `g_console_user`;
CREATE TABLE `g_console_user` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(30) NOT NULL DEFAULT '' COMMENT '用户名',
  `user_pass` char(32) NOT NULL COMMENT '登录密码;',
  `user_pass_salt` char(6) NOT NULL COMMENT '用户密码加盐处理',
  `user_nickname` varchar(30) NOT NULL DEFAULT '' COMMENT '用户昵称',
  `user_email` varchar(50) NOT NULL DEFAULT '' COMMENT '邮箱',
  `mobile` varchar(11) NOT NULL DEFAULT '' COMMENT '用户手机号',
  `avatar` varchar(100) NOT NULL DEFAULT '' COMMENT '用户头像',
  `create_time` int(10) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_status` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '用户状态;0:禁用,1:正常',
  `login_times` smallint(6) DEFAULT '0' COMMENT '登录次数',
  `last_login_time` int(10) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `last_login_ip` varchar(15) NOT NULL COMMENT '最后登录ip',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_login` (`user_login`) USING BTREE,
  KEY `user_nickname` (`user_nickname`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8 COMMENT='后台用户表';

-- -----------------------------
-- Records of `g_console_user`
-- -----------------------------
INSERT INTO `g_console_user` VALUES ('1', 'master', '81281e7e666729f69d62134b814e11bd', 'R6Y8d2', '超级管理员', '123@123.com', '', '', '1524807887', '1', '3', '1528257287', '127.0.0.1');
INSERT INTO `g_console_user` VALUES ('18', 'test_01', '29338382855cf9d3ab6fb6e773eab437', 'CIinom', 'aaaa', '', '', '', '1527576831', '1', '0', '0', '');
