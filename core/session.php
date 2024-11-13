<?php

/*

// ˵��: session ���浽 ���ݿ�

// ����: ��ҽս�� 

// ʱ��: 2013-10-28 20:00

*/



// ��ʼ�� session:

function ses_open($save_path, $sid) {

	$db = ses_init_db();



	if (!$db) exit("Error: session can not init with db.");



	if (mt_rand(1,10) == 1) ses_gc(1440);



	return true;

}



// session �ر�:

function ses_close() {

	return true;

}



// session ������:

function ses_read($sid) {

	$db = ses_init_db();

	if ($sid) {

		$s = $db->query("select data from sys_session where sid='$sid' limit 1", 1, "data");

		return $s;

	}



	return true;

}



// session д����:

function ses_write($sid, $sess_data) {

	global $uid, $username, $realname;

	$db = ses_init_db();



	$data = addslashes($sess_data);

	$time = time();



	if ($sid) {

		if ($db->query("select sid from sys_session where sid='$sid' limit 1", 1, "sid")) {

			$db->query("update sys_session set uid='$uid', u_realname='$realname', data='$data', updatetime='$time' where sid='$sid' limit 1");

		} else {

			$db->query("insert into sys_session set sid='$sid', uid='$uid', u_realname='$u_realname', data='$data', addtime='$time', updatetime='$time'");

		}

	}



	return true;

}



// ע�� session:

function ses_destroy($sid) {

	$db = ses_init_db();



	$db->query("delete from sys_session where sid='$sid' limit 1");



	return true;

}



// gc ѹ��:

function ses_gc($maxlifetime) {

	$db = ses_init_db();



	$time = time();

	$overtime = $time - $maxlifetime;

	$db->query("delete from sys_session where updatetime<$overtime");



	return true;

}





// ��ʼ�� db:

function ses_init_db() {

	if (!$GLOBALS["db"]) {

		// ���δ��ʼ��mysql,���Գ�ʼ��֮

		$path = dirname(__FILE__)."/";

		if ($GLOBALS["mysql_server"]) {

			$mysql_server = $GLOBALS["mysql_server"];

		} else {

			@include_once $path."config.php";

			$GLOBALS["mysql_server"] = $mysql_server;

		}

		@include_once $path."class.mysql.php";

		$GLOBALS["db"] = $db = new mysql($mysql_server);

	}



	return $GLOBALS["db"];

}



// ע�� session handler:

session_set_save_handler("ses_open", "ses_close", "ses_read", "ses_write", "ses_destroy", "ses_gc");



// ��ʼ session:

session_start();



?>