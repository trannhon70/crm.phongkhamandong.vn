<?php
defined("ROOT") or exit;

$from_hid = intval($_REQUEST["hid"]);
if (!$from_hid) {
	exit("��������");
}
$h_line = $db->query("select * from hospital where id=$from_hid limit 1", 1);
$h_name = $h_line["name"];

if ($_POST) {
	$r = array();

	$r["url"] = str_replace("http://", "", $_POST["url"]);
	$r["site_name"] = $_POST["site_name"];

	$tm_config = array();
	$tm_config["engine"] = str_replace("\n", "|", str_replace("\r", "", $_POST["engine"]));
	$r["config"] = serialize($tm_config);

	if ($op == "add_site") {
		$r["hid"] = $from_hid;
		$r["h_name"] = $h_name;
		$r["addtime"] = time();
		$r["uid"] = $uid;
		$r["u_realname"] = $realname;
	}


	$sqldata = $db->sqljoin($r);
	if ($op == "add_site") {
		$sql = "insert into sites set $sqldata";
	} else {
		$sql = "update sites set $sqldata where id='$id' limit 1";
	}

	if ($db->query($sql)) {
		msg_box("�����ύ�ɹ�", "?", 1);
	} else {
		msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);
	}
}

$title = $pinfo["title"]." - ".($op == "add_site" ? "����¹���վ" : "�޸��¹���վ");

?>
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<script language="javascript">
function Check() {
	var oForm = document.mainform;

	if (oForm.url.value.length == 0) {
		msg_box("��������ַ��");
		oForm.url.focus();
		return false;
	}

	return true;
}
</script>
</head>

<body>
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $title; ?></span></div>
	<div class="headers_oprate"><button onclick="history.back()" class="button">����</button></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<div class="description">
	<div class="d_title">��ʾ��</div>
	<!-- <div class="d_item"></div> -->
</div>

<div class="space"></div>

<form name="mainform" action="" method="POST" onsubmit="return Check()">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">�¹���վ</td>
	</tr>
	<tr>
		<td class="left">ҽԺ���ƣ�</td>
		<td class="right"><?php echo $h_name; ?></td>
	</tr>
	<tr>
		<td class="left">��վ��ַ��</td>
		<td class="right"><input name="url" value="<?php echo $line["url"]; ?>" class="input" style="width:300px"> <span class="intro">��ַ������ www.021guke.com</span></td>
	</tr>
	<tr>
		<td class="left">��վ���ƣ�</td>
		<td class="right"><input name="site_name" value="<?php echo $line["site_name"]; ?>" class="input" style="width:300px"> <span class="intro">�ɲ���</span></td>
	</tr>
	<tr>
		<td class="left">�������棺</td>
		<td class="right"><textarea class="input" name="engine" style="width:300px; height:120px; vertical-align:middle;"><?php echo str_replace("|", "\r\n", $site_config["engine"]); ?></textarea> <span class="intro">һ��һ��</span></td>
	</tr>
</table>

<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">
<input type="hidden" name="hid" value="<?php echo $from_hid; ?>">
<input type="hidden" name="back_url" value="<?php echo $_SERVER["HTTP_REFERER"]; ?>">

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
</body>
</html>