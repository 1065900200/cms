--
-- MySQL database dump
-- Created by DbManage class, Power By yanue. 
-- http://yanue.net 
--
-- 主机: 120.27.95.231
-- 生成日期: 2018 年  12 月 05 日 15:06
-- MySQL版本: 5.5.53
-- PHP 版本: 5.5.38

--
-- 数据库: `psk`
--

-- -------------------------------------------------------

--
-- 表的结构psk_access
--

DROP TABLE IF EXISTS `psk_access`;
CREATE TABLE `psk_access` (
  `role_id` smallint(6) unsigned NOT NULL,
  `node_id` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`role_id`,`node_id`),
  KEY `groupId` (`role_id`),
  KEY `nodeId` (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_access
--

INSERT INTO `psk_access` VALUES('1','29','0','');
INSERT INTO `psk_access` VALUES('1','28','0','');
INSERT INTO `psk_access` VALUES('1','27','0','');
INSERT INTO `psk_access` VALUES('1','26','0','');
INSERT INTO `psk_access` VALUES('1','25','0','');
INSERT INTO `psk_access` VALUES('1','24','0','');
INSERT INTO `psk_access` VALUES('1','23','0','');
INSERT INTO `psk_access` VALUES('1','22','0','');
INSERT INTO `psk_access` VALUES('1','3','0','');
INSERT INTO `psk_access` VALUES('1','2','0','');
INSERT INTO `psk_access` VALUES('1','68','0','');
INSERT INTO `psk_access` VALUES('1','67','0','');
INSERT INTO `psk_access` VALUES('1','66','0','');
INSERT INTO `psk_access` VALUES('1','65','0','');
INSERT INTO `psk_access` VALUES('1','64','0','');
INSERT INTO `psk_access` VALUES('1','60','0','');
INSERT INTO `psk_access` VALUES('1','59','0','');
INSERT INTO `psk_access` VALUES('1','58','0','');
INSERT INTO `psk_access` VALUES('1','57','0','');
INSERT INTO `psk_access` VALUES('1','56','0','');
INSERT INTO `psk_access` VALUES('1','55','0','');
INSERT INTO `psk_access` VALUES('1','54','0','');
INSERT INTO `psk_access` VALUES('1','53','0','');
INSERT INTO `psk_access` VALUES('1','52','0','');
INSERT INTO `psk_access` VALUES('1','51','0','');
INSERT INTO `psk_access` VALUES('1','50','0','');
INSERT INTO `psk_access` VALUES('1','49','0','');
INSERT INTO `psk_access` VALUES('1','48','0','');
INSERT INTO `psk_access` VALUES('1','47','0','');
INSERT INTO `psk_access` VALUES('1','46','0','');
INSERT INTO `psk_access` VALUES('1','45','0','');
INSERT INTO `psk_access` VALUES('1','44','0','');
INSERT INTO `psk_access` VALUES('1','62','0','');
INSERT INTO `psk_access` VALUES('1','61','0','');
INSERT INTO `psk_access` VALUES('1','37','0','');
INSERT INTO `psk_access` VALUES('1','36','0','');
INSERT INTO `psk_access` VALUES('1','35','0','');
INSERT INTO `psk_access` VALUES('1','70','0','');
INSERT INTO `psk_access` VALUES('1','69','0','');
INSERT INTO `psk_access` VALUES('1','63','0','');
INSERT INTO `psk_access` VALUES('1','43','0','');
INSERT INTO `psk_access` VALUES('2','60','0','');
INSERT INTO `psk_access` VALUES('2','59','0','');
INSERT INTO `psk_access` VALUES('2','58','0','');
INSERT INTO `psk_access` VALUES('2','57','0','');
INSERT INTO `psk_access` VALUES('2','56','0','');
INSERT INTO `psk_access` VALUES('2','55','0','');
INSERT INTO `psk_access` VALUES('2','54','0','');
INSERT INTO `psk_access` VALUES('2','53','0','');
INSERT INTO `psk_access` VALUES('2','52','0','');
INSERT INTO `psk_access` VALUES('2','51','0','');
INSERT INTO `psk_access` VALUES('2','50','0','');
INSERT INTO `psk_access` VALUES('2','49','0','');
INSERT INTO `psk_access` VALUES('2','48','0','');
INSERT INTO `psk_access` VALUES('2','46','0','');
INSERT INTO `psk_access` VALUES('2','45','0','');
INSERT INTO `psk_access` VALUES('2','44','0','');
INSERT INTO `psk_access` VALUES('2','75','0','');
INSERT INTO `psk_access` VALUES('2','62','0','');
INSERT INTO `psk_access` VALUES('2','61','0','');
INSERT INTO `psk_access` VALUES('1','42','0','');
INSERT INTO `psk_access` VALUES('1','41','0','');
INSERT INTO `psk_access` VALUES('1','40','0','');
INSERT INTO `psk_access` VALUES('1','39','0','');
INSERT INTO `psk_access` VALUES('1','38','0','');
INSERT INTO `psk_access` VALUES('1','33','0','');
INSERT INTO `psk_access` VALUES('2','37','0','');
INSERT INTO `psk_access` VALUES('2','36','0','');
INSERT INTO `psk_access` VALUES('2','35','0','');
INSERT INTO `psk_access` VALUES('2','69','0','');
INSERT INTO `psk_access` VALUES('2','41','0','');
INSERT INTO `psk_access` VALUES('2','38','0','');
INSERT INTO `psk_access` VALUES('2','33','0','');
INSERT INTO `psk_access` VALUES('2','32','0','');
INSERT INTO `psk_access` VALUES('1','32','0','');
INSERT INTO `psk_access` VALUES('1','31','0','');
INSERT INTO `psk_access` VALUES('1','30','0','');
INSERT INTO `psk_access` VALUES('1','1','0','');
INSERT INTO `psk_access` VALUES('1','34','0','');
INSERT INTO `psk_access` VALUES('1','71','0','');
INSERT INTO `psk_access` VALUES('2','31','0','');
INSERT INTO `psk_access` VALUES('2','30','0','');
INSERT INTO `psk_access` VALUES('2','1','0','');
--
-- 表的结构psk_article
--

DROP TABLE IF EXISTS `psk_article`;
CREATE TABLE `psk_article` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `subtitle` varchar(255) DEFAULT NULL,
  `cid` int(11) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `imgs` text COMMENT '多图用|线分割',
  `url` varchar(255) DEFAULT NULL,
  `file` varchar(255) DEFAULT NULL,
  `note` text,
  `content` longtext,
  `content1` longtext,
  `content2` longtext,
  `author` varchar(255) DEFAULT NULL,
  `datetime` varchar(255) DEFAULT NULL,
  `views` int(255) DEFAULT '0',
  `source` varchar(255) DEFAULT NULL,
  `sort` int(11) DEFAULT '0',
  `seo_title` text,
  `seo_keywords` text,
  `seo_description` text,
  `ishome` tinyint(4) DEFAULT '0' COMMENT '首页 0 否 1是',
  `isvouch` tinyint(4) DEFAULT '0' COMMENT '推荐 0否 1是',
  `istop` tinyint(4) DEFAULT '0' COMMENT '置顶 0否 1是',
  `mid` int(11) DEFAULT NULL COMMENT '模块ID',
  `nid` tinyint(6) DEFAULT NULL COMMENT '栏目id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_article
--

--
-- 表的结构psk_book
--

DROP TABLE IF EXISTS `psk_book`;
CREATE TABLE `psk_book` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `company` varchar(50) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `qq` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `content` text,
  `address` varchar(255) DEFAULT NULL,
  `datetime` varchar(50) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `num` varchar(50) DEFAULT NULL,
  `age` varchar(255) DEFAULT NULL,
  `height` varchar(255) DEFAULT NULL,
  `weight` varchar(255) DEFAULT NULL,
  `rytime` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_book
--

--
-- 表的结构psk_cate
--

DROP TABLE IF EXISTS `psk_cate`;
CREATE TABLE `psk_cate` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `entitle` varchar(255) DEFAULT NULL,
  `pid` smallint(6) DEFAULT '0',
  `img` varchar(255) DEFAULT NULL,
  `note` text,
  `sort` smallint(6) DEFAULT '0',
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `content` text,
  `isshow` tinyint(4) DEFAULT '1',
  `nid` smallint(6) DEFAULT NULL COMMENT '栏目ID',
  `mid` smallint(6) DEFAULT NULL COMMENT '模块ID',
  `list_tpl` varchar(255) DEFAULT NULL COMMENT '列表模板',
  `msg_tpl` varchar(255) DEFAULT NULL COMMENT '内容模板',
  `url` varchar(255) DEFAULT NULL COMMENT '跳转url',
  `target` char(1) DEFAULT NULL COMMENT '是否新页面打开',
  `page` int(11) DEFAULT NULL,
  `wap_page` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_cate
--

--
-- 表的结构psk_link
--

DROP TABLE IF EXISTS `psk_link`;
CREATE TABLE `psk_link` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_link
--

--
-- 表的结构psk_module
--

DROP TABLE IF EXISTS `psk_module`;
CREATE TABLE `psk_module` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `tpl` varchar(255) DEFAULT NULL COMMENT '模板',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_module
--

INSERT INTO `psk_module` VALUES('1','单页','onepage');
INSERT INTO `psk_module` VALUES('2','文章','article');
INSERT INTO `psk_module` VALUES('3','产品','product');
INSERT INTO `psk_module` VALUES('4','下载','download');
INSERT INTO `psk_module` VALUES('5','视频','video');
INSERT INTO `psk_module` VALUES('6','广告','advert');
INSERT INTO `psk_module` VALUES('7','外链','advert');
--
-- 表的结构psk_nav
--

DROP TABLE IF EXISTS `psk_nav`;
CREATE TABLE `psk_nav` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) DEFAULT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `img1` varchar(255) DEFAULT NULL,
  `img` varchar(255) DEFAULT NULL,
  `entitle` varchar(255) DEFAULT NULL,
  `isshow` char(1) DEFAULT '1' COMMENT '是否显示',
  `showcate` tinyint(255) DEFAULT '0' COMMENT '是否显示分类',
  `sort` int(11) DEFAULT NULL,
  `msg_tpl` varchar(255) DEFAULT NULL,
  `list_tpl` varchar(255) DEFAULT NULL,
  `seo_title` varchar(255) DEFAULT NULL,
  `seo_keywords` varchar(255) DEFAULT NULL,
  `seo_description` varchar(255) DEFAULT NULL,
  `content` text,
  `isthumb` tinyint(4) DEFAULT '0' COMMENT '是否生成缩略图',
  `thumbwidth` varchar(50) DEFAULT NULL,
  `thumbheight` varchar(50) DEFAULT NULL,
  `imgtips` varchar(255) DEFAULT NULL,
  `mid` tinyint(6) DEFAULT NULL COMMENT '模块ID',
  `url` varchar(255) DEFAULT NULL COMMENT 'url链接',
  `target` char(1) DEFAULT NULL COMMENT '是否新页面打开',
  `page` int(11) DEFAULT NULL,
  `wap_page` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_nav
