<?php
/*
// ˵��: �޸�����
// ����: ��ҽս�� 
// ʱ��: 2011-04-13 11:26
*/
require "../core/core.php";

if ($op == "change") {
	$pass = trim($_POST["pass"]);
	if (strlen($pass) < 6) {
		exit_html("����̫��");
	}
	$has_char = 0;
	for ($i = 0; $i < strlen($pass); $i++) {
		$ch = substr($pass, $i, 1);
		if (!in_array($ch, explode(" ", "0 1 2 3 4 5 6 7 8 9"))) {
			$has_char = 1;
			break;
		}
	}
	if (!$has_char) {
		exit_html("���벻��Ϊ������");
	}

	$enpass = md5($pass);
	if ($db->query("update sys_admin set pass='$enpass' where name='$username' limit 1")) {
		msg_box("�����޸ĳɹ����´ε�¼��ʹ�������룡", "./", 1, 3);
	} else {
		exit_html("�����޸�ʧ��");
	}

	exit;
}


$mod = intval($_GET["mod"]);
$tips = "";
if ($mod == 0) {
	$tips = "���ã����ǵ�һ�ε�¼��ϵͳ�������˺�Ϊ���������������޸������Բ߰�ȫ��";
} else if ($mod == 1) {
	$tips = "��������̫���򵥣������޸���ȷ����ȫ��";
}


?>
<html>
<head>
<title>���޸�����</title>
<meta charset="gbk" />
<link href="../res/base.css" rel="stylesheet" type="text/css">
<script src="../res/base.js" language="javascript"></script>
<style>
body, td {font-size:14px; font-family:"Tohamo"; }
.tip {font-size:16px; color:red; }
.tip2 {font-size:12px; color:gray; }
.p1 {margin-top:20px; }
.p2 {margin-top:10px; }
.i2 {margin-top:20px; margin-left:100px; }
.red {color:red; }
.mt10 {margin-top:6px; }
</style>
<script language="javascript">
function check_data(form) {
	if (form.pass.value.length < 6) {
		alert("�����볤������Ҫ��6λ��");
		form.pass.focus();
		return false;
	}

	if (form.pass.value != form.pass2.value) {
		alert("������������Ĳ�һ�£����飡");
		form.pass2.focus();
		return false;
	}

	var all_num = true;
	for (var i=0; i<form.pass.value.length; i++) {
		var ch = form.pass.value.substring(i, (i+1));
		if ("0123456789".indexOf(ch) == -1) {
			all_num = false;
			break;
		}
	}

	if (all_num) {
		alert("���벻���Ǵ����֣����飡");
		form.pass.focus();
		return false;
	}

	if (!confirm("�������������: "+form.pass.value+"  �Ƿ�ȷ���޸ģ�")) {
		return false;
	}

	return true;
}
</script>
</head>

<body>

<form name="modi_form" method="POST" onsubmit="return check_data(this)">
<table class="edit" width="600" align="center" border="0" style="margin-top:150px;">
	<tr>
		<td class="head" colspan="2">���޸�����</td>
	</tr>
	<tr>
		<td class="right" colspan="2">
			<div class="tip2">&nbsp;&nbsp;<?php echo $tips; ?></div>
			<div class="tip2 mt10">&nbsp;&nbsp;<b>�����볤������6λ���ұ��������ĸ(�������ַ�)���������������롣</b></div>
		</td>
	</tr>
	<tr>
		<td class="left" style="width:30%;">���������룺</td>
		<td class="right"><input type="password" class="input" name="pass" id="pass" /></td>
	</tr>
	<tr>
		<td class="left">ȷ�������룺</td>
		<td class="right"><input type="password" class="input" name="pass2" id="pass2" /></td>
	</tr>
	<tr>
		<td class="right" colspan="2" align="center">
			<div style="padding:20px;"><input type="submit" class="submit" value="�ύ�޸�" /></div>
		</td>
	</tr>
</table>
<input type="hidden" name="op" value="change" />
</form>




</body>
</html>