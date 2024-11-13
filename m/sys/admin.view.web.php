<?php defined("ROOT") or exit("Error."); ?>
<html xmlns=http://www.w3.org/1999/xhtml>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<style>
body {padding:10px; margin:0; }
.button_line {padding-bottom:3px; }
</style>
</head>

<body>
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $title; ?></span></div>
	<div class="headers_oprate"></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<table width="100%" class="edit">
	<tr>
		<td class="left">��¼����</td>
		<td class="right"><b><?php echo $user["name"]; ?></b></td>
	</tr>
	<tr>
		<td class="left">��ʵ������</td>
		<td class="right"><?php echo $user["realname"]; ?></td>
	</tr>
	<tr>
		<td class="left">����ҽԺ��</td>
		<td class="right"><?php echo $user["hs_str"]; ?></td>
	</tr>

	<tr>
		<td class="left">Ȩ�ޣ�</td>
		<td class="right">
<?php
if ($user["powermode"] == 2) {
	$ch_data = $db->query("select * from sys_character where id='".$user["character_id"]."' limit 1", 1);
	echo $ch_data["name"];
}
?>
		</td>
	</tr>

	<tr>
		<td class="left">�绰��</td>
		<td class="right"><?php echo $user["phone"]; ?></td>
	</tr>
	<tr>
		<td class="left">�ֻ���</td>
		<td class="right"><?php echo $user["mobile"]; ?></td>
	</tr>
	<tr>
		<td class="left">QQ��</td>
		<td class="right"><?php echo $user["qq"]; ?></td>
	</tr>
	<tr>
		<td class="left">E-Mail��</td>
		<td class="right"><?php echo $user["email"]; ?></td>
	</tr>
	<tr>
		<td class="left">���˼�飺</td>
		<td class="right"><?php echo $user["intro"]; ?></td>
	</tr>

</table>

<div class="button_line"><button onclick="parent.load_box(0)" class="buttonb">�ر�</button></div>
</body>
</html>