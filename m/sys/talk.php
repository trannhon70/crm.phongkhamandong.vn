<?php

/*

// ˵��: ������Ϣ

// ����: ��ҽս�� 

// ʱ��: 2010-09-17

*/

require "../../core/core.php";



$to_uid = intval($_REQUEST["to"]);

if ($to_uid > 0) {

	$uline = $db->query("select * from sys_admin where id=$to_uid limit 1", 1);

	if (!$uline) {

		exit_html("�޴��û�...");

	}

} else {

	exit_html("��������ȷ...");

}



if ($_POST) {

	$r = array();

	$r["to_uid"] = $to_uid;

	$r["to_realname"] = $uline["realname"];

	$r["from_uid"] = $uid;

	$r["from_realname"] = $realname;

	$r["content"] = $_POST["reply"];

	$r["from_id"] = 0;

	$r["addtime"] = time();



	$sqldata = $db->sqljoin($r);

	$db->query("insert into sys_message set $sqldata");



	echo '��Ϣ���ͳɹ�! <script> parent.load_box(0); </script>';

	exit;

}



$title = "�� ".$uline["realname"]." ������Ϣ";



?>

<html xmlns=http://www.w3.org/1999/xhtml>

<head>

<title><?php echo $title; ?></title>

<meta http-equiv="Content-Type" content="text/html;charset=gb2312">

<link href="/res/base.css" rel="stylesheet" type="text/css">

<script src="/res/base.js" language="javascript"></script>

<style>

body {padding:10px; margin:0; }

.send_m_box {padding:20px 0 0 0;}

.send_m_tip {float:left; width:100px; text-align:right; }

.send_m_text {float:left; }

</style>

<script language="javascript">

function send_reply() {

	if (byid("reply").value != '') {

		byid("reply_form").submit();

	} else {

		alert("���������Ļظ�����!");

		byid("reply").focus();

		return false;

	}

}



function close_page() {

	parent.load_box(0);

}



</script>

</head>



<body>



<form id="reply_form" method="POST">



<div class="send_m_box">

	<div class="send_m_tip">��Ϣ���ݣ�</div>

	<div class="send_m_text"><textarea name="reply" id="reply" class="input" style="width:90%; height:100px;"></textarea></div>

	<div class="clear"></div>

</div>



<input type="hidden" name="to" value="<?php echo $to_uid; ?>">

</form>



<div style="padding:20px 0 5px 0; text-align:center; ">

	<input type="submit" class="submit" onclick="send_reply()" value="������Ϣ" style="color:#009900;">&nbsp;��������&nbsp;

	<input type="submit" class="submit" onclick="close_page()" value="�ر�">

</div>



</body>

</html>