<?php

/*

// ˵��:

// ����: ��ҽս�� 

// ʱ��:

*/



if ($op == "view") {

	$line = $db->query("select * from `$table` where id='$id' limit 1", 1);

	$title = "���������־";



	// ����:

	$viewdata = array(

		array("����ʱ��", date("Y-m-d H:i:s", $line["addtime"])),

		array("��������", $line["type"]." ".$OpType[$line["type"]]),

		array("����˵��", $line["title"]),

		array("ҳ��", $line["url"]),

		array("������", $line["username"]),

		array("IP��ַ", $line["ip"].(function_exists("GetIPArea") ? (" - ".GetIPArea($line["ip"])) : "")),

		array("��������", '<textarea style="width:90%;height:300px;">'.$line["data"].'</textarea>'),

	);



	include "log.view.php";

	exit;

}





// �����Ĵ���:

if ($op == "delete") {

	$ids = explode(",", $_GET["id"]);

	$del_ok = $del_bad = 0; $op_data = array();

	foreach ($ids as $opid) {

		if (($opid = intval($opid)) > 0) {

			$tmp_data = $db->query_first("select * from $table where id='$opid' limit 1");

			if ($db->query("delete from $table where id='$opid' limit 1")) {

				$del_ok++;

				$op_data[] = $tmp_data;

			} else {

				$del_bad++;

			}

		}

	}



	if ($del_ok > 0) {

		//log_add("delete", "ɾ������", $op_data, $table, $db);

	}



	if ($del_bad > 0) {

		msg_box("ɾ���ɹ� $del_ok �����ϣ�ɾ��ʧ�� $del_bad �����ϡ�", "back", 1);

	} else {

		msg_box("ɾ���ɹ�", "back", 1);

	}

	exit;

}





// �������:

if ($op == "clear") {

	$db->query("truncate table $table");

	msg_box("��ճɹ�", "back", 1);

}





// ɾ��һ��֮ǰ������:

if ($op == "del_week") {

	$date = strtotime("-1 week");

	$db->query("delete from $table where addtime<$date");

	msg_box("ɾ�����ݳɹ�", "back", 1);

}



?>