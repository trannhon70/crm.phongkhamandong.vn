<?php
require "../../core/core.php";

if ($id = $_GET["id"]) {
	$line = $db->query_first("select * from yy_list where id='$id' limit 1");
} else {
	msg_box("��������...", "back", 1);
}

//!check_power("v", $pinfo, $pagepower) && msg_box("�Բ�����û�в鿴Ȩ��!", "back", 1);

$title = "�鿴��������";

$disease_id_name = $db->query("select id,name from ".$tabpre."disease where hospital_id=$user_hospital_id", 'id', 'name');
$part_id_name = $db->query("select id,name from ".$tabpre."sys_part", 'id', 'name');

$dis_array = array();
foreach (explode(",", $line["disease_id"]) as $v) {
	if ($v > 0) {
		$dis_array[] = $disease_id_name[$v];
	}
}

if ($realname != $li["author"]) {
	$line["tel"] = '-';
}

// ����:
$viewdata[1] = array(
	array("����", $line["name"]),
	array("�Ա�", $line["sex"]),
	array("�绰", $line["tel"]),
	array("QQ", $line["qq"]),
	array("ר�Һ�", $line["zhuanjia_num"]),
	array("��������", implode("��", $dis_array)),
	array("�Ӵ���", $line["jiedai"]),
	array("ԤԼʱ��", @date("Y-m-d H:i", $line["order_date"])),
	array("ý����Դ", $line["media_from"]),
	array("��Լ״̬", $status_array[$line["status"]]),
	array("��Լʱ��", ($line["status"] ==1 ? @date("Y-m-d H:i", $line["order_date"]) : "δ��Լ")),
	array("�Ӵ�ҽ��", in_array($uinfo["part_id"], array(2,3)) ? "<font color=gray>-����ʾ-</font>" : $line["doctor"]),
	array("�Ӵ�����", text_show($line["jiedai_content"])),
	array("���ʱ��", @date("Y-m-d H:i", $line["addtime"])),
	array("�����", $line["author"]),
	array("���ڲ���", $part_id_name[$line["part_id"]]),
);

$viewdata[2] = array(
	array("��ѯ����", this_text_show($line["content"]))
);

$viewdata[3] = array(
	array("�طü�¼", text_show($line["huifang"])),
);

$viewdata[4] = array(
	array("��ע", this_text_show($line["memo"])),
);


include "w_list_ck.tpl.php";


// --------- ���� -----------
function this_text_show($s) {
	$s = str_replace(" ", "&nbsp;", $s);
	$s = str_replace("\r", "", $s);
	$s = str_replace("\n", "<br>", $s);
	for ($i=0; $i<5; $i++) {
		$s = str_replace("<br><br>", "<br>", $s);
	}
	$s = "<br>".$s;
	$s = preg_replace("/<br>([^>]*?\d{2}:\d{2}:\d{2})/", "<br><br><font color=blue>[\\1]</font>", $s);
	while (substr($s, 0, 4) == "<br>") {
		$s = substr($s, 4);
	}
	return $s;
}
?>