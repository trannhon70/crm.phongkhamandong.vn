<?php
// --------------------------------------------------------
// - ���� : view ģ��
// - ���� : ��ҽս�� 
// - ʱ�� : 2013-05-20 11:34
// --------------------------------------------------------

// ����Ƿ��������
if (!$username) {
	exit("This page can not directly opened from browser...");
}

// �������
if (!$viewdata) {
	exit("Wrong viewdata...");
}

if ($title == "") {
	$title = "�鿴";
}
?>
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
</head>

<body>
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $title; ?></span></div>
	<div class="headers_oprate"><button onClick="history.back()" class="button">����</button></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<table width="100%" class="edit">
<?php foreach ($viewdata as $k => $v) { ?>
	<tr>
		<td class="left"><?php echo $v[0]; ?>��</td>
		<td class="right"><?php echo $v[1]; ?></td>
	</tr>
<?php } ?>
</table>

<div class="button_line">
	<input type="button" class="submit" onClick="history.back()" value="����">
</div>
</body>
</html>