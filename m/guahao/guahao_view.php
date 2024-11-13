<?php
/*
// - ����˵�� : �Һ����ϲ鿴
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-22 13:03
*/
require "../../core/core.php";
$table = "guahao";

if (!$user_hospital_id) {
	exit_html("�Բ���û��ѡ��ҽԺ������ִ�иò�����");
}

if ($id = $_GET["id"]) {
	$line = $db->query("select * from $table where hospital_id=$user_hospital_id and id='$id' limit 1", 1);
} else {
	msg_box("��������...", "back", 1);
}

check_power("v", $pinfo, $pagepower) or msg_box("�Բ�����û�в鿴Ȩ��!", "back", 1);

$title = "�鿴�Һ�����";

// ����:
$viewdata = array(
	array("����", $line["name"]),
	array("�Ա�", $line["sex"]),
	array("�绰", $line["tel"]),
	array("E-Mail", $line["email"]),
	array("����", $line["city"]),
	array("ԤԼʱ��", $line["order_date"] > 0 ? date("Y-m-d H:i", $line["order_date"]) : '-'),
	array("ԤԼ����", $line["depart"]),
	array("ԤԼ����", text_show($line["content"])),
	array("ԤԼҽ��", $line["doctor"]),
	array("��ע", text_show($line["memo"])),
	array("������IP", $line["ip"]),
	array("IP��Ӧ��ַ", $line["ip_address"]),
	array("�ύʱ��", date("Y-m-d H:i", $line["addtime"])),
	array("��Դվ��", $line["site"]),
	array("POST����(���ο�)", $line["postdata"]),
);

if ($debug_mode) {
	$viewdata = array_merge($viewdata, array(
		array("GET����", $line["getdata"]),
		array("SERVER����", $line["serverdata"])
	));
}

include "tpl/view.tpl.php";
?>