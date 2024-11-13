<?php
/*
// - ����˵�� : �����б�
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-07-17 15:22
*/
require "../../core/core.php";
$table = "patient_".$user_hospital_id;

check_power('', $pinfo) or msg_box("û�д�Ȩ��...", "back", 1);
if ($user_hospital_id == 0) {
	exit_html("�Բ���û��ѡ��ҽԺ������ִ�иò�����");
}


// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:
$aLinkInfo = array(
	"zhuanjia" => "zhuanjia",
	"show_type" => "show_type"
);

// ��ȡҳ����ò���:
foreach ($aLinkInfo as $local_var_name => $call_var_name) {
	$$local_var_name = $_GET[$call_var_name];
}

// ���嵥Ԫ���ʽ:
$aOrderType = array(0 => "", 1 => "asc", 2 => "desc");
$aTdFormat = array(
	1=>array("title"=>"����", "width"=>"50", "align"=>"center"),
	2=>array("title"=>"�Ա�", "width"=>"", "align"=>"center"),
	3=>array("title"=>"����", "width"=>"", "align"=>"center"),
	4=>array("title"=>"�绰", "width"=>"", "align"=>"center"),
	5=>array("title"=>"ר��", "width"=>"", "align"=>"center"),
	6=>array("title"=>"ԤԼʱ��", "width"=>"8%", "align"=>"center"),
	7=>array("title"=>"��������", "width"=>"", "align"=>"center"),
	8=>array("title"=>"ý����Դ", "width"=>"", "align"=>"center"),
	9=>array("title"=>"������Ŀ", "width"=>"", "align"=>"center"),
	10=>array("title"=>"��ע", "width"=>"", "align"=>"center"),
	11=>array("title"=>"����", "width"=>"", "align"=>"center"),
	12=>array("title"=>"�����", "width"=>"", "align"=>"center"),
);


if (!$show_type) $show_type = "today";

if ($show_type == 'today') {
	$begin_time = mktime(0, 0, 0);
	$end_time = strtotime("+1 day", $begin_time);
} else if ($show_type == 'tomorrow') {
	$begin_time = mktime(0, 0, 0) + 24 * 3600;
	$end_time = strtotime("+1 day", $begin_time);
}

// ��ѯ����:
$where = array();
$where[] = "status=1";
if ($zhuanjia) {
	$where[] = "doctor='$zhuanjia'";
}
$where[] = "rechecktime>=$begin_time and rechecktime<$end_time";

$sqlwhere = count($where) > 0 ? ("where ".implode(" and ", $where)) : "";

// ��ѯ:
$data = $db->query("select * from $table $sqlwhere order by binary name");

// id => name:
$hospital_id_name = $db->query("select id,name from ".$tabpre."hospital", 'id', 'name');
$part_id_name = $db->query("select id,name from ".$tabpre."sys_part", 'id', 'name');
$disease_id_name = $db->query("select id,name from ".$tabpre."disease", 'id', 'name');


// ҳ�濪ʼ ------------------------
?>
<html>
<head>
<title>����Ӧ�����ﲡ�� - <?php echo $hospital_id_name[$user_hospital_id]; ?> - <?php echo date("Y��n��j��", $begin_time); ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
</head>

<body>

<!-- �����б� begin -->
<table width="100%" align="center" class="list" style="border:1px solid black;">
	<!-- ��ͷ���� begin -->
	<tr>
<?php
// ��ͷ����:
foreach ($aTdFormat as $tdid => $tdinfo) {
	list($tdalign, $tdwidth, $tdtitle) = make_td_head($tdid, $tdinfo);
?>
		<td class="head" align="<?php echo $tdalign; ?>" width="<?php echo $tdwidth; ?>"><?php echo $tdtitle; ?></td>
<? } ?>
	</tr>
	<!-- ��ͷ���� end -->

	<!-- ��Ҫ�б����� begin -->
<?php
if (count($data) > 0) {
	foreach ($data as $line) {
		$id = $line["id"];
?>
	<tr>
		<td align="center" class="item"><?php echo $line["name"]; ?></td>
		<td align="center" class="item"><?php echo $line["sex"]; ?></td>
		<td align="center" class="item"><?php echo $line["age"] > 0 ? $line["age"] : ''; ?></td>
		<td align="center" class="item"><?php echo $line["tel"]; ?></td>
		<td align="center" class="item"><?php echo $line["doctor"] ? $line["doctor"] : ''; ?></td>
		<td align="center" class="item"><?php echo str_replace('|', '<br>', @date("Y-m-d|H:i", $line["order_date"])); ?></td>
		<td align="center" class="item"><?php echo $disease_id_name[$line["disease_id"]]; ?></td>
		<td align="center" class="item"><?php echo $line["media_from"]; ?></td>
		<td align="center" class="item"><?php echo $line["xiangmu"]; ?></td>
		<td align="center" class="item"><?php echo $line["memo"]; ?></td>
		<td align="center" class="item"><?php echo $part_id_name[$line["part_id"]]; ?></td>
		<td align="center" class="item"><?php echo $line["author"]; ?></td>
	</tr>
<?php
	}
} else {
?>
	<tr>
		<td colspan="<?php echo count($aTdFormat); ?>" align="center" class="nodata">(û������...)</td>
	</tr>
<?php } ?>
	<!-- ��Ҫ�б����� end -->

</table>
<!-- �����б� end -->

</body>
</html>