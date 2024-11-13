<?php

/* --------------------------------------------------------

// ˵��:

// ����: ��ҽս�� 

// ʱ��:

// ----------------------------------------------------- */

require "../../core/core.php";

$table = "patient_".$user_hospital_id;



check_power('', $pinfo) or msg_box("û�д�Ȩ��...", "back", 1);

if ($user_hospital_id == 0) {

	exit_html("�Բ���û��ѡ��ҽԺ������ִ�иò�����");

}



$where = array();

$where[] = "doctor!='' and doctor!='0'";

if (!$debug_mode) {

	$read_parts = get_manage_part(); //�����Ӳ��ţ���ͬ��������)

	if ($uinfo["part_admin"] || $uinfo["part_manage"]) { //���Ź���Ա�����ݹ���Ա

		$where[] = "(part_id in (".$read_parts.") or binary author='".$realname."')";

	} else { //��ͨ�û�ֻ��ʾ�Լ�������

		$where[] = "binary author='".$realname."'";

	}

}

$sqlwhere = count($where) > 0 ? ("where ".implode(" and ", $where)) : "";



$doctor_list = $db->query("select doctor,count(doctor) as count from $table $sqlwhere group by doctor order by binary doctor");



$hospital_id_name = $db->query("select id,name from ".$tabpre."hospital", 'id', 'name');

?>

<html>

<head>

<title>�ط�ҽ��</title>

<meta http-equiv="Content-Type" content="text/html;charset=gb2312">

<meta name="keywords" content="">

<meta name="description" content="">

<link href="/res/base.css" rel="stylesheet" type="text/css">

<script src="/res/base.js" language="javascript"></script>

<style>

ul {width:90%; margin:0 auto; margin-top:50px; }

li {list-style-type:none; width:15%; text-align:center; padding:0px 0; float:left; border:1px solid #95CAFF; margin:3px;}

.go {height:40px; line-height:40px; font-size:16px; font-weight:bold; }

.go:hover {}

.nums {font-size:12px; font-weight:normal; color:gray; }

.list {font-size:12px; font-weight:normal; color:green; border:0;}

.list:hover {font-size:12px; font-weight:normal; color:red;}

</style>

<script language="javascript">

function set_and_go(name) {

	byid("go").doctor_name.value = name;

	byid("go").submit();

}

</script>

</head>



<body>

<!-- ͷ�� begin -->

<div class="headers">

	<div class="headers_title" style="width:45%"><span class="tips"><?=$hospital_id_name[$user_hospital_id]?> - �ط�ҽ��</span></div>

	<div class="header_center"></div>

	<div class="headers_oprate"><button onclick="history.back()" class="button" title="������һҳ">����</button></div>

</div>

<!-- ͷ�� end -->





<ul>

<?php if ($doctor_list) { foreach ($doctor_list as $li) { ?>

	<li><a href="javascript:void(0);" class="go" onclick="set_and_go('<?php echo $li["doctor"]; ?>'); return false;"><?php echo $li["doctor"]; ?><span class="nums">(<?php echo $li["count"]; ?>)</span></a>&nbsp;<a href="patient_huifang_list.php?zhuanjia=<?php echo $li["doctor"]; ?>" target="_blank" class="list" title="��ӡ���ո�����Ա�б�">�б�</a></li>

<?php } } else { ?>

	<div>(û������)</div>

<?php } ?>

</ul>





<form id="go" action="patient.php" method="GET">

<input type="hidden" name="doctor_name" value="">

<input type="hidden" name="sort" value="7">

<input type="hidden" name="sorttype" value="2">

</form>



</body>

</html>