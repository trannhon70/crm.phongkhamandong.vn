<?php

/*

// ˵��: ����֪ͨ�Ѷ�

// ����: ��ҽս�� 

// ʱ��: 2010-09-17

*/

header("Content-Type:text/html;charset=GB2312");

header("Cache-Control: no-cache, must-revalidate");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require "../core/core.php";



$id = intval($_GET["id"]);



$old = $db->query("select * from sys_notice where id=$id limit 1", 1);

$new_uids = trim(trim($old["read_uids"]), ",");



// ����Ƿ��Ѿ�����:

if ($new_uids && substr_count(",".$new_uids.",", ",".$uid.",") > 0) {

	// �Ѵ���, ���������

} else {

	// ��UID�����Ѷ�����:

	if ($new_uids) {

		$new_uids .= ",";

	}

	$new_uids .= $uid;

	$db->query("update sys_notice set read_uids='".$new_uids."' where id=$id limit 1", 1);

}



echo "done..";



?>