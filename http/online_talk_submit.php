<?php
/*
// - ����˵�� : online talk save messages
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2007-05-19 22:40
*/
require "../core/core.php";

$timestamp = time();
$fromname = $username;

$replyfromid = intval($_POST["messid"]);
$toname = convert($_POST["name"], "utf-8", "gb2312");
$content = convert($_POST["content"], "utf-8", "gb2312");

if ($db->query("insert into sys_message set fromname='$fromname', toname='$toname', replyfromid='$replyfromid', content='$content', addtime='$timestamp'")) {
	echo $replyfromid;
}
?>