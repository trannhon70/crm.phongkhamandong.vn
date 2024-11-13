<?php

/* --------------------------------------------------------

// ˵��: ������ͳ������

// ����: ��ҽս�� 

// ʱ��: 2010-04-06 14:56

// ----------------------------------------------------- */

require "../../core/core.php";

$title = "����ͳ������";



$media_from_array = array();

$media_from_array[] = "����";

$media_from_array[] = "�绰";

$media_from_array[] = "����";

$media_from_array[] = "�г�";

$media_from_array[] = "����";

$media_from_array[] = "���ѽ���";

$media_from_array[] = "·��";

$media_from_array[] = "����";

$media_from_array[] = "��̨";

$media_from_array[] = "����";

$media_from_array[] = "·��";

$media_from_array[] = "����";

$media_from_array[] = "���";

$media_from_array[] = "��ֽ";

$media_from_array[] = "��־";

$media_from_array[] = "����";



$status_array = array(0 => "����", 1 => "�ѵ�", 2 => "δ��", 3=> 'ԤԼδ��');



if ($user_hospital_id == 0) {

	exit_html("�Բ���û��ѡ��ҽԺ������ִ�иò�����");

}

$table = "patient_".$user_hospital_id;



// ʱ����޶���:

$time_today_begin = mktime(0,0,0);

$time_today_end = $time_today_begin + 24*3600;

$time_yesterday_begin = $time_today_begin - 24*3600;

$time_this_month_begin = mktime(0,0,0,date("m"),1);

$time_this_month_end = strtotime("+1 month", $time_this_month_begin);

$time_last_month_begin = strtotime("-1 month", $time_this_month_begin);





// ���п���:

$keshi = $db->query("select id,name from depart where hospital_id=$user_hospital_id", "id", "name");



// ʱ�䶨��

$arr_time = array(

	"����" => array($time_today_begin, $time_today_end),

	"����" => array($time_yesterday_begin, $time_today_begin),

	"����" => array($time_this_month_begin, $time_this_month_end)

);



$where = $tips = array();



// ý����Դ:

if ($_GET["media"]) {

	$where[] = "media_from='".$_GET["media"]."'";

	$tips[] = $_GET["media"];

}



// �Ƿ�Ժ

if (!isset($_GET["status"])) {

	$_GET["status"] = 1; //Ĭ�ϲ�ѯ�ѵ���

}

if ($_GET["status"]) {

	if ($_GET["status"] == "1") {

		$where[] = "status=1";

	}

	if ($_GET["status"] == "2") {

		$where[] = "status!=1";

	}

	$tips[] = $status_array[$_GET["status"]];

}

$sql_where = count($where) ? ("and ".implode(" and ", $where)) : "";



$tips = implode(" ", $tips);



$num = array();



foreach ($arr_time as $tname => $t) {

	list($tb, $te) = $t;



	$num[$tname]["all"] = $db->query("select count(*) as count from $table where order_date>=$tb and order_date<$te $sql_where", 1, "count");



	if (count($keshi) > 0) {

		foreach ($keshi as $k => $v) {

			$num[$tname][$v] = $db->query("select count(*) as count from $table where order_date>=$tb and order_date<$te and depart=$k $sql_where", 1, "count");

		}

	}

}





?>

<html>

<head>

<title><?php echo $title; ?></title>

<meta http-equiv="Content-Type" content="text/html;charset=gbk">

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



<div style="padding-left:30px; padding-top:20px;">

	<form method="GET">

	ɸѡ������

	<select class="combo" name="media">

		<option value="" style="color:gray">-ý����Դ-</option>

		<?php echo list_option($media_from_array, "_value_", "_value_", $_GET["media"]); ?>

	</select>&nbsp;

	<select class="combo" name="status">

		<option value="0" style="color:gray">-��Ժ���-</option>

		<?php echo list_option($status_array, "_key_", "_value_", $_GET["status"]); ?>

	</select>&nbsp;

	<input type="submit" class="button" value="ȷ��" />

	</form>

</div>



<?php foreach ($arr_time as $tname => $t) { ?>

<div style="float:left; margin-top:40px; padding-left:30px;">

<table width="200" class="edit">

	<tr>

		<td colspan="2" class="head"><?php echo $tname." ".$tips; ?></td>

	</tr>

	<tr>

		<td class="left" style="width:50%">�ܹ���</td>

		<td class="right"><b><?=$num[$tname]["all"]?></b></td>

	</tr>



<!-- ��������Ժ���� -->

<?php foreach ($keshi as $k => $v) { ?>

	<tr>

		<td class="left" style="width:30%"><?=$v?>��</td>

		<td class="right"><b><?=$num[$tname][$v]?></b></td>

	</tr>

<?php } ?>



</table>

</div>

<?php } ?>



<div class="clear"></div>



<div style="margin-top:40px; padding-left:40px; ">

	������ʾ������ͳ�Ƶĵ�Ժ���� &nbsp; ����ͳ��ʱ��: <b><?php echo date("Y-m-d H:i"); ?></b><br>

	<br>

	<button onClick="location.reload()" class="buttonb">����ͳ��</button>

</div>





</body>

</html>