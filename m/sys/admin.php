<?php

/*

// - ����˵�� : admin.php

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-05-11 23:16

*/

require "../../core/core.php";

$table = "sys_admin";



if (!$debug_mode && !$uinfo["part_admin"]) {

	exit_html("û�д�Ȩ��..."); //�����ǲ��Ź���Ա

}



// �����Ĵ���:

$op = $_REQUEST["op"];

if ($op) {

	include "admin.op.php";

}



$sqlwhere = "1";



// ��Ա��ȡ����:

if (!$debug_mode && $username != "admin") {

	$hd_s = array();

	foreach ($hospital_ids as $v) {

		$hd_s[] = ','.$v.',';

	}

	$hd = implode("", $hd_s);

	$sqlwhere .= " and ('$hd' like concat('%,',replace(hospitals,',',',%,'),',%') or hospitals='')";

}



// ����:

if ($key = $_GET["key"]) {

	$sqlwhere .= " and (name like '%{$key}%' or realname like '%{$key}%')";

}



// �ų�

$sqlwhere .= " and name!='$username'";





$group_type = array(1 => "����", 2 => "Ȩ��", 3 => "ҽԺ", 4 => "��������", 5 => "���õ��˺�", 6 => "�����û�");

$cur_group = intval($_SESSION["admin_group_type"]);

if (!$cur_group) {

	$cur_group = $_SESSION["admin_group_type"] = 1;

}





// ------------- ҳ�濪ʼ ---------------

?>

<html>

<head>

<title><?php echo $pinfo["title"]; ?></title>

<meta http-equiv="Content-Type" content="text/html;charset=gb2312">

<link href="/res/base.css" rel="stylesheet" type="text/css">

<script src="/res/base.js" language="javascript"></script>

<style>

.admin_list {margin-left:10px; margin-top:10px; }

#rec_part, #rec_user {margin-top:6px; }

.rub {width:180px; float:left; }

.rub input {float:left; }

.rub a {display:block; float:left; padding-top:2px; }

.rgp {clear:both; margin:10px 0 5px 0; font-weight:bold; }

.group_select {margin-top:10px; margin-bottom:0px; text-align:center; }

</style>



<script language="javascript">

function ucc(o) {

	o.parentNode.getElementsByTagName("a")[0].style.color = o.checked ? "red" : "";

}

function sd(id) {

	var ss = byid("g_"+id).getElementsByTagName("INPUT");

	for (var i=0; i<ss.length; i++) {

		ss[i].checked = !ss[i].checked;

	}

	return false;

}



function ld(id) {

	parent.load_src(1,'m/sys/admin.php?op=edit&id='+id);

	return false;

}



function del() {

	if (confirm("���ȷ��Ҫɾ����Щ��Ա������ؽ���������")) {

		byid("op_value").value = "delete";

		byid("mainform").submit();

	}

}



function close_account() {

	byid("op_value").value = "close";

	byid("mainform").submit();

}



function open_account() {

	byid("op_value").value = "open";

	byid("mainform").submit();

}



function set_ch() {

	byid("new_ch").style.display = byid("new_ch").style.display == "none" ? "inline" : "none";

}



function submit_ch() {

	if (byid("ch_id").value > 0) {

		byid("op_value").value = "set_ch";

		byid("mainform").submit();

	} else {

		alert("��ѡ��Ҫ���õ�Ȩ�ޣ�");

		byid("ch_id").focus();

		return false;

	}

}

</script>

</head>



<body>

<!-- ͷ�� begin -->

<div class="headers">

	<div class="headers_title" width="30%"><span class="tips">ϵͳ��Ա����</span></div>

	<div class="header_center" width="40%">

		<?php echo $power->show_button("add"); ?>&nbsp;&nbsp;

	</div>

	<div class="headers_oprate"><button onclick="history.back()" class="button" title="������һҳ">����</button></div>

</div>

<!-- ͷ�� end -->



<div class="space"></div>