--

--
-- 表的结构psk_node
--

DROP TABLE IF EXISTS `psk_node`;
CREATE TABLE `psk_node` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `title` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '0',
  `remark` varchar(255) DEFAULT NULL,
  `sort` smallint(6) unsigned DEFAULT NULL,
  `pid` smallint(6) unsigned NOT NULL,
  `level` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `pid` (`pid`),
  KEY `status` (`status`),
  KEY `name` (`name`)
) ENGINE=MyISAM AUTO_INCREMENT=85 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_node
--

INSERT INTO `psk_node` VALUES('1','pskcms','后台管理','1','','1','0','1');
INSERT INTO `psk_node` VALUES('2','Rbac','权限管理','1','','1','1','2');
INSERT INTO `psk_node` VALUES('3','user','用户管理','1','','0','2','3');
INSERT INTO `psk_node` VALUES('29','delnode','删除节点','1','','0','2','3');
INSERT INTO `psk_node` VALUES('22','adduser','添加编辑用户','1','','0','2','3');
INSERT INTO `psk_node` VALUES('23','deluser','删除用户','1','','0','2','3');
INSERT INTO `psk_node` VALUES('24','role','角色管理','1','','0','2','3');
INSERT INTO `psk_node` VALUES('25','addrole','添加编辑角色','1','','0','2','3');
INSERT INTO `psk_node` VALUES('26','delrole','删除角色','1','','0','2','3');
INSERT INTO `psk_node` VALUES('27','node','节点管理','1','','0','2','3');
INSERT INTO `psk_node` VALUES('28','addnode','添加编辑节点','1','','0','2','3');
INSERT INTO `psk_node` VALUES('63','uploadset','文件上传设置','1','','0','30','3');
INSERT INTO `psk_node` VALUES('30','Site','网站设置','1','','0','1','2');
INSERT INTO `psk_node` VALUES('31','info','网站信息','1','','0','30','3');
INSERT INTO `psk_node` VALUES('32','contact','联系信息','1','','0','30','3');
INSERT INTO `psk_node` VALUES('33','email','邮箱配置','1','','0','30','3');
INSERT INTO `psk_node` VALUES('34','checkuser','检测用户名','1','','0','2','3');
INSERT INTO `psk_node` VALUES('35','Common','公共模块','1','','0','1','2');
INSERT INTO `psk_node` VALUES('36','uploadimg','图片上传显示','1','','0','35','3');
INSERT INTO `psk_node` VALUES('37','uploadfile','文件上传处理','1','','0','35','3');
INSERT INTO `psk_node` VALUES('38','image','图片管理','1','','0','30','3');
INSERT INTO `psk_node` VALUES('39','delimage','删除图片','1','','0','30','3');
INSERT INTO `psk_node` VALUES('40','delAllImage','批量删除图片','1','','0','30','3');
INSERT INTO `psk_node` VALUES('41','nav','导航管理','1','','0','30','3');
INSERT INTO `psk_node` VALUES('42','editnav','添加编辑导航','1','','0','30','3');
INSERT INTO `psk_node` VALUES('43','delnav','删除导航','1','','0','30','3');
INSERT INTO `psk_node` VALUES('44','Cate','分类管理','1','','0','1','2');
INSERT INTO `psk_node` VALUES('45','index','分类列表','1','','0','44','3');
INSERT INTO `psk_node` VALUES('46','add','添加编辑分类','1','','0','44','3');
INSERT INTO `psk_node` VALUES('47','del','删除分类','1','','0','44','3');
INSERT INTO `psk_node` VALUES('48','sorts','分类排序','1','','0','44','3');
INSERT INTO `psk_node` VALUES('49','Article','文章管理','1','','0','1','2');
INSERT INTO `psk_node` VALUES('50','index','文章列表','1','','0','49','3');
INSERT INTO `psk_node` VALUES('51','add','添加编辑文章','1','','0','49','3');
INSERT INTO `psk_node` VALUES('52','del','删除文章','1','','0','49','3');
INSERT INTO `psk_node` VALUES('53','delall','批量删除','1','','0','49','3');
INSERT INTO `psk_node` VALUES('54','sorts','文章排序','1','','0','49','3');
INSERT INTO `psk_node` VALUES('55','plsorts','批量排序','1','','0','49','3');
INSERT INTO `psk_node` VALUES('56','ishome','批量首页','1','','0','49','3');
INSERT INTO `psk_node` VALUES('57','isvouch','批量推荐','1','','0','49','3');
INSERT INTO `psk_node` VALUES('58','istop','批量置顶','1','','0','49','3');
INSERT INTO `psk_node` VALUES('59','movecate','批量移动','1','','0','49','3');
INSERT INTO `psk_node` VALUES('60','copyArticle','批量复制','1','','0','49','3');
INSERT INTO `psk_node` VALUES('61','doupload','文件图片上传处理','1','','0','35','3');
INSERT INTO `psk_node` VALUES('62','icon','图标管理','1','','0','35','3');
INSERT INTO `psk_node` VALUES('64','Database','数据库管理','1','','0','1','2');
INSERT INTO `psk_node` VALUES('65','backup','数据库备份','1','','0','64','3');
INSERT INTO `psk_node` VALUES('66','importsql','数据库还原','1','','0','64','3');
INSERT INTO `psk_node` VALUES('67','delsql','删除数据库备份','1','','0','64','3');
INSERT INTO `psk_node` VALUES('68','downloadsql','下载数据库备份','1','','0','64','3');
INSERT INTO `psk_node` VALUES('69','book','留言管理','1','','0','30','3');
INSERT INTO `psk_node` VALUES('70','delbook','删除留言','1','','0','30','3');
INSERT INTO `psk_node` VALUES('71','access','编辑权限','1','','0','2','3');
INSERT INTO `psk_node` VALUES('78','sitelink','网站内链','1','','0','76','3');
INSERT INTO `psk_node` VALUES('77','baiduurl','百度推送','1','','0','76','3');
INSERT INTO `psk_node` VALUES('76','Seo','网站优化','1','','0','1','2');
INSERT INTO `psk_node` VALUES('75','clearcache','清除缓存','1','','0','35','3');
INSERT INTO `psk_node` VALUES('79','delsitelink','删除内链','1','','0','76','3');
INSERT INTO `psk_node` VALUES('80','sitemap','站点地图','1','','0','76','3');
INSERT INTO `psk_node` VALUES('81','spider','蜘蛛统计','1','','0','76','3');
INSERT INTO `psk_node` VALUES('82','delspider','删除蜘蛛统计','1','','0','76','3');
INSERT INTO `psk_node` VALUES('83','link','友情连接','1','','0','76','3');
INSERT INTO `psk_node` VALUES('84','dellink','删除友情连接','1','','0','76','3');
--
-- 表的结构psk_role
--

