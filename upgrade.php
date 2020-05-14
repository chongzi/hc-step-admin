<?php
//升级数据表
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_activity` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `entryfee` varchar(255) DEFAULT NULL,
  `displayorder` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_activity','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_activity')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_activity','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_activity')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activity','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_activity')." ADD   `step` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activity','entryfee')) {pdo_query("ALTER TABLE ".tablename('hcstep_activity')." ADD   `entryfee` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activity','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_activity')." ADD   `displayorder` varchar(255) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_activitylog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `aid` int(11) DEFAULT NULL COMMENT '活动id',
  `timestamp` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `entryfee` varchar(255) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '0未达标1已达标，未发奖2已达标，已发奖',
  `jiangjin` varchar(255) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`,`aid`,`time`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_activitylog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_activitylog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activitylog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activitylog','aid')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `aid` int(11) DEFAULT NULL COMMENT '活动id'");}
if(!pdo_fieldexists('hcstep_activitylog','timestamp')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `timestamp` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activitylog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activitylog','entryfee')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `entryfee` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activitylog','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `step` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_activitylog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `status` int(11) NOT NULL COMMENT '0未达标1已达标，未发奖2已达标，已发奖'");}
if(!pdo_fieldexists('hcstep_activitylog','jiangjin')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   `jiangjin` varchar(255) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_activitylog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_activitylog')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_adv` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `jump` int(11) NOT NULL COMMENT '跳转方式 0 不跳转 1 小程序',
  `xcxpath` varchar(255) NOT NULL,
  `xcxappid` varchar(255) NOT NULL,
  `h5` varchar(255) NOT NULL,
  `tippic` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_adv','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_adv','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `uniacid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_adv','advname')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `advname` varchar(50) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_adv','thumb')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `thumb` varchar(255) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_adv','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `displayorder` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_adv','enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `enabled` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_adv','jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `jump` int(11) NOT NULL COMMENT '跳转方式 0 不跳转 1 小程序'");}
if(!pdo_fieldexists('hcstep_adv','xcxpath')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `xcxpath` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_adv','xcxappid')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `xcxappid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_adv','h5')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `h5` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_adv','tippic')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   `tippic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_adv','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('hcstep_adv','idx_uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   KEY `idx_uniacid` (`uniacid`)");}
if(!pdo_fieldexists('hcstep_adv','idx_enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_adv')." ADD   KEY `idx_enabled` (`enabled`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_awards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `goods_name` varchar(255) DEFAULT NULL,
  `main_img` varchar(255) DEFAULT NULL,
  `goods_img` varchar(2555) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `inventory` varchar(255) DEFAULT NULL,
  `express` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1 上架 2 下架',
  `rate` varchar(10) NOT NULL COMMENT '中奖率',
  `awards_type` int(11) NOT NULL DEFAULT '1' COMMENT '1真实商品2步数币3红包',
  `awards_coin` varchar(10) NOT NULL,
  `awards_money` decimal(10,2) NOT NULL,
  `sort` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_awards','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_awards','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','goods_name')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `goods_name` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','main_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `main_img` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','goods_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `goods_img` varchar(2555) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','price')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `price` decimal(10,2) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','inventory')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `inventory` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','express')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `express` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_awards','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `status` int(11) DEFAULT NULL COMMENT '1 上架 2 下架'");}
if(!pdo_fieldexists('hcstep_awards','rate')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `rate` varchar(10) NOT NULL COMMENT '中奖率'");}
if(!pdo_fieldexists('hcstep_awards','awards_type')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `awards_type` int(11) NOT NULL DEFAULT '1' COMMENT '1真实商品2步数币3红包'");}
if(!pdo_fieldexists('hcstep_awards','awards_coin')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `awards_coin` varchar(10) NOT NULL");}
if(!pdo_fieldexists('hcstep_awards','awards_money')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `awards_money` decimal(10,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_awards','sort')) {pdo_query("ALTER TABLE ".tablename('hcstep_awards')." ADD   `sort` int(11) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_bushulog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `uniacid` int(11) DEFAULT NULL,
  `bushu` varchar(255) DEFAULT NULL,
  `money` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `timestamp` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`uniacid`,`user_id`,`time`)
) ENGINE=MyISAM AUTO_INCREMENT=12185 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_bushulog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_bushulog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_bushulog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_bushulog','bushu')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   `bushu` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_bushulog','money')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   `money` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_bushulog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   `time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_bushulog','timestamp')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   `timestamp` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_bushulog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_bushulog')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '评论人',
  `dt_id` int(11) DEFAULT NULL COMMENT '评论的哪条动态',
  `content` varchar(1000) DEFAULT NULL COMMENT '评论的内容',
  `time` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`uniacid`,`user_id`,`dt_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_comment','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_comment','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_comment','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD   `user_id` int(11) DEFAULT NULL COMMENT '评论人'");}
if(!pdo_fieldexists('hcstep_comment','dt_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD   `dt_id` int(11) DEFAULT NULL COMMENT '评论的哪条动态'");}
if(!pdo_fieldexists('hcstep_comment','content')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD   `content` varchar(1000) DEFAULT NULL COMMENT '评论的内容'");}
if(!pdo_fieldexists('hcstep_comment','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD   `time` varchar(50) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_comment','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_comment')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_dt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` varchar(2555) DEFAULT NULL COMMENT '文字内容',
  `position` varchar(255) NOT NULL,
  `content_img` varchar(2555) DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL COMMENT '评论id集合',
  `zan` varchar(11) DEFAULT NULL COMMENT '点赞数',
  `time` varchar(20) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL COMMENT '话题id',
  `status` int(11) NOT NULL,
  `latitude` varchar(50) NOT NULL,
  `longitude` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_dt','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_dt','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_dt','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_dt','content')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `content` varchar(2555) DEFAULT NULL COMMENT '文字内容'");}
