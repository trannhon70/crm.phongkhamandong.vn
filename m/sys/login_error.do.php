<?php

/*

// ˵��:

// ����: ��ҽս�� 

// ʱ��:

*/



// �����Ĵ���:

if ($op == "delete") {

	$ids = explode(",", $_GET["id"]);

	$del_ok = $del_bad = 0; $op_data = array();

	foreach ($ids as $opid) {

		if (($opid = intval($opid)) > 0) {

			$tmp_data = $db->query("select * from $table where id='$opid' limit 1", 1);

			if ($db->query("delete from $table where id='$opid' limit 1")) {

				$del_ok++;

				$op_data[] = $tmp_data;

			} else {

				$del_bad++;

			}

		}

	}



	if ($del_ok > 0) {

		$log->add("delete", "ɾ������", serialize($op_data));

	}



	if ($del_bad > 0) {

		msg_box("ɾ���ɹ� $del_ok �����ϣ�ɾ��ʧ�� $del_bad �����ϡ�", "back", 1);

	} else {

		msg_box("ɾ���ɹ�", "back", 1);

	}



}



if ($op == "clear") {

	$db->query("truncate table $table");

	msg_box("��ճɹ�", "back", 1);

}



if ($op == "del_week") {

	$date = strtotime("-1 week");

	$db->query("delete from $table where addtime<$date");

	msg_box("ɾ�����ݳɹ�", "back", 1);

}



?>