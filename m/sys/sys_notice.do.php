<?php

/*

// ˵��:

// ����: ��ҽս�� 

// ʱ��:

*/



// ��Ӻ��޸�:

if ($op == "add" || $op == "edit") {



	if ($id) {

		$line = $db->query("select * from $table where id=$id limit 1", 1);

	}



	// post

	if ($_POST) {

		$r = array();



		// ֪ͨ������:

		if ($_POST["reader_type"] == '') {

			$_POST["reader_type"] = "all";

		}

		$r["reader_type"] = $reader = $_POST["reader_type"];

		if ($reader == "part") {

			$r["part_ids"] = implode(",", array_keys($part->get_sub_part($_POST["part"], 1)));

		} else {

			$r["part_ids"] = '';

		}

		if ($reader == "user") {

			$r["uids"] = implode(",", $_POST["rec_user"]);

		} else {

			$r["uids"] = '';

		}



		// ֪ͨ����:

		$r["title"] = $_POST["title"];

		$r["content"] = $_POST["content"];



		// ֪ͨ��Ч��:

		$r["begintime"] = $_POST["begin_date"] ? strtotime($_POST["begin_date"]) : 0;

		$r["endtime"] = $_POST["end_date"] ? strtotime($_POST["end_date"]) : 0;



		if ($op == 'add') {

			$r["isshow"] = 1;

			$r["uid"] = $uid;

			$r["u_realname"] = $realname;

			$r["addtime"] = time();

		}



		$sqldata = $db->sqljoin($r);

		if ($op == 'add') {

			$sql = "insert into $table set $sqldata";

		} else {

			$sql = "update $table set $sqldata where id=$id limit 1";

		}

		if ($db->query($sql)) {

			msg_box("�����ύ�ɹ���", back_url($_POST["back_url"], $pinfo["link"], "#".$id), 1);

		} else {

			msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);

		}

		exit;

	}



	$title = $pinfo["title"]." - ".($op == "add" ? "����" : "�޸�");



	include "sys_notice.edit.php";

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





if ($op == "setshow") {

	$isshow_value = intval($_GET["value"]) > 0 ? 1 : 0;

	$ids = explode(",", $_GET["id"]);

	$set_ok = $set_bad = 0;

	foreach ($ids as $opid) {

		if (($opid = intval($opid)) > 0) {

			if ($db->query("update $table set isshow='$isshow_value' where id='$opid' limit 1")) {

				$set_ok++;

			} else {

				$set_bad++;

			}

		}

	}



	if ($set_bad > 0) {

		msg_box("�����ɹ���� $set_ok ����ʧ�� $del_bad ����", "back", 1);

	} else {

		msg_box("���óɹ���", "back", 1);

	}

}



?>