<div class="group_select">

	<b>���з�ʽ��</b>

	<form method="GET" style="display:inline;">

		<select name="group" class="combo" onchange="this.form.submit()">

			<?php echo list_option($group_type, "_key_", "_value_", $cur_group); ?>

		</select>

		<input type="hidden" name="op" value="change_group_type">

		<input type="hidden" name="key" value="<?php echo $_GET["key"]; ?>">

	</form>&nbsp;&nbsp;



	<b>�������֣�</b>

	<form method="GET" style="display:inline;">

		<input name="key" value="<?php echo $_GET["key"]; ?>" class="input" size="12">

		<input type="submit" class="button" value="����" style="font-weight:bold;">

		<input type="submit" class="button" onclick="this.form.key.value=''" value="����">

	</form>

</div>



<div class="space"></div>

<form method="POST" name="mainform" id="mainform" action="?">

<div class="admin_list">

	<div id="rec_user">

<?php

if ($cur_group == 1) { //����

	$id_name = $db->query("select id,name,if(id=9,0,id) as sort from sys_part order by sort", "id", "name");

	foreach ($id_name as $k => $v) {

		$all_admin = $db->query("select id,name,realname from sys_admin where $sqlwhere and isshow=1 and id!='$uid' and part_id='$k' order by realname", "id");

		echo '<div class="rgp">'.$v.'('.count($all_admin).')'.' <a href="#" onclick="return sd('.$k.')">ȫѡ</a></div>';

		echo '<div id="g_'.$k.'">';

		foreach ($all_admin as $a => $b) {

			echo '<div class="rub"><input type="checkbox" name="uid[]" value="'.$a.'" onclick="ucc(this)"><a href="#" onclick="return ld('.$b["id"].')">'.$b["realname"]." (".$b["name"].") ".'</a></div>';

		}

		echo '</div>';

	}

} else if ($cur_group == 2) { //��ɫ

	$id_name = $db->query("select id,concat(name,' (',author,')') as name from sys_character", "id", "name");

	foreach ($id_name as $k => $v) {

		$all_admin = $db->query("select id,name,realname from sys_admin where $sqlwhere and isshow=1 and id!='$uid' and character_id='$k' order by realname", "id");

		echo '<div class="rgp">'.$v.'('.count($all_admin).')'.' <a href="#" onclick="return sd('.$k.')">ȫѡ</a></div>';

		echo '<div id="g_'.$k.'">';

		foreach ($all_admin as $a => $b) {

			echo '<div class="rub"><input type="checkbox" name="uid[]" value="'.$a.'" onclick="ucc(this)"><a href="#" onclick="return ld('.$b["id"].')">'.$b["realname"]." (".$b["name"].") ".'</a></div>';

		}

		echo '</div>';

	}

} else if ($cur_group == 3) { //ҽԺ

	$allow_ids = implode(",", $hospital_ids);

	$id_name = $db->query("select id,name from hospital where id in ($allow_ids) order by sort desc,id asc", "id", "name");

	foreach ($id_name as $k => $v) {

		$all_admin = $db->query("select id,name,realname from sys_admin where $sqlwhere and isshow=1 and id!='$uid' and concat(',',hospitals,',') like '%,".$k.",%' order by realname", "id");

		echo '<div class="rgp">'.$v.'('.count($all_admin).')'.' <a href="#" onclick="return sd('.$k.')">ȫѡ</a></div>';

		echo '<div id="g_'.$k.'">';

		foreach ($all_admin as $a => $b) {

			echo '<div class="rub"><input type="checkbox" name="uid[]" value="'.$a.'" onclick="ucc(this)"><a href="#" onclick="return ld('.$b["id"].')">'.$b["realname"]." (".$b["name"].") ".'</a></div>';

		}

		echo '</div>';

	}

} else if ($cur_group == 4) { //����

	$id_name = array(1 => "��������", 0 => "��ͨ��Ա(������)");

	foreach ($id_name as $k => $v) {

		$all_admin = $db->query("select id,name,realname from sys_admin where $sqlwhere and isshow=1 and id!='$uid' and part_admin='$k' order by realname", "id");

		echo '<div class="rgp">'.$v.'('.count($all_admin).')'.' <a href="#" onclick="return sd('.$k.')">ȫѡ</a></div>';

		echo '<div id="g_'.$k.'">';

		foreach ($all_admin as $a => $b) {

			echo '<div class="rub"><input type="checkbox" name="uid[]" value="'.$a.'" onclick="ucc(this)"><a href="#" onclick="return ld('.$b["id"].')">'.$b["realname"]." (".$b["name"].") ".'</a></div>';

		}

		echo '</div>';

	}

} else if ($cur_group == 5) { //����

	$id_name = array(0 => "���õ��˺�", 1 => "��ͨ���˺�");

	foreach ($id_name as $k => $v) {

		$all_admin = $db->query("select id,name,realname,isshow from sys_admin where $sqlwhere and isshow='$k' and id!='$uid' order by realname", "id");

		echo '<div class="rgp">'.$v.'('.count($all_admin).')'.' <a href="#" onclick="return sd('.$k.')">ȫѡ</a></div>';

		echo '<div id="g_'.$k.'">';

		foreach ($all_admin as $a => $b) {

			echo '<div class="rub"><input type="checkbox" name="uid[]" value="'.$a.'" onclick="ucc(this)"><a href="#" onclick="return ld('.$b["id"].')">'.$b["realname"]." (".$b["name"].") ".($b["isshow"]!=1 ? ' <font color="red">��</font>' : '').'</a></div>';

		}

		echo '</div>';

	}

} else if ($cur_group == 6) { //����

	$id_name = array(1 => "����", 0 => "������");

	foreach ($id_name as $k => $v) {

		$all_admin = $db->query("select id,name,realname,isshow from sys_admin where $sqlwhere and isshow=1 and online='$k' and id!='$uid' order by realname", "id");

		echo '<div class="rgp">'.$v.'('.count($all_admin).')'.' <a href="#" onclick="return sd('.$k.')">ȫѡ</a></div>';

		echo '<div id="g_'.$k.'">';

		foreach ($all_admin as $a => $b) {

			echo '<div class="rub"><input type="checkbox" name="uid[]" value="'.$a.'" onclick="ucc(this)"><a href="#" onclick="return ld('.$b["id"].')">'.$b["realname"]." (".$b["name"].") ".'</a></div>';

		}

		echo '</div>';

	}

}

