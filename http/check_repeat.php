<?php

/*

// - ����˵�� : ��������ظ����

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-06-04 13:01

*/

header("Content-Type:text/html;charset=GB2312");

header("Cache-Control: no-cache, must-revalidate");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require "../core/core.php";

require "../core/class.fastjson.php";



$table = "patient_".$user_hospital_id;



$out = array();

$out["status"] = "bad";

$out["tips"] = '';



$user_part_name = $db->query("select id,name from sys_part", "id", "name");



$type = $_GET["type"];

$value = $_GET["value"];

if (in_array($type, array("name", "tel")) && $value != '') {



	//if ($type == "tel") {

	//	$value = ec($value, "ENCODE", md5($encode_password));

	//	$value = addslashes($value);

	//}



	$data = $db->query("select * from $table where $type='$value' order by id desc limit 3");

	//echo $db->sql;
    if(!is_array($data)){$data = array();}
	
	if (count($data) > 0) {

		$out["tips"] .= "��ע�⣬�������ظ�������Ҫ��ӵ�����ϵͳ�Ѵ���  ##";

		$tipdata = array();

		foreach ($data as $line) {

			$tipstr = '';

			$tipstr .= "������".$line["name"]."#";

			$tipstr .= "�Ա�".$line["sex"]."#";

			//$tipstr .= "�绰��".$line["tel"]."#";

			if (trim($line["content"]) != '') {

				$tipstr .= "��ѯ���ݣ�".cut(str_replace("\n", "��", str_replace("\r","", $line["content"])), 400, "��")."#";

			}

			$tipstr .= "ԤԼʱ�䣺".date("Y-m-d H:i", $line["order_date"])."#";

			$tipstr .= "���ʱ�䣺".date("Y-m-d H:i", $line["addtime"])."#";

			$tipstr .= "����ˣ�".$line["author"]." (".$user_part_name[$line["part_id"]].")"."##";

			$tipdata[] = $tipstr;

		}

		$out["tips"] .= implode('', $tipdata);

		$out["tips"] .= "�����鿼���Ƿ������ӣ�";

		$out["tips"] = str_replace("#", "\n", trim($out["tips"], "#"));

	}

	$out["status"] = "ok";

	$out["type"] = $type;

	$out["value"] = $value;

}



echo FastJSON::convert($out);

?>