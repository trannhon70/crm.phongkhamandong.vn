<?php defined("ROOT") or exit("Error."); ?>
<html xmlns=http://www.w3.org/1999/xhtml>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<script language="javascript">
function set_MID_show(oSel) {
	var theValue = oSel.value;
	var theForm = document.hideform;
	var HtmlData = theValue == "0" ? theForm.menuid_select.value : theForm.menuid_input.value;
	var menuidTips = theValue == "0" ? "����˵���" : "�˵����ţ�";
	document.getElementById("menuid_area").innerHTML = HtmlData;
	document.getElementById("menuid_tips").innerHTML = menuidTips;
	document.getElementById("menu_detail").style.display = (theValue == "0" ? "block" : "none");
}

function init() {
	set_MID_show(document.mainform.type);
}

function check_data(f) {
	if (f.menuid.value == "") {
		msg_box("��ָ���˵����ţ�"); f.menuid.focus(); return false;
	}
	if (f.title.value == "") {
		msg_box("������˵����ƣ�"); f.title.focus(); return false;
	}
	return true;
}
</script>
</head>

<body onload="init()">
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $title; ?></span></div>
	<div class="headers_oprate"><input type="button" value="����" onclick="history.back()" class="button"></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>
<div class="description">
	<div class="d_title">��ʾ��</div>
	<li class="d_item">�˵����ͷ���һ�������㣩�Ͷ������Ӳ˵������֡�һ���˵���ָ�����ӣ����ɷ������Ȩ�ޣ������˵����趨ϸ��Ȩ��</li>
	<li class="d_item">�����˵�����ѡ��������ĸ�һ���˵�֮�£�ͨ��ѡ���䡰����˵���ʵ��</li>
	<li class="d_item">������˵��Ĳ˵����Ų�����ͬ������˵����Ӳ˵��ı����Ӧ��ͬ�Ա�ʾ��Ϊͬһ����</li>
</div>

<div class="space"></div>
<form method='POST' name="mainform" onsubmit="return check_data(this)">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">�˵���Ҫ���ϣ�</td>
	</tr>
	<tr>
		<td class="left" style="color:red">�˵����ͣ�</td>
		<td class="right"><select name="type" class="combo" onchange="set_MID_show(this)">
		<option value="1"<?php echo (!$id || $line["type"] ? " selected" : ""); ?>>����˵�<?php echo ($line["type"] ? " *" : ""); ?></option>
		<option value="0"<?php echo (strlen($line["type"]) && $line["type"]==0 ? " selected" : ""); ?>>�Ӳ˵�<?php echo (strlen($line["type"]) && $line["type"]==0 ? " *" : ""); ?></option>
		</select> <span class="intro">����˵���ʾ�ڡ����˵���λ�ã��Ӳ˵���ʾ�����¼������</span>
		</td>
	</tr>
	<tr>
		<td class="left" id="menuid_tips" style="color:red"></td>
		<td class="right"><span id="menuid_area"></span>
		</td>
	</tr>
	<tr>
		<td class="left" style="color:red">�˵����ƣ�</td>
		<td class="right"><input name="title" size="20" maxlength="40" class="input" value="<?php echo $line["title"]; ?>"> <span class="intro">�˵���ʾ���ƣ�����</span></td>
	</tr>
	<tr>
		<td class="left">������ҳ�棺</td>
		<td class="right"><input name="link" size="40" maxlength="100" class="input" value="<?php echo $line["link"]; ?>"> <span class="intro">���˵������ֱ������ (����˵��ɲ���)</span></td>
	</tr>
	<tr>
		<td class="left">����˵����</td>
		<td class="right"><input name="tips" size="40" maxlength="100" class="input" value="<?php echo $line["tips"]; ?>"> <span class="intro">������д��ϸ˵�����Ǳ�����</span></td>
	</tr>
	<tr>
		<td class="left">����ֵ��</td>
		<td class="right"><input name="sort" size="8" maxlength="10" class="input" value="<?php echo $line["sort"]; ?>"> <span class="intro">����ֵ�Ĵ�С�����˵������д�������ģʽ�¿�������ϵͳ�Զ�����</span></td>
	</tr>
</table>

<div id="menu_detail" style="display:none">
<div class="space"></div>
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">�Ӳ˵���ϸ���壺</td>
	</tr>
	<tr>
		<td class="left">ÿҳ��ʾ������</td>
		<td class="right"><input name="pagesize" size="8" class="input" value="<?php echo $line["pagesize"]; ?>"> <span class="intro">�趨�б�ҳ��ÿҳ��ʾ��¼������</span></td>
	</tr>
	<tr>
		<td class="left">Ĭ�Ͽ�ݷ�ʽ��</td>
		<td class="right"><select name="shortcut" class="combo">
		<option value="1"<?php echo ($line["shortcut"] == 1 ? " selected" : ""); ?>>��ΪĬ�Ͽ�ݷ�ʽ</option>
		<option value="0"<?php echo ($line["shortcut"] == 0 ? " selected" : ""); ?>>����ΪĬ�Ͽ�ݷ�ʽ</option>
		</select> <span class="intro">������Աδ�趨��ר����ݷ�ʽʱ������ΪĬ�Ͽ�ݷ�ʽ������Щҳ�����ʾ</span></td>
	</tr>

<?php
	$cur_op = explode(",", $line["modules"]);
	foreach ($oprate as $op_code => $op_name) {
		$ischeck = in_array($op_code, $cur_op) ? 'checked' : '';
?>
	<tr>
		<td class="left"><?php echo $op_name; ?>���ܣ�</td>
		<td class="right">
			<input type="checkbox" name="oprate[]" value="<?php echo $op_code; ?>" class="check" <?php echo $ischeck; ?>>
			<label for="op_<?php echo $op_code; ?>">��<?php echo $op_name; ?>����</label>
			<span class="intro">op=<?php echo $op_code; ?></span>
		</td>
	</tr>

<?php
	}

	$other_op = array();
	foreach ($cur_op as $op_code) {
		if (!array_key_exists($op_code, $oprate)) {
			$other_op[] = $op_code;
		}
	}
	$other_op = implode(",", $other_op);
?>
	<tr>
		<td class="left">�������ܣ�</td>
		<td class="right">
			<input name="oprate[]" value="<?php echo $other_op; ?>" size="30" class="input"> <span class="intro">��������ƴ���, op=xxx, �����Сд���Ÿ���</span>
		</td>
	</tr>
</table>
</div>
<div class="button_line"><input type="submit" value="�ύ����" class="submit"></div>
<input type="hidden" name="id" value="<?php echo $id; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">
</form>
<form name="hideform">
<input type="hidden" name="menuid_select" value="<?php echo $SelectData; ?>">
<input type="hidden" name="menuid_input" value="<?php echo $InputData; ?>">
</form>

<div class="space"></div>

</body>
</html>