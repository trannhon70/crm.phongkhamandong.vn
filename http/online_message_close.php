<?php
/*
// - ����˵�� : online talk save messages
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2007-05-19 22:40
*/
require "../core/core.php";

$timestamp = time();

$nMessID = intval($_GET["id"]);
if ($db->query("update sys_message set readtime='$timestamp' where id='$nMessID' limit 1")) {
	echo $nMessID;
}
?>