<?php

/*

// ˵��:

// ����: ��ҽս�� 

// ʱ��:

*/



// ��Ӻ��޸�:

if ($op == "add" || $op == "edit") {



	// post

	if ($_POST) {

		$record = array();

		$record["name"] = $_POST["ch_name"];

		$record["menu"] = $power->get_power_from_post();



		if ($op == "add") {

			$record["addtime"] = time();

			$record["author"] = $username;

		}



		$sqldata = $db->sqljoin($record);

		if ($op == "edit") {

			$sql = "update $table set $sqldata where id='$id' limit 1";

		} else {

			$sql = "insert into $table set $sqldata";

		}



		if ($db->query($sql)) {

			msg_box("Ȩ�������ύ�ɹ�", back_url($_POST["back_url"], $pinfo["link"], "#".$id), 1);

		} else {

			msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);

		}

	}

	// end of post





	if ($op == "add") {

		$title = "�����µ�Ȩ��";

	} else {

		$title = "�޸�Ȩ�޶���";

		$cline = $db->query("select * from $table where id='$id' limit 1", 1);

		if ($power->compare_power($cline["menu"], $usermenu) >= 0) {

			msg_box("û��Ȩ��", "back", 1, 2);

		}

	}

	include "character.edit.php";

	exit;

}





if ($op == "view") {

	$line = $db->query("select * from $table where id='$id' limit 1", 1);



	include "character.view.php";

	exit;

}



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

		$log->add("delete", "ɾ������", serialize($op_data));

	}



	if ($del_bad > 0) {

		msg_box("ɾ���ɹ� $del_ok �����ϣ�ɾ��ʧ�� $del_bad �����ϡ�", "back", 1);

	} else {

		msg_box("ɾ���ɹ�", "back", 1);

	}

}



?>