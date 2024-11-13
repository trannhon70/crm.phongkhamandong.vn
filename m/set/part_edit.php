<?php
/*
//  ����˵�� : �����������޸�
*/

if ($_POST) {
	$r = array();
	if ($op == "add" && $_POST["part_id"] > 0) {
		$r["id"] = $ptid = intval($_POST["part_id"]);

		// ���ID�Ƿ��ظ�:
		$is_old = $db->query("select count(*) as count from $table where id=$ptid", 1, "count");
		if ($is_old > 0) {
			exit_html("�Բ���, ID�����е��ظ�,�뷵��������д! ");
		}
		// end ���ID�ظ�
	}
	$r["name"] = $_POST["name"];
	$r["pid"] = $_POST["parent_part_id"];

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

	//exit($sql);

	if ($db->query($sql)) {
		// ��������:
		if ($op == "edit" && $r["id"]) {
			$db->query("update $table set pid=".$r["id"]." where pid=".$id."");
		}
		msg_box("���������ύ�ɹ�", "?", 1);
	} else {
		msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);
	}
}


$title = $op == "edit" ? "�޸Ĳ��Ŷ���" : "����µĲ���";

//$part_list = $db->query("select * from ".$tabpre."sys_part");

$part_list = get_part_list('array');

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
		alert("�����롰�������ơ���");
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
	<div class="headers_title"><span class="tips"><?php echo $title; ?></span></div>
	<div class="headers_oprate"><button onclick="history.back()" class="button">����</button></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<div class="description">
	<div class="d_title">��ʾ��</div>
	<div class="d_item">1.�����벿�����ƣ��ұ���ָ�����������ĸ�����</div>
	<div class="d_item">2.����֮��Ĺ����ϵ��Ӧ�õ��ĵ����ȵĹ����У������׼ȷ���ã��Ҳ�Ӧ�����޸�</div>
</div>

<div class="space"></div>

<form name="mainform" action="" method="POST" onsubmit="return Check()">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">��������</td>
	</tr>

<?php if ($op == "add") { ?>
	<!-- ֻ�����ʱ����ָ��һ��ID -->
	<tr>
		<td class="left">����ID��</td>
		<td class="right"><input name="part_id" value="<?php echo $line["id"]; ?>" class="input" style="width:80px"> <span class="intro">���Բ�ָ����ϵͳ�Զ����䣻���ָ������ȷ�ϲ��ܺ����е��ظ������ύ�ɹ������޸�</span></td>
	</tr>
<?php } ?>

	<tr>
		<td class="left">�������ƣ�</td>
		<td class="right"><input name="name" value="<?php echo $line["name"]; ?>" class="input" style="width:200px"> <span class="intro">���Ʊ�����д</span></td>
	</tr>
	<tr>
		<td class="left">�ϼ����ţ�</td>
		<td class="right">
			<select name="parent_part_id" class="combo">
			<option value="0" style="color:gray">-û���ϼ�����-</option>
			<?php echo list_option($part_list, 'id', 'name', $line["pid"]); ?>
			</select>

			<span class="intro">�ϼ���������</span>
		</td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
</body>
</html>