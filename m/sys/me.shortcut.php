<?php

/*

// - ����˵�� : ��������Ա���˵Ŀ�ݷ�ʽ�˵�

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2006-12-16 01:13

*/

require "../../core/core.php";



if (!$uid) {

	exit_html("����ѡ���ݷ�ʽ...");

}



if ($_POST) {

	$theMenu = "";

	foreach ($_POST as $name => $value) {

		list($left, $right) = explode("_", $name);

		if ($left == "item") {

			$theMenu .= (strlen($theMenu)>0 ? "," : "") . $right;

		}

	}

	if ($db->query("update sys_admin set shortcut='$theMenu' where name='$username' limit 1")) {

		update_main_frame();

		exit;

		//msg_box("��ݷ�ʽ�޸ĳɹ�", "back", 1);

	} else {

		msg_box("�ܱ�Ǹ�������ύʧ�ܣ����Ժ����ԡ�", "back", 1);

	}

}



?>

<html xmlns=http://www.w3.org/1999/xhtml>

<head>

<title>ѡ���ݷ�ʽ</title>

<meta http-equiv="Content-Type" content="text/html;charset=gb2312">

<link href="/res/base.css" rel="stylesheet" type="text/css">

<script src="/res/base.js" language="javascript"></script>

<style>

.my_short {float:left; width:150px; margin:3px; }

</style>

</head>



<body>

<!-- ͷ�� begin -->

<div class="headers">

	<div class="headers_title"><span class="tips">ѡ���ݷ�ʽ</span></div>

	<div class="headers_oprate"><input type="button" value="����" onclick="history.back()" class="button"></div>

</div>

<!-- ͷ�� end -->



<div class="space"></div>



<div class="description">

	<div class="d_title">��ʾ��</div>

	<li class="d_item">����ѡ�����Ŀ�ݲ˵�������������˵��������Ե�ӵ��������10������</li>

	<li class="d_item">���ȫ����ѡ������ʾϵͳĬ�ϵĿ�ݲ˵�������ϵͳ�ܹ���Ա�ڲ˵�����ʱ�趨��</li>

	<li class="d_item">�����Ҫ�رա���ݷ�ʽ��������ȡ�����ҵĺ�̨��->��ѡ�����á�->������ѡ�->����ʾ����ݷ�ʽ�������Ĺ�ѡ</li>

	<li class="d_item">�޸ĳɹ���ϵͳ������������ʾ</li>

</div>



<div class="space"></div>



<form method="POST" name="mainform">

<table width="100%" class="edit">

	<tr>

		<td colspan="2" class="head">��ѡ���ݷ�ʽ��</td>

	</tr>



<?php

$aitems = explode(",", $shortcut);

$perline = 4;

$tdwidth = intval(100 / $perline) . "%";



$menu_data = array();

$tmp_data = $db->query("select id,title,link from sys_menu order by sort");

foreach ($tmp_data as $tmp_line) {

	$menu_data[$tmp_line["id"]] = $tmp_line;

}



list($m_stru, $m_power) = $power->parse_menu($usermenu);



foreach ($m_stru as $mid => $items) {

	if (count($items) > 0) {

?>

	<tr>

		<td class="left"><b><?php echo $menu_data[$mid]["title"]; ?></b>��</td>

		<td class="right">

<?php

foreach ($items as $v) {

	$title = $menu_data[$v]["title"];

?>

			<div class="my_short"><input type='checkbox' class='check' name='item_<?php echo $v; ?>' id='item_<?php echo $v; ?>'<?php echo (in_array($v, $aitems) ? " checked" : ""); ?>><label for='item_<?php echo $v; ?>'><?php echo $title; ?></label></div>

<?php

}

?>

		</td>

	</tr>

<?php

	}

}

?>

</table>



<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>

<input type="hidden" name="formsubmit" value="1">

</form>



<div class="space"></div>

</body>

</html>