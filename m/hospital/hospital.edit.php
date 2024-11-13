<?php
defined("ROOT") or exit;

if ($_POST) {
	$r = array();
	$r["name"] = $_POST["name"];
	$r["intro"] = $_POST["intro"];
	$r["sort"] = $_POST["sort"];

	// �������Ѿ����أ����壩��������ֵ���ᶪʧ
	$line["config"]["�����շ���Ŀ"] = str_replace("\n", "|", str_replace("\r", "", $_POST["menzhen_fei"]));
	$line["config"]["סԺ�շ���Ŀ"] = str_replace("\n", "|", str_replace("\r", "", $_POST["zhuyuan_fei"]));

	$line["config"][date("Ym")]["jiangli_jishu"] = intval($_POST["jiangli_jishu"]);
	$line["config"][date("Ym")]["jiangli_zhibiao"] = intval($_POST["jiangli_zhibiao"]);
	$line["config"][date("Ym")]["jiuzhen_mubiao"] = intval($_POST["jiuzhen_mubiao"]);

	$r["config"] = serialize($line["config"]);

	if ($op == "add") {
		$r["addtime"] = time();
		$r["author"] = $username;
	}

	$sqldata = $db->sqljoin($r);
	if ($op == "edit") {
		$sql = "update $table set $sqldata where id='$id' limit 1";
	} else {
		$sql = "insert into $table set $sqldata";
	}

	if ($hid = $db->query($sql)) {
		$hid = ($op == "edit" ? $id : $hid);
		create_patient_table($hid);
		msg_box("�����ύ�ɹ�", "?", 1);
	} else {
		msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);
	}
}

?>
<html>
<head>
<title><?php echo $pinfo["title"]." - ".($op == "add" ? "����" : "�޸�"); ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<script language="javascript">
function Check() {
	var oForm = document.mainform;
	if (oForm.name.value == "") {
		alert("�����롰ҽԺ���ơ���");
		oForm.name.focus();
		return false;
	}
	return true;
}
</script>
</head>

<body>
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $pinfo["title"]." - ".($op == "add" ? "����" : "�޸�"); ?></span></div>
	<div class="headers_oprate"><button onclick="history.back()" class="button">����</button></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<div class="description">
	<div class="d_title">��ʾ��</div>
	<div class="d_item">1.����ҽԺ���ƣ�����ύ����</div>
</div>

<div class="space"></div>

<form name="mainform" action="" method="POST" onsubmit="return Check()">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">ҽԺ����</td>
	</tr>
	<tr>
		<td class="left">ҽԺ���ƣ�</td>
		<td class="right"><input name="name" value="<?php echo $line["name"]; ?>" class="input" size="30" style="width:200px"> <span class="intro">���Ʊ�����д</span></td>
	</tr>
	<tr>
		<td class="left">ҽԺ��飺</td>
		<td class="right"><textarea class="input" name="intro" style="width:60%; height:80px; vertical-align:middle;"><?php echo $line["intro"]; ?></textarea> <span class="intro">ҽԺ��飬ѡ��</span></td>
	</tr>
	<tr>
		<td class="left">���ȶȣ�</td>
		<td class="right"><input name="sort" value="<?php echo $line["sort"]; ?>" class="input" style="width:80px"> <span class="intro">���ȶ�Խ��,����Խ��ǰ</span></td>
	</tr>
</table>

<div class="space"></div>

<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">ҽԺ����</td>
	</tr>
	<tr>
		<td class="left">�����շ���Ŀ��</td>
		<td class="right"><textarea class="input" name="menzhen_fei" style="width:200px; height:90px; vertical-align:middle;"><?php echo str_replace("|", "\r\n", $line["config"]["�����շ���Ŀ"]); ?></textarea> <span class="intro">�����շ���Ŀ��ÿ��һ��</span></td>
	</tr>
	<tr>
		<td class="left">סԺ�շ���Ŀ��</td>
		<td class="right"><textarea class="input" name="zhuyuan_fei" style="width:200px; height:90px; vertical-align:middle;"><?php echo str_replace("|", "\r\n", $line["config"]["סԺ�շ���Ŀ"]); ?></textarea> <span class="intro">סԺ�շ���Ŀ��ÿ��һ��</span></td>
	</tr>
	<tr>
		<td class="left">���½���������</td>
		<td class="right"><input name="jiangli_jishu" value="<?php echo $line["config"][date("Ym")]["jiangli_jishu"]; ?>" class="input" style="width:80px"> <span class="intro">���½�������(ֻ���ڱ����޸�)</span> &nbsp; <a href="?op=set_zhibiao&id=<?php echo $id; ?>"><b>�����������·�ָ�꡿</b></a></td>
	</tr>
	<tr>
		<td class="left">���½���ָ�꣺</td>
		<td class="right"><input name="jiangli_zhibiao" value="<?php echo $line["config"][date("Ym")]["jiangli_zhibiao"]; ?>" class="input" style="width:80px"> <span class="intro">���½���ָ��(ֻ���ڱ����޸�)</span></td>
	</tr>
	<tr>
		<td class="left">����Ŀ����</td>
		<td class="right"><input name="jiuzhen_mubiao" value="<?php echo $line["config"][date("Ym")]["jiuzhen_mubiao"]; ?>" class="input" style="width:80px"> <span class="intro">����Ŀ�����(ֻ���ڱ����޸�)</span></td>
	</tr>
</table>

<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">
<input type="hidden" name="linkinfo" value="<?php echo $linkinfo; ?>">

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
</body>
</html>