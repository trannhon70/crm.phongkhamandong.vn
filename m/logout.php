<?php
/*
// - ����˵�� : �˳���վ����ϵͳ
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-15 11:39
*/
require "../core/core.php";
$db->query("update sys_admin set online='0', lastactiontime='0' where name='$username' limit 1");

$_SESSION[$cfgSessionName] = array();
$_SESSION = array();
session_destroy();

if (!$debug_mode) {
	//$log->add("logout", "����Ա $username �˳�ϵͳ");
}

header("location:../m/login.php?username=$username");
?>