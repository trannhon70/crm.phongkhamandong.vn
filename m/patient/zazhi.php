<?php

/* --------------------------------------------------------

// ˵��: ��־

// ����: ��ҽս�� 

// ʱ��: 2010-03-13 12:28

// ----------------------------------------------------- */

require "../../core/core.php";

$title = "��־ͳ������";



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



$num = array();



foreach ($arr_time as $tname => $t) {

	list($tb, $te) = $t;



	$num[$tname]["all"] = $db->query("select count(*) as count from $table where status=1 and media_from='��־' and order_date>=$tb and order_date<$te", 1, "count");



	if (count($keshi) > 0) {

		foreach ($keshi as $k => $v) {

			$num[$tname][$v] = $db->query("select count(*) as count from $table where status=1 and media_from='��־' and order_date>=$tb and order_date<$te and depart=$k", 1, "count");

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

	<div class="headers_oprate"><button onclick="history.back()" class="button">����</button></div>

</div>

<!-- ͷ�� end -->



<div class="space"></div>



<?php foreach ($arr_time as $tname => $t) { ?>

<div style="float:left; margin-top:40px; padding-left:30px;">

<table width="200" class="edit">

	<tr>

		<td colspan="2" class="head"><?php echo $tname; ?></td>

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

	������ʾý����Դ����־�ĵ�Ժ���� &nbsp; ����ͳ��ʱ��: <b><?php echo date("Y-m-d H:i"); ?></b> &nbsp;

	<button onclick="location.reload()" class="buttonb">����ͳ��</button>

</div>





</body>

</html>