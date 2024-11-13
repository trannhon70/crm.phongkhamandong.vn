<?php

/* --------------------------------------------------------

// ˵��: ͼ�α���

// ����: ��ҽս�� 

// ʱ��: 2013-06-25 14:01

// ----------------------------------------------------- */

require "../../core/core.php";

check_power('', $pinfo) or msg_box("û�д�Ȩ��...", "back", 1);



if ($user_hospital_id == 0) {

	exit_html("�Բ���û��ѡ��ҽԺ������ִ�иò�����");

}



$table = "patient_".$user_hospital_id;



// ���������µ�ͳ��:

$timebegin = $_GET["month"] ? $_GET["month"] : mktime(0,0,0,date("m"),0);

$timeend = strtotime("+1 month", $timebegin);

$list_2 = $db->query("select id,addtime from $table where part_id=2 and addtime>=$timebegin and addtime<$timeend");

$list_3 = $db->query("select id,addtime from $table where part_id=3 and addtime>=$timebegin and addtime<$timeend");



$a1 = $b1 = array();

foreach ($list_2 as $li) {

	$a1[date("Y-m-d 0:0:0", $li["addtime"])] += 1;

}

foreach ($list_3 as $li) {

	$b1[date("Y-m-d 0:0:0", $li["addtime"])] += 1;

}



$a_max = $b_max = 0;

$a = $b = array();

foreach ($a1 as $k => $v) {

	$a[] = '['.strtotime($k).'000,'.$v.']';



	if ($a_max < $v) {

		$a_max = $v;

	}

}

foreach ($b1 as $k => $v) {

	$b[] = '['.strtotime($k).'000,'.$v.']';

	if ($b_max < $v) {

		$b_max = $v;

	}

}



$title = '����ԤԼ��������ͼ';

?>

<html>

<head>

<title></title>

<meta http-equiv="Content-Type" content="text/html;charset=gb2312">

<link href="/res/base.css" rel="stylesheet" type="text/css">

<script src="/res/base.js" language="javascript"></script>



<!--[if IE]><script language="javascript" type="text/javascript" src="lib/excanvas.js"></script><![endif]-->

<script language="javascript" type="text/javascript" src="lib/jquery.js"></script>

<script language="javascript" type="text/javascript" src="lib/jquery.flot.js"></script>



<script language="javascript">

$(function () {



	var a = [<?php echo implode(', ', $a); ?>];

	var b = [<?php echo implode(', ', $b); ?>];



    var plot = $.plot($("#placeholder"),

           [

		   { data: a, label: "����"}

		   ],

		   {

			   xaxis: { mode: 'time' },

			   lines: { show: true },

			   points: { show: true },

			   selection: { mode: "xy" },

			   grid: { hoverable: true, clickable: true },

			   yaxis: { min: 0, max: <?php echo max($a_max,$b_max); ?> }

		});



    var plot = $.plot($("#placeholder2"),

           [

		   { data: b, label: "�绰" }

		   ],

		   {

			   xaxis: { mode: 'time' },

			   lines: { show: true },

			   points: { show: true },

			   selection: { mode: "xy" },

			   grid: { hoverable: true, clickable: true },

			   yaxis: { min: 0, max: <?php echo max($a_max,$b_max); ?> }

		});

});





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



<div style="margin-left:20px;">

���磺

<?php

//$this_month = mktime(0,0,0,date("m"),0);

for ($i=0; $i<=6; $i++) {

	$date = mktime(0,0,0,date("m")-$i,1);

?>

	<a href="?month=<?php echo $date; ?>"><?php echo date("Y-m", $date); ?></a>

<?php

}

?>

<div id="placeholder" style="width:800px;height:200px"></div>

<br>

�绰��

<div id="placeholder2" style="width:800px;height:200px"></div>

</div>



</body>

</html>