?>

		<div class="clear"></div>

	</div>

</div>

<input type="hidden" name="op" id="op_value" value="">



<div class="space" style="height:20px;"></div>



<b>&nbsp;&nbsp;������</b>

<button onclick="select_all()" class="button">ȫѡ</button>&nbsp;

<button onclick="unselect()" class="button">��ѡ</button>&nbsp;



<b>&nbsp;&nbsp;��ѡ��Ա��</b>

<?php if ($debug_mode || $uinfo["part_id"] == 9) { ?>

<button onclick="del()" class="button">ɾ��</button>&nbsp;

<?php } ?>

<button onclick="close_account()" class="buttonb">�ر��ʻ�</button>&nbsp;

<button onclick="open_account()" class="buttonb">��ͨ�ʻ�</button>&nbsp;

<button onclick="set_ch()" class="buttonb">����Ȩ��</button>&nbsp;

<span id="new_ch" style="display:none;">

	<select name="ch_id" id="ch_id" class="combo">

		<option value="" style="color:gray">-��ѡ����Ȩ��-</option>

<?php

$id_name = $db->query("select id,concat(name,' (',author,')') as name from sys_character", "id", "name");

echo list_option($id_name, "_key_", "_value_");

?>

	</select>&nbsp;

	<button onclick="submit_ch()" class="button">ȷ��</button>

</span>



<div class="space" style="height:20px;"></div>

</form>



</body>

</html>