<?php
/*
// ˵��: ����ϵͳ��
// ����: ��ҽս�� 
// ʱ��: 2013-10-07 16:55
*/

$db_tables = array();

$db_tables["patient"] = "CREATE TABLE IF NOT EXISTS `patient_{hid}` (
	`id` int(10) NOT NULL AUTO_INCREMENT,
	`part_id` int(10) NOT NULL DEFAULT '0',
	`name` varchar(20) NOT NULL,
	`age` int(3) NOT NULL,
	`sex` varchar(6) NOT NULL COMMENT '�Ա�',
	`disease_id` vachar(200) NOT NULL DEFAULT '0' COMMENT '��������',
	`depart` int(10) NOT NULL DEFAULT '0' COMMENT '����',
	`is_local` tinyint(1) NOT NULL DEFAULT '1' COMMENT '�Ƿ񱾵ز���',
	`area` varchar(32) NOT NULL COMMENT '������Դ����',
	`tel` varchar(20) NOT NULL,
	`qq` varchar(20) NOT NULL,
	`zhuanjia_num` varchar(10) NOT NULL,
	`content` mediumtext NOT NULL,
	`jiedai` varchar(20) NOT NULL,
	`jiedai_content` text NOT NULL,
	`order_date` int(10) NOT NULL DEFAULT '0',
	`order_date_changes` int(4) NOT NULL DEFAULT '0' COMMENT 'ԤԼʱ���޸Ĵ���',
	`order_date_log` mediumtext NOT NULL,
	`media_from` varchar(20) NOT NULL,
	`engine` varchar(32) NOT NULL,
	`engine_key` varchar(32) NOT NULL,
	`from_site` varchar(40) NOT NULL,
	`from_account` int(10) NOT NULL DEFAULT '0' COMMENT '�����ʻ�',
	`memo` mediumtext NOT NULL,
	`status` int(2) NOT NULL DEFAULT '0',
	`fee` double(9,2) NOT NULL COMMENT '���Ʒ���',
	`come_date` int(10) NOT NULL DEFAULT '0',
	`doctor` varchar(32) NOT NULL COMMENT '�Ӵ�ҽ��',
	`xiaofei` int(2) NOT NULL DEFAULT '0' COMMENT '�Ƿ�����',
	`xiangmu` varchar(250) NOT NULL COMMENT '������Ŀ',
	`huifang` mediumtext NOT NULL COMMENT '�طü�¼',
	`rechecktime` int(10) NOT NULL DEFAULT '0' COMMENT '����ʱ��',
	`addtime` int(10) NOT NULL DEFAULT '0',
	`author` varchar(32) NOT NULL,
	`edit_log` mediumtext NOT NULL COMMENT '�޸ļ�¼',
	`mtly` varchar(32) COMMENT 'ý����Դ',
	PRIMARY KEY (`id`),
	KEY `part_id` (`part_id`),
	KEY `order_date` (`order_date`),
	KEY `addtime` (`addtime`),
	KEY `author` (`author`)
	) ENGINE=MyISAM  DEFAULT CHARSET=gbk;";


?>