if(!pdo_fieldexists('hcstep_dt','position')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `position` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_dt','content_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `content_img` varchar(2555) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_dt','comment')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `comment` varchar(1000) DEFAULT NULL COMMENT '评论id集合'");}
if(!pdo_fieldexists('hcstep_dt','zan')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `zan` varchar(11) DEFAULT NULL COMMENT '点赞数'");}
if(!pdo_fieldexists('hcstep_dt','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `time` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_dt','topic_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `topic_id` int(11) DEFAULT NULL COMMENT '话题id'");}
if(!pdo_fieldexists('hcstep_dt','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `status` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_dt','latitude')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `latitude` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_dt','longitude')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   `longitude` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_dt','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('hcstep_dt','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   KEY `user_id` (`user_id`)");}
if(!pdo_fieldexists('hcstep_dt','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_dt')." ADD   KEY `uniacid` (`uniacid`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_follow` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL COMMENT '关注人',
  `follow_id` int(11) DEFAULT NULL COMMENT '被关注人',
  `time` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniacid` (`uniacid`,`user_id`,`follow_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_follow','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_follow')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_follow','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_follow')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_follow','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_follow')." ADD   `user_id` int(11) DEFAULT NULL COMMENT '关注人'");}
if(!pdo_fieldexists('hcstep_follow','follow_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_follow')." ADD   `follow_id` int(11) DEFAULT NULL COMMENT '被关注人'");}
if(!pdo_fieldexists('hcstep_follow','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_follow')." ADD   `time` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_follow','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_follow')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_formid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL,
  `formid` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=51 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_formid','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_formid')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_formid','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_formid')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_formid','formid')) {pdo_query("ALTER TABLE ".tablename('hcstep_formid')." ADD   `formid` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_formid','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_formid')." ADD   `status` int(11) NOT NULL DEFAULT '0'");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_fourhblog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `son_id` int(11) DEFAULT NULL,
  `hbmoney` decimal(10,2) DEFAULT NULL,
  `time` varchar(50) DEFAULT NULL,
  `daytime` varchar(50) DEFAULT NULL,
  `type` int(11) NOT NULL COMMENT '1大红包2小红包',
  `status` int(11) NOT NULL DEFAULT '0' COMMENT '0待解锁1待开启2已开启',
  PRIMARY KEY (`id`),
  KEY `user_id` (`uniacid`,`type`,`status`,`daytime`,`user_id`,`son_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_fourhblog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_fourhblog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_fourhblog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_fourhblog','son_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `son_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_fourhblog','hbmoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `hbmoney` decimal(10,2) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_fourhblog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `time` varchar(50) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_fourhblog','daytime')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `daytime` varchar(50) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_fourhblog','type')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `type` int(11) NOT NULL COMMENT '1大红包2小红包'");}
if(!pdo_fieldexists('hcstep_fourhblog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   `status` int(11) NOT NULL DEFAULT '0' COMMENT '0待解锁1待开启2已开启'");}
if(!pdo_fieldexists('hcstep_fourhblog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_fourhblog')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_goods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `goods_name` varchar(255) DEFAULT NULL,
  `main_img` varchar(255) DEFAULT NULL,
  `goods_img` varchar(2555) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `inventory` varchar(255) DEFAULT NULL,
  `express` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1 上架 2 下架',
  `displayorder` int(11) NOT NULL,
  `goodsinfo` varchar(9999) NOT NULL,
  `paytype` int(11) NOT NULL COMMENT '支付方式',
  `price2` decimal(10,2) NOT NULL COMMENT '步数加钱 步数币',
  `rmb` varchar(255) NOT NULL COMMENT '人民币',
  `maxrmb` varchar(255) NOT NULL COMMENT '人民币原价',
  `selltype` int(11) NOT NULL COMMENT '0普通1核销',
  `shop_id` int(11) NOT NULL,
  `minpeople` varchar(20) NOT NULL COMMENT '邀请人数',
  `maxbuy` varchar(20) NOT NULL DEFAULT '0',
  `indexsort_id` int(11) NOT NULL,
  `sort_id` int(11) NOT NULL,
  `is_hongbao` int(11) NOT NULL,
  `maxhongbao` varchar(20) NOT NULL,
  `validity` varchar(20) NOT NULL DEFAULT '0' COMMENT '核销码有效期',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_goods','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_goods','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','goods_name')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `goods_name` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','main_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `main_img` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','goods_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `goods_img` varchar(2555) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','price')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `price` decimal(10,2) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','inventory')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `inventory` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','express')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `express` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_goods','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `status` int(11) DEFAULT NULL COMMENT '1 上架 2 下架'");}
if(!pdo_fieldexists('hcstep_goods','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `displayorder` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','goodsinfo')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `goodsinfo` varchar(9999) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','paytype')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `paytype` int(11) NOT NULL COMMENT '支付方式'");}
if(!pdo_fieldexists('hcstep_goods','price2')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `price2` decimal(10,2) NOT NULL COMMENT '步数加钱 步数币'");}
if(!pdo_fieldexists('hcstep_goods','rmb')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `rmb` varchar(255) NOT NULL COMMENT '人民币'");}
if(!pdo_fieldexists('hcstep_goods','maxrmb')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `maxrmb` varchar(255) NOT NULL COMMENT '人民币原价'");}
if(!pdo_fieldexists('hcstep_goods','selltype')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `selltype` int(11) NOT NULL COMMENT '0普通1核销'");}
if(!pdo_fieldexists('hcstep_goods','shop_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `shop_id` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','minpeople')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `minpeople` varchar(20) NOT NULL COMMENT '邀请人数'");}
if(!pdo_fieldexists('hcstep_goods','maxbuy')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `maxbuy` varchar(20) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_goods','indexsort_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `indexsort_id` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','sort_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `sort_id` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','is_hongbao')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `is_hongbao` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','maxhongbao')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `maxhongbao` varchar(20) NOT NULL");}
if(!pdo_fieldexists('hcstep_goods','validity')) {pdo_query("ALTER TABLE ".tablename('hcstep_goods')." ADD   `validity` varchar(20) NOT NULL DEFAULT '0' COMMENT '核销码有效期'");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_guanzhulog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_guanzhulog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_guanzhulog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_guanzhulog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_guanzhulog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_guanzhulog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_guanzhulog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_guanzhulog','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_guanzhulog')." ADD   `step` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_guanzhulog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_guanzhulog')." ADD   `time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_guanzhulog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_guanzhulog')." ADD   `status` int(11) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_hbwith` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `money` decimal(10,2) DEFAULT NULL,
  `add_time` varchar(255) DEFAULT NULL,
  `pay_time` varchar(255) DEFAULT NULL,
  `partner_trade_no` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT '0',
  `nick_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_hbwith','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_hbwith','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hbwith','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hbwith','money')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `money` decimal(10,2) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hbwith','add_time')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `add_time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hbwith','pay_time')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `pay_time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hbwith','partner_trade_no')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `partner_trade_no` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hbwith','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `status` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_hbwith','nick_name')) {pdo_query("ALTER TABLE ".tablename('hcstep_hbwith')." ADD   `nick_name` varchar(255) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_hongbao` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `hongbaopic` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `hongbaomoney` varchar(255) NOT NULL,
  `hongbaoname` varchar(255) NOT NULL,
  `hongbaonamecolor` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_hongbao','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_hongbao','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `uniacid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_hongbao','hongbaopic')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `hongbaopic` varchar(255) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_hongbao','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `displayorder` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_hongbao','enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `enabled` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_hongbao','hongbaomoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `hongbaomoney` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_hongbao','hongbaoname')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `hongbaoname` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_hongbao','hongbaonamecolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   `hongbaonamecolor` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_hongbao','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('hcstep_hongbao','idx_uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   KEY `idx_uniacid` (`uniacid`)");}
if(!pdo_fieldexists('hcstep_hongbao','idx_enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbao')." ADD   KEY `idx_enabled` (`enabled`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_hongbaolog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sonid` int(11) DEFAULT NULL,
  `invite_time` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_hongbaolog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbaolog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_hongbaolog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbaolog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hongbaolog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbaolog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hongbaolog','sonid')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbaolog')." ADD   `sonid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hongbaolog','invite_time')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbaolog')." ADD   `invite_time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_hongbaolog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_hongbaolog')." ADD   `status` int(11) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_huodong` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `displayorder` varchar(20) DEFAULT '' COMMENT '排序',
  `jump` varchar(20) DEFAULT NULL COMMENT '1抽奖2步数挑战3步数商城4小程序5h5',
  `entrypic` varchar(100) DEFAULT NULL,
  `xcxpath` varchar(255) DEFAULT NULL,
  `xcxappid` varchar(255) DEFAULT NULL,
  `h5` varchar(255) DEFAULT NULL,
  `diypic` varchar(255) NOT NULL,
  `step` varchar(50) NOT NULL,
  `ad` varchar(55) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_huodong','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_huodong','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_huodong','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `displayorder` varchar(20) DEFAULT '' COMMENT '排序'");}
if(!pdo_fieldexists('hcstep_huodong','jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `jump` varchar(20) DEFAULT NULL COMMENT '1抽奖2步数挑战3步数商城4小程序5h5'");}
if(!pdo_fieldexists('hcstep_huodong','entrypic')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `entrypic` varchar(100) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_huodong','xcxpath')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `xcxpath` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_huodong','xcxappid')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `xcxappid` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_huodong','h5')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `h5` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_huodong','diypic')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `diypic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_huodong','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `step` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_huodong','ad')) {pdo_query("ALTER TABLE ".tablename('hcstep_huodong')." ADD   `ad` varchar(55) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_icon` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `thumb` varchar(255) DEFAULT '',
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `jump` int(11) NOT NULL COMMENT '跳转方式 0 运动提醒1汗水日子2其他',
  `xcxpath` varchar(255) NOT NULL,
  `xcxappid` varchar(255) NOT NULL,
  `runpic` varchar(255) NOT NULL,
  `advnamecolor` varchar(100) NOT NULL,
  `h5` varchar(255) NOT NULL,
  `tippic` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_icon','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_icon','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `uniacid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_icon','advname')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `advname` varchar(50) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_icon','thumb')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `thumb` varchar(255) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_icon','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `displayorder` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_icon','enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `enabled` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_icon','jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `jump` int(11) NOT NULL COMMENT '跳转方式 0 运动提醒1汗水日子2其他'");}
if(!pdo_fieldexists('hcstep_icon','xcxpath')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `xcxpath` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_icon','xcxappid')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `xcxappid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_icon','runpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `runpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_icon','advnamecolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `advnamecolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_icon','h5')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `h5` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_icon','tippic')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   `tippic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_icon','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('hcstep_icon','idx_uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   KEY `idx_uniacid` (`uniacid`)");}
if(!pdo_fieldexists('hcstep_icon','idx_enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_icon')." ADD   KEY `idx_enabled` (`enabled`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_invitelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sonid` int(11) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `invite_time` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0 未兑换燃力币 1 已兑换',
  `time` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=160 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_invitelog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_invitelog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_invitelog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_invitelog','sonid')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `sonid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_invitelog','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `step` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_invitelog','invite_time')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `invite_time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_invitelog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `status` int(11) DEFAULT NULL COMMENT '0 未兑换燃力币 1 已兑换'");}
if(!pdo_fieldexists('hcstep_invitelog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_invitelog')." ADD   `time` varchar(255) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_kefu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `kefu_keyword` varchar(255) DEFAULT NULL,
  `kefu_title` varchar(255) DEFAULT NULL,
  `kefu_img` varchar(255) DEFAULT NULL,
  `kefu_gaishu` varchar(255) DEFAULT NULL,
  `kefu_url` varchar(255) DEFAULT NULL,
  `beizhu` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_kefu','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_kefu','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kefu','kefu_keyword')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `kefu_keyword` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kefu','kefu_title')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `kefu_title` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kefu','kefu_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `kefu_img` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kefu','kefu_gaishu')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `kefu_gaishu` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kefu','kefu_url')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `kefu_url` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kefu','beizhu')) {pdo_query("ALTER TABLE ".tablename('hcstep_kefu')." ADD   `beizhu` varchar(255) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_kouhonglog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `createtime` varchar(20) DEFAULT NULL,
  `createday` varchar(20) DEFAULT NULL,
  `goods_id` varchar(20) DEFAULT NULL,
  `invite_id` varchar(100) DEFAULT NULL,
  `status` int(11) DEFAULT '0' COMMENT '0挑战失败1挑战成功',
  `cishu` int(20) DEFAULT '0' COMMENT '0挑战零次没挑战直接邀请好友1直接开始挑战2满足条件后挑战第二次',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`user_id`,`createday`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_kouhonglog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_kouhonglog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kouhonglog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kouhonglog','createtime')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `createtime` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kouhonglog','createday')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `createday` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kouhonglog','goods_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `goods_id` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kouhonglog','invite_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `invite_id` varchar(100) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_kouhonglog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `status` int(11) DEFAULT '0' COMMENT '0挑战失败1挑战成功'");}
if(!pdo_fieldexists('hcstep_kouhonglog','cishu')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   `cishu` int(20) DEFAULT '0' COMMENT '0挑战零次没挑战直接邀请好友1直接开始挑战2满足条件后挑战第二次'");}
if(!pdo_fieldexists('hcstep_kouhonglog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_kouhonglog')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `msgid` varchar(255) DEFAULT NULL,
  `keyword1` varchar(255) DEFAULT NULL,
  `keyword2` varchar(255) DEFAULT NULL,
  `keyword3` varchar(255) DEFAULT NULL,
  `hongbao_msgid` varchar(255) NOT NULL,
  `fahuomsgid` varchar(255) NOT NULL,
  `notice` varchar(255) NOT NULL,
  `rolltime` varchar(255) NOT NULL,
  `status` int(11) NOT NULL,
  `centerhead` varchar(55) NOT NULL,
  `invite_icon` varchar(55) NOT NULL,
  `rule_icon` varchar(55) NOT NULL,
  `qs_icon` varchar(55) NOT NULL,
  `news_icon` varchar(55) NOT NULL,
  `contact_icon` varchar(55) NOT NULL,
  `ka_icon` varchar(55) NOT NULL,
  `set_icon` varchar(55) NOT NULL,
  `dhcolor` varchar(55) NOT NULL,
  `name_color` varchar(55) NOT NULL,
  `id_color` varchar(55) NOT NULL,
  `dhjl_color` varchar(55) NOT NULL,
  `idbg_color` varchar(55) NOT NULL,
  `is_fourhb` int(11) NOT NULL,
  `fourhb_sharetitle` varchar(100) NOT NULL,
  `fourhb_sharepic` varchar(100) NOT NULL,
  `min_bighbmoney` decimal(10,2) NOT NULL,
  `max_bighbmoney` decimal(10,2) NOT NULL,
  `min_smallhbmoney` decimal(10,2) NOT NULL,
  `max_smallhbmoney` decimal(10,2) NOT NULL,
  `min_hbtxmoney` decimal(10,2) NOT NULL,
  `txinfo` varchar(255) NOT NULL,
  `hbtext1` varchar(100) NOT NULL,
  `hbtext2` varchar(100) NOT NULL,
  `txcolor` varchar(50) NOT NULL,
  `txjl_color` varchar(50) NOT NULL,
  `fourhb_mainpic` varchar(60) NOT NULL,
  `daijiesuo` varchar(60) NOT NULL,
  `daikaiqi` varchar(60) NOT NULL,
  `yikaiqi` varchar(60) NOT NULL,
  `hb_icon` varchar(60) NOT NULL,
  `openhbpic` varchar(60) NOT NULL,
  `lotto_type` int(11) NOT NULL DEFAULT '1',
  `fourhb_coin` varchar(10) NOT NULL,
  `is_float` int(11) NOT NULL COMMENT '0不显示1电话2微信',
  `phoneno` varchar(20) NOT NULL,
  `call_icon` varchar(60) NOT NULL,
  `copytext` varchar(100) NOT NULL,
  `copy_icon` varchar(60) NOT NULL,
  `is_tan` int(11) NOT NULL,
  `tan_type` int(11) NOT NULL,
  `tan_goodsid` varchar(20) NOT NULL,
  `tan_pic` varchar(100) NOT NULL,
  `left1` varchar(100) NOT NULL,
  `left1_jump` int(11) NOT NULL,
  `left1_appid` varchar(50) NOT NULL,
  `left1_path` varchar(50) NOT NULL,
  `left2` varchar(100) NOT NULL,
  `left2_jump` int(11) NOT NULL,
  `left2_appid` varchar(50) NOT NULL,
  `left2_path` varchar(50) NOT NULL,
  `right1` varchar(100) NOT NULL,
  `right1_jump` int(11) NOT NULL,
  `right1_appid` varchar(50) NOT NULL,
  `right1_path` varchar(50) NOT NULL,
  `right2` varchar(100) NOT NULL,
  `right2_jump` int(11) NOT NULL,
  `right2_appid` varchar(50) NOT NULL,
  `right2_path` varchar(50) NOT NULL,
  `right3` varchar(100) NOT NULL,
  `right3_jump` int(11) NOT NULL,
  `right3_appid` varchar(50) NOT NULL,
  `right3_path` varchar(50) NOT NULL,
  `is_five` int(11) NOT NULL,
  `icon_position` int(11) NOT NULL,
  `fabu_icon` varchar(60) NOT NULL,
  `kouhong_sharetitle` varchar(100) NOT NULL,
  `kouhong_sharepic` varchar(100) NOT NULL,
  `kouhong_ids` varchar(100) NOT NULL,
  `order_icon` varchar(60) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_message','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_message','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_message','msgid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `msgid` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_message','keyword1')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `keyword1` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_message','keyword2')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `keyword2` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_message','keyword3')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `keyword3` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_message','hongbao_msgid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `hongbao_msgid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','fahuomsgid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `fahuomsgid` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','notice')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `notice` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','rolltime')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `rolltime` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `status` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','centerhead')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `centerhead` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','invite_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `invite_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','rule_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `rule_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','qs_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `qs_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','news_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `news_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','contact_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `contact_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','ka_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `ka_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','set_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `set_icon` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','dhcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `dhcolor` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','name_color')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `name_color` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','id_color')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `id_color` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','dhjl_color')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `dhjl_color` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','idbg_color')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `idbg_color` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','is_fourhb')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `is_fourhb` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','fourhb_sharetitle')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `fourhb_sharetitle` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','fourhb_sharepic')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `fourhb_sharepic` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','min_bighbmoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `min_bighbmoney` decimal(10,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','max_bighbmoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `max_bighbmoney` decimal(10,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','min_smallhbmoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `min_smallhbmoney` decimal(10,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','max_smallhbmoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `max_smallhbmoney` decimal(10,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','min_hbtxmoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `min_hbtxmoney` decimal(10,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','txinfo')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `txinfo` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','hbtext1')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `hbtext1` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','hbtext2')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `hbtext2` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','txcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `txcolor` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','txjl_color')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `txjl_color` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','fourhb_mainpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `fourhb_mainpic` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','daijiesuo')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `daijiesuo` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','daikaiqi')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `daikaiqi` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','yikaiqi')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `yikaiqi` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','hb_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `hb_icon` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','openhbpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `openhbpic` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','lotto_type')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `lotto_type` int(11) NOT NULL DEFAULT '1'");}
if(!pdo_fieldexists('hcstep_message','fourhb_coin')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `fourhb_coin` varchar(10) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','is_float')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `is_float` int(11) NOT NULL COMMENT '0不显示1电话2微信'");}
if(!pdo_fieldexists('hcstep_message','phoneno')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `phoneno` varchar(20) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','call_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `call_icon` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','copytext')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `copytext` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','copy_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `copy_icon` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','is_tan')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `is_tan` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','tan_type')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `tan_type` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','tan_goodsid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `tan_goodsid` varchar(20) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','tan_pic')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `tan_pic` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left1')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left1` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left1_jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left1_jump` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left1_appid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left1_appid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left1_path')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left1_path` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left2')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left2` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left2_jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left2_jump` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left2_appid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left2_appid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','left2_path')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `left2_path` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right1')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right1` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right1_jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right1_jump` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right1_appid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right1_appid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right1_path')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right1_path` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right2')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right2` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right2_jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right2_jump` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right2_appid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right2_appid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right2_path')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right2_path` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right3')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right3` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right3_jump')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right3_jump` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right3_appid')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right3_appid` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','right3_path')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `right3_path` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','is_five')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `is_five` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','icon_position')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `icon_position` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','fabu_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `fabu_icon` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','kouhong_sharetitle')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `kouhong_sharetitle` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','kouhong_sharepic')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `kouhong_sharepic` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','kouhong_ids')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `kouhong_ids` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_message','order_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_message')." ADD   `order_icon` varchar(60) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_mission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `title2` varchar(255) DEFAULT NULL,
  `mission_type` int(11) DEFAULT NULL COMMENT '0邀请好友1跳转小程序2步友圈首次发帖3绑定手机',
  `mission_icon` varchar(255) DEFAULT NULL,
  `step` varchar(20) DEFAULT NULL,
  `appid` varchar(100) DEFAULT NULL,
  `path` varchar(100) DEFAULT NULL,
  `displayorder` int(11) NOT NULL,
  `ad` varchar(50) NOT NULL COMMENT '视频广告id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_mission','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_mission','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','title')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `title` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','title2')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `title2` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','mission_type')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `mission_type` int(11) DEFAULT NULL COMMENT '0邀请好友1跳转小程序2步友圈首次发帖3绑定手机'");}
if(!pdo_fieldexists('hcstep_mission','mission_icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `mission_icon` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `step` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','appid')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `appid` varchar(100) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','path')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `path` varchar(100) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_mission','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `displayorder` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_mission','ad')) {pdo_query("ALTER TABLE ".tablename('hcstep_mission')." ADD   `ad` varchar(50) NOT NULL COMMENT '视频广告id'");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_missionlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `mission_id` int(11) DEFAULT NULL,
  `step` varchar(20) DEFAULT NULL,
  `time` varchar(20) DEFAULT NULL,
  `daytime` varchar(50) NOT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0完成任务未领取步数1领取步数',
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniacid` (`uniacid`,`user_id`,`mission_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_missionlog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_missionlog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_missionlog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_missionlog','mission_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `mission_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_missionlog','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `step` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_missionlog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `time` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_missionlog','daytime')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `daytime` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_missionlog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   `status` int(11) DEFAULT NULL COMMENT '0完成任务未领取步数1领取步数'");}
if(!pdo_fieldexists('hcstep_missionlog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_missionlog')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `makenews_uid` int(11) DEFAULT NULL,
  `dt_id` int(11) NOT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1点赞2关注3评论',
  `time` varchar(20) DEFAULT NULL,
  `status` int(11) NOT NULL COMMENT '0未读1已读',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_news','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_news','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_news','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_news','makenews_uid')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `makenews_uid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_news','dt_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `dt_id` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_news','type')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `type` int(11) DEFAULT NULL COMMENT '1点赞2关注3评论'");}
if(!pdo_fieldexists('hcstep_news','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `time` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_news','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_news')." ADD   `status` int(11) NOT NULL COMMENT '0未读1已读'");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `userName` varchar(255) DEFAULT NULL,
  `postalCode` varchar(255) DEFAULT NULL,
  `provinceName` varchar(255) DEFAULT NULL,
  `cityName` varchar(255) DEFAULT NULL,
  `countyName` varchar(255) DEFAULT NULL,
  `detailInfo` varchar(255) DEFAULT NULL,
  `nationalCode` varchar(255) DEFAULT NULL,
  `telNumber` varchar(255) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `time` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0待发货1已发货',
  `type` int(11) NOT NULL COMMENT '0原价1币加钱2纯币',
  `oid` int(11) NOT NULL COMMENT '支付表id',
  `express` varchar(255) NOT NULL,
  `fahuotime` varchar(255) NOT NULL,
  `expressname` varchar(255) NOT NULL COMMENT '快递公司名',
  `hexiaostatus` int(11) DEFAULT '0' COMMENT '0未核销1已核销',
  `hexiaotime` varchar(255) NOT NULL,
  `hexiaoyuan` int(11) NOT NULL COMMENT '核销员id',
  `endtime` varchar(60) NOT NULL COMMENT '核销码到期时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=135 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_orders','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_orders','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','userName')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `userName` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','postalCode')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `postalCode` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','provinceName')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `provinceName` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','cityName')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `cityName` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','countyName')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `countyName` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','detailInfo')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `detailInfo` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','nationalCode')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `nationalCode` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','telNumber')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `telNumber` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','goods_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `goods_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_orders','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `time` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_orders','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `status` int(11) NOT NULL COMMENT '0待发货1已发货'");}
