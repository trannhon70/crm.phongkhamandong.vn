<?php

/*

// ˵��: �ύ������Ϣ

// ����: ��ҽս�� 

// ʱ��: 2013-10-13

*/

require "../../core/core.php";



if ($_POST) {

	$r = array();

	$r["detail"] = $_POST["detail"];

	$r["username"] = $username;

	$r["addtime"] = time();



	$sqldata = $db->sqljoin($r);

	$db->query("insert into sys_bugs set $sqldata");



	echo '<script>parent.load_box(0); parent.msg_box("�����ύ�ɹ���");</script>';

	exit;

}



?>

<html xmlns=http://www.w3.org/1999/xhtml>

<head>

<title>�ύ����͸Ľ�����</title>

<meta http-equiv="Content-Type" content="text/html;charset=gb2312">

<link href="/res/base.css" rel="stylesheet" type="text/css">

<script src="/res/base.js" language="javascript"></script>

<style>

#error_title {font-weight:bold; margin-top:5px;}

#error_box {margin-top:5px; }

#error_tips {color:silver; margin-top:5px; }

</style>

<script language="JavaScript">

function check_data(f) {

	if (f.detail.value == '') {

		alert("��������������ύ��"); f.detail.focus(); return false;

	}

	return true;

}

</script>

</head>



<body>

<form method="POST" enctype="multipart/form-data" onsubmit="return check_data(this)">

<div id="error_title">�����������飺</div>

<div id="error_box"><textarea name="detail" id="detail" class="input" style="width:100%; height:200px;"></textarea></div>

<div id="error_tips">��������Խ��ϸ��Խ���������ǽ�����⡣</div>

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>

</form>

</body>

</html>