DROP TABLE IF EXISTS `psk_role`;
CREATE TABLE `psk_role` (
  `id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `pid` smallint(6) DEFAULT NULL,
  `status` tinyint(1) unsigned DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_role
--

INSERT INTO `psk_role` VALUES('1','超级管理员','0','1','无须权限控制管理所有操作');
--
-- 表的结构psk_role_user
--

DROP TABLE IF EXISTS `psk_role_user`;
CREATE TABLE `psk_role_user` (
  `role_id` mediumint(9) unsigned NOT NULL DEFAULT '0',
  `user_id` char(32) NOT NULL DEFAULT '',
  PRIMARY KEY (`role_id`,`user_id`),
  KEY `group_id` (`role_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_role_user
--

--
-- 表的结构psk_site
--

DROP TABLE IF EXISTS `psk_site`;
CREATE TABLE `psk_site` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `webtitle` text,
  `weblogo` varchar(255) DEFAULT NULL,
  `weburl` varchar(255) DEFAULT NULL,
  `webkeywords` text,
  `webdescription` text,
  `webcopyright` text,
  `webstatus` tinyint(1) DEFAULT '1',
  `webzdts` tinyint(4) DEFAULT '1' COMMENT '主动推送连接到百度',
  `webzdtsurl` varchar(255) DEFAULT NULL,
  `webclosedesc` text,
  `contact_company` varchar(255) DEFAULT NULL,
  `contact_name` varchar(255) DEFAULT NULL,
  `contact_mobile` varchar(255) DEFAULT NULL,
  `contact_tel` varchar(255) DEFAULT NULL,
  `contact_400` varchar(255) DEFAULT NULL,
  `contact_fax` varchar(255) DEFAULT NULL,
  `contact_qq` varchar(255) DEFAULT NULL,
  `contact_email` varchar(255) DEFAULT NULL,
  `contact_address` varchar(255) DEFAULT NULL,
  `contact_ewm` varchar(255) DEFAULT NULL,
  `contact_latlng` varchar(255) DEFAULT NULL,
  `email_form_name` varchar(255) DEFAULT NULL COMMENT '发件人',
  `email_form` varchar(255) DEFAULT NULL COMMENT '邮箱地址',
  `email_host` varchar(255) DEFAULT NULL COMMENT 'smt服务器',
  `email_smtpsecure` varchar(255) DEFAULT NULL COMMENT '连接方式',
  `email_port` varchar(255) DEFAULT NULL COMMENT 'smpt服务器端口',
  `email_send_name` varchar(255) DEFAULT NULL COMMENT '发件箱帐号',
  `email_send_pass` varchar(255) DEFAULT NULL COMMENT '发件箱密码',
  `email_issend` tinyint(4) DEFAULT '1' COMMENT '是否发送邮件',
  `upload_size` varchar(255) DEFAULT NULL,
  `upload_ext` varchar(255) DEFAULT NULL,
  `search_page` varchar(255) DEFAULT NULL COMMENT '搜索页分页条数',
  `page` tinyint(1) DEFAULT NULL COMMENT 'Pc简化分页模式',
  `search_banner` varchar(255) DEFAULT NULL,
  `wap_page` tinyint(1) DEFAULT NULL COMMENT 'Wap简化分页模式',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_site
--

INSERT INTO `psk_site` VALUES('1','','','','','','','1','0','','','','','','','','','','','','','','','','','','','','','1','10485760','jpg,gif,png,jpeg,doc,docx,xls,xlsx,ppt,txt,zip,rar,gz,bz2,pdf,ios,7z,avi,mp4,swf,wmv,rm,rmvb,mkv,mp3,wma,wav','10','0','','0');
--
-- 表的结构psk_sitelink
--

DROP TABLE IF EXISTS `psk_sitelink`;
CREATE TABLE `psk_sitelink` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_sitelink
--

--
-- 表的结构psk_spider
--

DROP TABLE IF EXISTS `psk_spider`;
CREATE TABLE `psk_spider` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `oldurl` varchar(255) DEFAULT NULL,
  `ip` varchar(50) DEFAULT NULL,
  `datetime` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_spider
--

--
-- 表的结构psk_upload
--

DROP TABLE IF EXISTS `psk_upload`;
CREATE TABLE `psk_upload` (
  `id` bigint(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `thumb` varchar(255) DEFAULT NULL,
  `type` tinyint(4) DEFAULT '1' COMMENT '类型 1图片 2 文件',
  `ext` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_upload
--

--
-- 表的结构psk_user
--

DROP TABLE IF EXISTS `psk_user`;
CREATE TABLE `psk_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password` varchar(32) NOT NULL,
  `logintime` int(11) DEFAULT NULL,
  `loginip` varchar(30) DEFAULT NULL,
  `status` tinyint(1) DEFAULT NULL,
  `remark` text,
  `role` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- 转存表中的数据 psk_user
--

INSERT INTO `psk_user` VALUES('1','admin','f379eaf3c831b04de153469d1bec345e','0','','1','','1');
