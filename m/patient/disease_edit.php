<?php
/*
// - ����˵�� : ҽԺ�������޸�
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-01 00:40
*/

if ($_POST) {
	$record = array();
	$record["name"] = $_POST["name"];

	$s2 = array();
	if ($_POST["xiangmu"] != '') {
		$s = explode(" ", $_POST["xiangmu"]);
		foreach ($s as $k) {
			if (trim($k) != '') {
				$s2[] = trim($k);
			}
		}
	}
	$record["xiangmu"] = implode(" ", $s2);

	$record["intro"] = $_POST["intro"];
	$record["sort"] = $_POST["sort"];


	if ($op == "add") {
		$record["hospital_id"] = $user_hospital_id;
		$record["addtime"] = time();
		$record["author"] = $username;
	}

	$sqldata = $db->sqljoin($record);
	if ($op == "add") {
		$sql = "insert into $table set $sqldata";
	} else {
		$sql = "update $table set $sqldata where id='$id' limit 1";
	}

	if ($db->query($sql)) {
		msg_box("�����ύ�ɹ�", "?", 1);
	} else {
		msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);
	}
}

$title = $op == "edit" ? "�޸ļ�������" : "����µļ���";

$hospital_list = $db->query("select id,name from ".$tabpre."hospital");
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
	if (oForm.name.value == "") {
		alert("�����롰�������ơ���"); oForm.name.focus(); return false;
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
	<div class="d_item">1.���뼲�����Ƽ���飨���ɲ����룩������ύ����</div>
</div>

<div class="space"></div>

<form name="mainform" action="" method="POST" onsubmit="return Check()">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">��������</td>
	</tr>
	<tr>
		<td class="left">�������ƣ�</td>
		<td class="right"><input name="name" value="<?php echo $line["name"]; ?>" class="input" size="30" style="width:200px"> <span class="intro">���Ʊ�����д</span></td>
	</tr>
	<tr>
		<td class="left">������Ŀ��</td>
		<td class="right"><textarea name="xiangmu" class="input" style="width:60%; height:80px; overflow:visible;"><?php echo $line["xiangmu"]; ?></textarea> <span class="intro">����ÿո����</span></td>
	</tr>
	<tr>
		<td class="left">������飺</td>
		<td class="right"><textarea name="intro"class="input"  style="width:60%; height:80px; overflow:visible;"><?php echo $line["intro"]; ?></textarea> <span class="intro">ѡ��</span></td>
	</tr>
	<tr>
		<td class="left">���ȶȣ�</td>
		<td class="right"><input name="sort" value="<?php echo $line["sort"]; ?>" class="input" size="30" style="width:100px"> <span class="intro">Խ��Խ���ȣ���ֵ�����</span></td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
</body>
</html>