<?php
/*
// ˵��: ��ʾ��Ϣ
// ����: ��ҽս�� 
// ʱ��: 2010-09-17
*/
require "../../core/core.php";

if ($_POST) {
	$r = array();
	$from_id = intval($_POST["from_id"]);
	if ($from_id > 0) {
		$old = $db->query("select * from sys_message where id=$from_id limit 1", 1);
		$r["to_uid"] = $old["from_uid"];
		$r["to_realname"] = $old["from_realname"];
		$r["from_uid"] = $uid;
		$r["from_realname"] = $realname;
		$r["content"] = $_POST["reply"];
		$r["from_id"] = $from_id;
		$r["addtime"] = time();

		$sqldata = $db->sqljoin($r);
		$db->query("insert into sys_message set $sqldata");

		echo '��Ϣ���ͳɹ�! <script> parent.load_box(0); </script>';
		exit;
	} else {
		exit("��������...");
	}

}

$id = $_GET["id"];
$line = $db->query("select * from sys_message where id=$id limit 1", 1);

// ��������Ϊ�Ѷ�:
if ($line) {
	$db->query("update sys_message set readtime=$time where id=$id limit 1");
}

//$title = cut($line["from_realname"]." ˵: ".$line["content"], 30, "..");

$title = "�� ".$line["from_realname"]." ���߽�̸";

?>
<html xmlns=http://www.w3.org/1999/xhtml>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<style>
</style>
<script language="javascript">
/*
function update_read() {
	var id = byid("id").value;
	var xm = new ajax();
	xm.connect("/http/set_message_read.php", "GET", "id="+id, update_read_do);
}

function update_read_do() {
	parent.load_box(0);
	parent.get_online(); //����������ʾ
}
*/

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

<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">��Ϣ����</td>
	</tr>
	<tr>
		<td class="left">���ݣ�</td>
		<td class="right" style="color:#005050; font-weight:bold;"><?php echo text_show($line["content"]); ?></td>
	</tr>
	<tr>
		<td class="left">�����ˣ�</td>
		<td class="right"><?php echo $line["from_realname"]; ?></td>
	</tr>
	<tr>
		<td class="left">ʱ�䣺</td>
		<td class="right"><?php echo date("Y-m-d H:i", $line["addtime"]); ?></td>
	</tr>
</table>

<div class="space"></div>

<form id="reply_form" method="POST">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">�ظ�</td>
	</tr>
	<tr>
		<td class="left">���Ļظ���</td>
		<td class="right"><textarea name="reply" id="reply" class="input" style="width:90%; height:60px;"></textarea></td>
	</tr>
</table>
<input type="hidden" name="from_id" value="<?php echo $id; ?>">
</form>

<div style="padding:20px 0 5px 0; text-align:center; ">
	<input type="submit" class="submit" onclick="send_reply()" value="���ͻظ�����" style="color:#009900;">&nbsp;��������&nbsp;
	<input type="submit" class="submit" onclick="close_page()" value="�ر�">
</div>

<input type="hidden" id="id" value="<?php echo $id; ?>">


</body>
</html>