if(!pdo_fieldexists('hcstep_orders','type')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `type` int(11) NOT NULL COMMENT '0原价1币加钱2纯币'");}
if(!pdo_fieldexists('hcstep_orders','oid')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `oid` int(11) NOT NULL COMMENT '支付表id'");}
if(!pdo_fieldexists('hcstep_orders','express')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `express` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_orders','fahuotime')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `fahuotime` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_orders','expressname')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `expressname` varchar(255) NOT NULL COMMENT '快递公司名'");}
if(!pdo_fieldexists('hcstep_orders','hexiaostatus')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `hexiaostatus` int(11) DEFAULT '0' COMMENT '0未核销1已核销'");}
if(!pdo_fieldexists('hcstep_orders','hexiaotime')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `hexiaotime` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_orders','hexiaoyuan')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `hexiaoyuan` int(11) NOT NULL COMMENT '核销员id'");}
if(!pdo_fieldexists('hcstep_orders','endtime')) {pdo_query("ALTER TABLE ".tablename('hcstep_orders')." ADD   `endtime` varchar(60) NOT NULL COMMENT '核销码到期时间'");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_payment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `weid` int(11) DEFAULT '0',
  `uid` int(11) DEFAULT '0',
  `ordersn` varchar(30) DEFAULT '',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `paytype` int(11) NOT NULL COMMENT '0原价1步数加钱',
  `fee` decimal(11,2) NOT NULL,
  `status` tinyint(1) DEFAULT '0',
  `paystatus` tinyint(1) NOT NULL DEFAULT '0',
  `paytime` char(10) NOT NULL,
  `transid` varchar(50) DEFAULT '',
  `createtime` int(10) DEFAULT '0',
  `package` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_payment','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_payment','weid')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `weid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_payment','uid')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `uid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_payment','ordersn')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `ordersn` varchar(30) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_payment','goods_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `goods_id` int(11) DEFAULT NULL COMMENT '商品id'");}
if(!pdo_fieldexists('hcstep_payment','paytype')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `paytype` int(11) NOT NULL COMMENT '0原价1步数加钱'");}
if(!pdo_fieldexists('hcstep_payment','fee')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `fee` decimal(11,2) NOT NULL");}
if(!pdo_fieldexists('hcstep_payment','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `status` tinyint(1) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_payment','paystatus')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `paystatus` tinyint(1) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_payment','paytime')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `paytime` char(10) NOT NULL");}
if(!pdo_fieldexists('hcstep_payment','transid')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `transid` varchar(50) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_payment','createtime')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `createtime` int(10) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_payment','package')) {pdo_query("ALTER TABLE ".tablename('hcstep_payment')." ADD   `package` varchar(50) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_peoplelog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `son_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_peoplelog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_peoplelog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_peoplelog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_peoplelog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_peoplelog','goods_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_peoplelog')." ADD   `goods_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_peoplelog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_peoplelog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_peoplelog','son_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_peoplelog')." ADD   `son_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_peoplelog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_peoplelog')." ADD   `time` varchar(255) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_question` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) NOT NULL,
  `displayorder` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `thumb` varchar(200) NOT NULL,
  `link` varchar(200) NOT NULL,
  `content` text NOT NULL,
  `enabled` tinyint(3) NOT NULL,
  `createtime` char(10) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_question','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_question','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `uniacid` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `displayorder` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','title')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `title` varchar(200) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','thumb')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `thumb` varchar(200) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','link')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `link` varchar(200) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','content')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `content` text NOT NULL");}
if(!pdo_fieldexists('hcstep_question','enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `enabled` tinyint(3) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','createtime')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   `createtime` char(10) NOT NULL");}
if(!pdo_fieldexists('hcstep_question','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_question')." ADD   PRIMARY KEY (`id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_set` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `sharetitle` varchar(255) DEFAULT NULL,
  `sharepic` varchar(255) DEFAULT NULL,
  `coinname` varchar(255) DEFAULT NULL,
  `rate` varchar(255) DEFAULT NULL,
  `sharestep` varchar(255) DEFAULT NULL,
  `boxprice` varchar(255) DEFAULT NULL,
  `rulepic` varchar(255) DEFAULT NULL,
  `headcolor` varchar(255) NOT NULL,
  `xcx` varchar(255) NOT NULL,
  `up` varchar(255) NOT NULL,
  `notice` varchar(5000) NOT NULL,
  `shenhe` int(11) NOT NULL,
  `loginpic` varchar(255) NOT NULL,
  `indexbg` varchar(255) NOT NULL,
  `indexbutton` varchar(255) NOT NULL,
  `inviteball` varchar(255) NOT NULL,
  `upball` varchar(255) NOT NULL,
  `zerotip` varchar(255) NOT NULL,
  `poortip` varchar(255) NOT NULL,
  `questionpic` varchar(255) NOT NULL,
  `is_follow` int(11) NOT NULL,
  `followpic` varchar(255) NOT NULL,
  `kefu_title` varchar(255) NOT NULL,
  `kefu_img` varchar(255) NOT NULL,
  `kefu_gaishu` varchar(255) NOT NULL,
  `kefu_url` varchar(255) NOT NULL,
  `kefupic` varchar(255) NOT NULL,
  `guanzhu_step` varchar(255) NOT NULL,
  `followlogo` varchar(255) NOT NULL,
  `maxstep` varchar(255) NOT NULL,
  `sharetext` varchar(255) NOT NULL,
  `version` varchar(255) NOT NULL,
  `shareinfo` varchar(255) NOT NULL,
  `upinfo` varchar(255) NOT NULL,
  `adunit` varchar(255) NOT NULL,
  `adunit2` varchar(255) NOT NULL,
  `adunit3` varchar(255) NOT NULL,
  `boxpic` varchar(255) NOT NULL,
  `activitypic` varchar(255) NOT NULL,
  `applypic` varchar(255) NOT NULL,
  `rule` varchar(255) NOT NULL,
  `sweattext` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `comeon` varchar(255) NOT NULL,
  `posterpic` varchar(255) NOT NULL,
  `smalltip` varchar(255) NOT NULL,
  `signsharetext` varchar(255) NOT NULL,
  `signpic` varchar(255) NOT NULL,
  `signsharemoney` varchar(255) NOT NULL,
  `frame` varchar(255) NOT NULL,
  `signicon` varchar(255) NOT NULL,
  `signtext` varchar(255) NOT NULL,
  `smalltipcolor` varchar(100) NOT NULL,
  `sharetextcolor` varchar(100) NOT NULL,
  `shareinfocolor` varchar(100) NOT NULL,
  `signtextcolor` varchar(100) NOT NULL,
  `buttonbg` varchar(100) NOT NULL,
  `balltextcolor` varchar(100) NOT NULL,
  `centercolor` varchar(100) NOT NULL,
  `coinpic` varchar(100) NOT NULL,
  `cointextcolor` varchar(100) NOT NULL,
  `invitetype` int(11) NOT NULL DEFAULT '1',
  `hongbaobg` varchar(100) NOT NULL,
  `longbg` varchar(100) NOT NULL,
  `hongbaotext` varchar(100) NOT NULL,
  `adunit4` varchar(50) NOT NULL,
  `adunit5` varchar(50) NOT NULL,
  `sweatcolor` varchar(20) NOT NULL,
  `updatetip` varchar(100) NOT NULL,
  `updatepic` varchar(55) NOT NULL,
  `updatetipcolor` varchar(10) NOT NULL,
  `goodstop` varchar(55) NOT NULL,
  `adunit6` varchar(50) NOT NULL,
  `posterpic2` varchar(60) NOT NULL,
  `posterpic3` varchar(60) NOT NULL,
  `is_qian` int(11) NOT NULL DEFAULT '1',
  `is_kuang` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_set','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_set','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','sharetitle')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sharetitle` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','sharepic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sharepic` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','coinname')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `coinname` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','rate')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `rate` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','sharestep')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sharestep` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','boxprice')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `boxprice` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','rulepic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `rulepic` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_set','headcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `headcolor` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','xcx')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `xcx` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','up')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `up` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','notice')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `notice` varchar(5000) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','shenhe')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `shenhe` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','loginpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `loginpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','indexbg')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `indexbg` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','indexbutton')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `indexbutton` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','inviteball')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `inviteball` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','upball')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `upball` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','zerotip')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `zerotip` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','poortip')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `poortip` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','questionpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `questionpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','is_follow')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `is_follow` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','followpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `followpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','kefu_title')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `kefu_title` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','kefu_img')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `kefu_img` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','kefu_gaishu')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `kefu_gaishu` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','kefu_url')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `kefu_url` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','kefupic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `kefupic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','guanzhu_step')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `guanzhu_step` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','followlogo')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `followlogo` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','maxstep')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `maxstep` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','sharetext')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sharetext` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','version')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `version` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','shareinfo')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `shareinfo` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','upinfo')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `upinfo` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','adunit')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `adunit` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','adunit2')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `adunit2` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','adunit3')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `adunit3` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','boxpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `boxpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','activitypic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `activitypic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','applypic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `applypic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','rule')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `rule` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','sweattext')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sweattext` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','icon')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `icon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','comeon')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `comeon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','posterpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `posterpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','smalltip')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `smalltip` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','signsharetext')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `signsharetext` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','signpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `signpic` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','signsharemoney')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `signsharemoney` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','frame')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `frame` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','signicon')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `signicon` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','signtext')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `signtext` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','smalltipcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `smalltipcolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','sharetextcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sharetextcolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','shareinfocolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `shareinfocolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','signtextcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `signtextcolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','buttonbg')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `buttonbg` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','balltextcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `balltextcolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','centercolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `centercolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','coinpic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `coinpic` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','cointextcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `cointextcolor` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','invitetype')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `invitetype` int(11) NOT NULL DEFAULT '1'");}
