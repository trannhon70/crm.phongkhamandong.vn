<?php

/*

// ˵��: ������Ϣ�Ѷ�

// ����: ��ҽս�� 

// ʱ��: 2010-09-17

*/

header("Content-Type:text/html;charset=GB2312");

header("Cache-Control: no-cache, must-revalidate");

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

require "../core/core.php";



$id = intval($_GET["id"]);



$old = $db->query("select * from sys_message where id=$id limit 1", 1);



if ($old) {

	// �����Ѷ�:

	$db->query("update sys_message set readtime=$time where id=$id limit 1");

}



echo "done..";



?>