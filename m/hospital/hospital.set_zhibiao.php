<?php
defined("ROOT") or exit;

if ($_POST) {

	// �������Ѿ����أ����壩��������ֵ���ᶪʧ
	$ym = $_POST["ym"];
	if (strlen($ym) != 6) {
		exit_html("�·ݲ���ȷ");
	}
	$line["config"][$ym]["jiangli_jishu"] = intval($_POST["jiangli_jishu"]);
	$line["config"][$ym]["jiangli_zhibiao"] = intval($_POST["jiangli_zhibiao"]);
	$line["config"][$ym]["jiuzhen_mubiao"] = intval($_POST["jiuzhen_mubiao"]);

	$config = serialize($line["config"]);

	$sql = "update $table set config='$config' where id='$id' limit 1";
	if ($db->query($sql)) {
		msg_box("�ύ�ɹ�", "?op=set_zhibiao&id=".$id."&ym=".$ym, 1);
	} else {
		msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);
	}
}

$ym_array = array();
for ($i = 3; $i >= -6; $i--) {
	$d = strtotime(($i>=0 ? "+" : "").$i." month");
	$ym_array[date("Ym", $d)] = date("Y��m��", $d);
}

if (!isset($_GET["ym"])) {
	$_GET["ym"] = date("Ym");
}

?>
<html>
<head>
<title><?php echo $pinfo["title"]." - "."�޸�ָ������"; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<script language="javascript">
function Check() {
	var oForm = document.mainform;
	return true;
}
</script>
</head>

<body>
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $pinfo["title"]." - "."�޸�ָ������"; ?></span></div>
	<div class="headers_oprate"><button onclick="location='?op=edit&id=<?php echo $id; ?>'" class="button">����</button></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<!-- <div class="description">
	<div class="d_title">��ʾ��</div>
	<div class="d_item">1.ѡ�����ڣ���дָ�����ݣ��ύ����</div>
</div>
<div class="space"></div> -->

<form method="GET">
<div style="margin:40px 0 20px 0px; ">
	<b>�������·ݣ�</b>
	<select class="combo" name="ym" onchange="this.form.submit()">
		<option value="" style="color:gray">-��ѡ��-</option>
		<?php echo list_option($ym_array, "_key_", "_value_", $_GET["ym"]); ?>
	</select> &nbsp;
	<span class="intro">����ѡ��Ҫ���õ��·ݣ�ѡ��󣬱�ҳ�潫���¼���</span>
</div>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">
</form>

<?php if ($_GET["ym"]) { ?>
<form name="mainform" action="" method="POST" onsubmit="return Check()">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">��������</td>
	</tr>

	<tr>
		<td class="left">����������</td>
		<td class="right"><input name="jiangli_jishu" value="<?php echo $line["config"][$_GET["ym"]]["jiangli_jishu"]; ?>" class="input" style="width:80px"> <span class="intro">����д��������</span></td>
	</tr>
	<tr>
		<td class="left">����ָ�꣺</td>
		<td class="right"><input name="jiangli_zhibiao" value="<?php echo $line["config"][$_GET["ym"]]["jiangli_zhibiao"]; ?>" class="input" style="width:80px"> <span class="intro">����д����ָ��</span></td>
	</tr>
	<tr>
		<td class="left">Ŀ����</td>
		<td class="right"><input name="jiuzhen_mubiao" value="<?php echo $line["config"][$_GET["ym"]]["jiuzhen_mubiao"]; ?>" class="input" style="width:80px"> <span class="intro">����дĿ�����</span></td>
	</tr>
</table>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">
<input type="hidden" name="ym" value="<?php echo $_GET["ym"]; ?>">
<input type="hidden" name="linkinfo" value="<?php echo $linkinfo; ?>">
<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
<?php } ?>

</body>
</html>