<?php

/*

// ˵��: hospital.op

// ����: ��ҽս�� 

// ʱ��: 2010-07-09

*/

defined("ROOT") or exit;



switch ($op) {

	case "add":

		include $mod.".edit.php";

		exit;



	case "edit":

		$line = $db->query("select * from $table where id=$id limit 1", 1);

		$line["config"] = (array) @unserialize($line["config"]);

		include $mod.".edit.php";

		exit;



	case "add_site":

		include $mod.".edit_site.php";

		exit;





	case "edit_site":

		$line = $db->query("select * from sites where id=$id limit 1", 1);

		$site_config = array();

		if ($line["config"]) {

			$site_config = @unserialize($line["config"]);

		}

		include $mod.".edit_site.php";

		exit;



	case "set_zhibiao":

		if ($id) {

			$line = $db->query("select * from $table where id=$id limit 1", 1);

			$line["config"] = (array) @unserialize($line["config"]);

			include "hospital.set_zhibiao.php";

		} else {

			exit("��������!  empty id.");

		}

		exit;



	case "delete":

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



		// ͬ�������û�����ҽԺ:

		$h_id_name = $db->query("select id,name from hospital order by sort desc,id asc", "id", "name");

		$keep_ids = array_keys($h_id_name);

		$users = $db->query("select id,realname,hospitals from sys_admin", "id");

		foreach ($users as $id => $v) {

			$ids = $v["hospitals"];

			if ($ids != '') {

				$ids_arr = explode(",", trim(trim($ids), ","));

				if (count($ids_arr) > 0) {

					foreach ($ids_arr as $x => $y) {

						if (!in_array($y, $keep_ids)) {

							unset($ids_arr[$x]);

						}

					}

					$new = implode(",", $ids_arr);

					if (trim($ids) != $new) {

						$db->query("update sys_admin set hospitals='$new' where id=$id limit 1");

					}

				}

			}

		}

		// ͬ�� end.



		if ($del_ok > 0) {

			$log->add("delete", "ɾ������", serialize($op_data));

		}



		if ($del_bad > 0) {

			msg_box("ɾ���ɹ� $del_ok �����ϣ�ɾ��ʧ�� $del_bad �����ϡ�", "back", 1);

		} else {

			msg_box("ɾ���ɹ�", "back", 1);

		}



	case "setshow":

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



	default:

		msg_box("����δ����...", "back", 1);

}



?>