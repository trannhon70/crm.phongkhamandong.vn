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

		$r = array();

		$r["mid"] = $_POST["menuid"];

		$r["type"] = $_POST["type"];

		$r["title"] = $_POST["title"];

		$r["link"] = $_POST["link"];

		$r["tips"] = $_POST["tips"];

		$r["pagesize"] = $_POST["pagesize"];

		$r["shortcut"] = $_POST["shortcut"];



		$modules = array();

		if (is_array($_POST["oprate"])) {

			foreach ($_POST["oprate"] as $v) {

				$va = explode(",", $v);

				foreach ($va as $vs) {

					$vs = trim($vs);

					if ($vs && !in_array($vs, $modules) && preg_match("/^[a-zA-Z]+$/", $vs)) {

						$modules[] = $vs;

					}

				}

			}

		}

		$r["modules"] = implode(",", $modules);



		if ($op == "edit") {

			$r["sort"] = $_POST["sort"];

		} else {

			$r["sort"] = $_POST["sort"] > 0 ? $_POST["sort"] : get_new_sort($r["type"], $r["mid"]);

			$r["addtime"] = time();

		}



		$sqldata = $db->sqljoin($r);

		if ($op == "edit") {

			$db->query("update $table set $sqldata where id='$id' limit 1");

		} else {

			$new_id = $db->query("insert into $table set $sqldata");

			if ($new_id > 0) $id = $new_id;

		}



		msg_box("�˵������ύ�ɹ�", back_url($_POST["back_url"], $pinfo["link"], "#".$id), 1);

	}

	// end of post



	if ($op == "add") {

		$title = "�����˵�����";

		$tm = $db->query_first("select mid from $table order by addtime desc limit 1");

		$last_used_mid = $tm["mid"];

	} else {

		$title = "�޸Ĳ˵�����";

		$line = $db->query("select * from $table where id='$id' limit 1", 1);

	}



	$middata = $db->query("select distinct mid from $table order by mid");

	$UsedMIDs = "";

	foreach ($middata as $midline) {

		$UsedMIDs .= ($UsedMIDs ? "," : "") . $midline["mid"];

	}

	$UsedMID = "<span class='intro'>".($UsedMIDs ? "���������Ѳ����ã�$UsedMIDs" : "������ָ��")."</span>";



	// ������һ�����õ�mid��

	$aUsedMID = explode(",", $UsedMIDs);

	$nNotUsedMID = 1;

	while (in_array($nNotUsedMID, $aUsedMID)) {

		$nNotUsedMID++;

	}

	$InputData = "<input name='menuid' size='8' class='input' value='$line[mid]'>";

	if (!$editmode) {

		$InputData .= "&nbsp;<a href='javascript:void(0);' onclick='document.mainform.menuid.value=$nNotUsedMID;'>[�Զ���д]</a>&nbsp;$UsedMID";

	}



	// ����ģʽ

	$SelectData = "<select name='menuid' class='combo'>\n";

	$mmdata = mysql_query("select mid,title from $table where mid>0 and type=1 order by sort");

	while ($mmline = mysql_fetch_array($mmdata)) {

		$sel = $op == "edit" ? ($line["mid"] == $mmline["mid"] ? " selected" : "") : ($mmline["mid"] == $last_used_mid ? " selected" : "");

		$SelectData .= "<option value='$mmline[0]'{$sel}>".$mmline[1]."($mmline[0])".($sel ? " *" : "")."</option>\n";

	}

	$SelectData .= "</select> <span class='intro'>�����ڵ�����Ϊ������</span>\n";



	include "menu.edit.php";

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



function get_new_sort($level, $mid) {

	if ($level == 1) {

		return $mid*100;

	} else {

		global $db, $table;

		$tm = $db->query_first("select sort from $table where mid='$mid' order by sort desc limit 1");

		return $tm["sort"]+1;

	}

}





?>