if(!pdo_fieldexists('hcstep_set','hongbaobg')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `hongbaobg` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','longbg')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `longbg` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','hongbaotext')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `hongbaotext` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','adunit4')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `adunit4` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','adunit5')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `adunit5` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','sweatcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `sweatcolor` varchar(20) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','updatetip')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `updatetip` varchar(100) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','updatepic')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `updatepic` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','updatetipcolor')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `updatetipcolor` varchar(10) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','goodstop')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `goodstop` varchar(55) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','adunit6')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `adunit6` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','posterpic2')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `posterpic2` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','posterpic3')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `posterpic3` varchar(60) NOT NULL");}
if(!pdo_fieldexists('hcstep_set','is_qian')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `is_qian` int(11) NOT NULL DEFAULT '1'");}
if(!pdo_fieldexists('hcstep_set','is_kuang')) {pdo_query("ALTER TABLE ".tablename('hcstep_set')." ADD   `is_kuang` int(11) NOT NULL DEFAULT '1'");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_shipinlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `daytime` varchar(255) DEFAULT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`),
  KEY `status` (`status`),
  KEY `daytime` (`daytime`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_shipinlog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_shipinlog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shipinlog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shipinlog','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   `step` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shipinlog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   `time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shipinlog','daytime')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   `daytime` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shipinlog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   `status` int(11) NOT NULL");}
if(!pdo_fieldexists('hcstep_shipinlog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('hcstep_shipinlog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   KEY `uniacid` (`uniacid`)");}
if(!pdo_fieldexists('hcstep_shipinlog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   KEY `status` (`status`)");}
if(!pdo_fieldexists('hcstep_shipinlog','daytime')) {pdo_query("ALTER TABLE ".tablename('hcstep_shipinlog')." ADD   KEY `daytime` (`daytime`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_shop` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `topbg` varchar(255) NOT NULL,
  `shopname` varchar(255) NOT NULL,
  `sheng` varchar(255) DEFAULT NULL,
  `shi` varchar(255) DEFAULT NULL,
  `qu` varchar(255) DEFAULT NULL,
  `tel` varchar(255) DEFAULT NULL,
  `starttime` varchar(255) DEFAULT NULL,
  `endtime` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `user_id` varchar(500) NOT NULL,
  `openid` varchar(255) DEFAULT NULL,
  `lat` varchar(20) NOT NULL,
  `lng` varchar(20) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_shop','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_shop','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','logo')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `logo` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','topbg')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `topbg` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_shop','shopname')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `shopname` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_shop','sheng')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `sheng` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','shi')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `shi` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','qu')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `qu` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','tel')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `tel` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','starttime')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `starttime` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','endtime')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `endtime` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','address')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `address` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `user_id` varchar(500) NOT NULL");}
if(!pdo_fieldexists('hcstep_shop','openid')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `openid` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_shop','lat')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `lat` varchar(20) NOT NULL");}
if(!pdo_fieldexists('hcstep_shop','lng')) {pdo_query("ALTER TABLE ".tablename('hcstep_shop')." ADD   `lng` varchar(20) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_sort` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT '0',
  `advname` varchar(50) DEFAULT '',
  `type` int(11) DEFAULT NULL,
  `displayorder` int(11) DEFAULT '0',
  `enabled` int(11) DEFAULT '0',
  `is_distance` int(11) NOT NULL DEFAULT '0' COMMENT '是否按距离排序',
  PRIMARY KEY (`id`),
  KEY `idx_uniacid` (`uniacid`),
  KEY `idx_enabled` (`enabled`),
  KEY `idx_displayorder` (`displayorder`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_sort','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_sort','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   `uniacid` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_sort','advname')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   `advname` varchar(50) DEFAULT ''");}
if(!pdo_fieldexists('hcstep_sort','type')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   `type` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_sort','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   `displayorder` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_sort','enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   `enabled` int(11) DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_sort','is_distance')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   `is_distance` int(11) NOT NULL DEFAULT '0' COMMENT '是否按距离排序'");}
if(!pdo_fieldexists('hcstep_sort','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   PRIMARY KEY (`id`)");}
if(!pdo_fieldexists('hcstep_sort','idx_uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   KEY `idx_uniacid` (`uniacid`)");}
if(!pdo_fieldexists('hcstep_sort','idx_enabled')) {pdo_query("ALTER TABLE ".tablename('hcstep_sort')." ADD   KEY `idx_enabled` (`enabled`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_topic` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `displayorder` varchar(20) DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '0不显示1显示',
  `toppic` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_topic','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_topic')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_topic','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_topic')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_topic','title')) {pdo_query("ALTER TABLE ".tablename('hcstep_topic')." ADD   `title` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_topic','displayorder')) {pdo_query("ALTER TABLE ".tablename('hcstep_topic')." ADD   `displayorder` varchar(20) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_topic','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_topic')." ADD   `status` int(11) DEFAULT NULL COMMENT '0不显示1显示'");}
if(!pdo_fieldexists('hcstep_topic','toppic')) {pdo_query("ALTER TABLE ".tablename('hcstep_topic')." ADD   `toppic` varchar(255) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_uplog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `step` varchar(255) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  `day` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=769 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_uplog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_uplog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_uplog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_uplog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_uplog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_uplog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_uplog','step')) {pdo_query("ALTER TABLE ".tablename('hcstep_uplog')." ADD   `step` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_uplog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_uplog')." ADD   `time` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_uplog','day')) {pdo_query("ALTER TABLE ".tablename('hcstep_uplog')." ADD   `day` varchar(255) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `gender` varchar(255) DEFAULT NULL,
  `open_id` varchar(255) DEFAULT NULL,
  `nick_name` varchar(255) DEFAULT NULL,
  `head_pic` varchar(255) DEFAULT NULL,
  `province` varchar(255) DEFAULT NULL,
  `money` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '可提现金额',
  `fatherid` int(11) DEFAULT NULL,
  `black` int(11) NOT NULL DEFAULT '0' COMMENT '0正常1拉黑',
  `is_yy` int(11) NOT NULL DEFAULT '0',
  `signtime` varchar(255) NOT NULL DEFAULT '1' COMMENT '连续签到次数',
  `lasttime` varchar(255) NOT NULL COMMENT '最后签到时间',
  `sharetime` varchar(255) NOT NULL,
  `hongbaofid` int(11) NOT NULL DEFAULT '0',
  `rmb` decimal(10,2) DEFAULT '0.00',
  `tantime` varchar(50) NOT NULL,
  `mobile` varchar(20) NOT NULL COMMENT '手机号',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `KEY` (`uniacid`,`open_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2318 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_users','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD 
  `user_id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_users','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `status` int(11) NOT NULL DEFAULT '1'");}
if(!pdo_fieldexists('hcstep_users','city')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `city` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','country')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `country` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','gender')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `gender` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','open_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `open_id` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','nick_name')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `nick_name` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','head_pic')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `head_pic` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','province')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `province` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','money')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `money` decimal(11,4) NOT NULL DEFAULT '0.0000' COMMENT '可提现金额'");}
if(!pdo_fieldexists('hcstep_users','fatherid')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `fatherid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_users','black')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `black` int(11) NOT NULL DEFAULT '0' COMMENT '0正常1拉黑'");}
if(!pdo_fieldexists('hcstep_users','is_yy')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `is_yy` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_users','signtime')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `signtime` varchar(255) NOT NULL DEFAULT '1' COMMENT '连续签到次数'");}
if(!pdo_fieldexists('hcstep_users','lasttime')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `lasttime` varchar(255) NOT NULL COMMENT '最后签到时间'");}
if(!pdo_fieldexists('hcstep_users','sharetime')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `sharetime` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_users','hongbaofid')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `hongbaofid` int(11) NOT NULL DEFAULT '0'");}
if(!pdo_fieldexists('hcstep_users','rmb')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `rmb` decimal(10,2) DEFAULT '0.00'");}
if(!pdo_fieldexists('hcstep_users','tantime')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `tantime` varchar(50) NOT NULL");}
if(!pdo_fieldexists('hcstep_users','mobile')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   `mobile` varchar(20) NOT NULL COMMENT '手机号'");}
if(!pdo_fieldexists('hcstep_users','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_users')." ADD   PRIMARY KEY (`user_id`)");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_winlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `userName` varchar(255) NOT NULL,
  `postalCode` varchar(255) NOT NULL,
  `provinceName` varchar(255) NOT NULL,
  `cityName` varchar(255) NOT NULL,
  `countyName` varchar(255) NOT NULL,
  `detailInfo` varchar(255) NOT NULL,
  `nationalCode` varchar(255) NOT NULL,
  `telNumber` varchar(255) NOT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `time` varchar(255) NOT NULL,
  `status` int(11) NOT NULL COMMENT '0待发货1已发货',
  `express` varchar(255) NOT NULL,
  `fahuotime` varchar(255) NOT NULL,
  `expressname` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=504 DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_winlog','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_winlog','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_winlog','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_winlog','userName')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `userName` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','postalCode')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `postalCode` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','provinceName')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `provinceName` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','cityName')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `cityName` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','countyName')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `countyName` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','detailInfo')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `detailInfo` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','nationalCode')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `nationalCode` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','telNumber')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `telNumber` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','goods_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `goods_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_winlog','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `time` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','status')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `status` int(11) NOT NULL COMMENT '0待发货1已发货'");}
if(!pdo_fieldexists('hcstep_winlog','express')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `express` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','fahuotime')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `fahuotime` varchar(255) NOT NULL");}
if(!pdo_fieldexists('hcstep_winlog','expressname')) {pdo_query("ALTER TABLE ".tablename('hcstep_winlog')." ADD   `expressname` varchar(255) NOT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_xuni` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `nick_name` varchar(255) DEFAULT NULL,
  `head_pic` varchar(255) DEFAULT NULL,
  `goods_id` int(11) DEFAULT NULL,
  `time` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_xuni','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_xuni')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_xuni','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_xuni')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_xuni','nick_name')) {pdo_query("ALTER TABLE ".tablename('hcstep_xuni')." ADD   `nick_name` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_xuni','head_pic')) {pdo_query("ALTER TABLE ".tablename('hcstep_xuni')." ADD   `head_pic` varchar(255) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_xuni','goods_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_xuni')." ADD   `goods_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_xuni','time')) {pdo_query("ALTER TABLE ".tablename('hcstep_xuni')." ADD   `time` varchar(255) DEFAULT NULL");}
pdo_query("CREATE TABLE IF NOT EXISTS `ims_hcstep_zan` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uniacid` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `dt_id` int(11) DEFAULT NULL,
  `target_id` int(11) NOT NULL COMMENT '被点赞人id',
  PRIMARY KEY (`id`),
  KEY `uniacid` (`uniacid`,`user_id`,`dt_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

");

if(!pdo_fieldexists('hcstep_zan','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_zan')." ADD 
  `id` int(11) NOT NULL AUTO_INCREMENT");}
if(!pdo_fieldexists('hcstep_zan','uniacid')) {pdo_query("ALTER TABLE ".tablename('hcstep_zan')." ADD   `uniacid` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_zan','user_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_zan')." ADD   `user_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_zan','dt_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_zan')." ADD   `dt_id` int(11) DEFAULT NULL");}
if(!pdo_fieldexists('hcstep_zan','target_id')) {pdo_query("ALTER TABLE ".tablename('hcstep_zan')." ADD   `target_id` int(11) NOT NULL COMMENT '被点赞人id'");}
if(!pdo_fieldexists('hcstep_zan','id')) {pdo_query("ALTER TABLE ".tablename('hcstep_zan')." ADD   PRIMARY KEY (`id`)");}
