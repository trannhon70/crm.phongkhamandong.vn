<?php
/*
// - ����˵�� : main.php
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-13 12:28
*/
require "../core/core.php";
include "../core/function.lunar.php";

// -------------------- 2013-05-01 23:39
if ($_GET["do"] == 'change') {
	$_SESSION[$cfgSessionName]["hospital_id"] = $_GET["hospital_id"];
	$user_hospital_id = $_SESSION[$cfgSessionName]["hospital_id"];
}
$hospital_list = $db->query("select id,name from hospital where id in (".implode(',', $hospital_ids).") order by sort desc,id asc", 'id');
$part_id_name = $db->query("select id,name from sys_part", 'id', 'name');
// --------------------

// ʱ����޶���:
$today_tb = mktime(0,0,0);
$today_te = $today_tb + 24*3600;
$yesterday_tb = $today_tb - 24*3600;
$month_tb = mktime(0,0,0,date("m"),1);
$month_te = strtotime("+1 month", $month_tb);
$lastmonth_tb = strtotime("-1 month", $month_tb);

// ͬ�����ڶ���(2010-11-27):
$tb_tb = strtotime("-1 month", $month_tb);
$tb_te = strtotime("-1 month", time());

// �±�:
$yuebi_tb = strtotime("-1 month", $today_tb);
if (date("d", $yuebi_tb) != date("d", $today_tb)) {
	$yuebi_tb = $yuebi_te = -1;
} else {
	$yuebi_te = $yuebi_tb + 24*3600;
}

// �ܱ�:
$zhoubi_tb = strtotime("-7 day", $today_tb);
$zhoubi_te = $zhoubi_tb + 24*3600;

// ͬ��:
$tb_tb = strtotime("-1 month", $month_tb); //ͬ��ʱ�俪ʼ
$tb_te = strtotime("-1 month", time()); //ͬ��ʱ�����




// ���л���Ĳ�ѯ���:
function wee($tb, $te, $time_type='order_date', $condition='', $condition2='' ) {
	global $table, $db;
	$time_type = $time_type == "addtime" ? "addtime" : "order_date";
	$where = array();
	if ($tb > 0) $where[] = $time_type.">=".intval($tb);
	if ($te > 0) $where[] = $time_type."<".intval($te);
	if ($condition) $where[] = $condition;
	if ($condition2) $where[] = $condition2;
	$sqlwhere = implode(" and ", $where);
	$sql = "select count(*) as c from $table where $sqlwhere limit 1";
	$sql_md5 = md5($sql);

	// ������:
	$timeout = 60; //���泬ʱʱ��
	$sql_result = -1;
	$cache_file = "cache/".$table;
	if (file_exists($cache_file)) {
		$tm = @explode("\n", str_replace("\r", "", file_get_contents($cache_file)));
		foreach ($tm as $tml) {
			list($a, $b, $c) = explode("|", trim($tml));
			if ($a == $sql_md5) {
				if (time() - $b < $timeout) {
					$sql_result = $c;
					break;
				}
			}
		}
	}

	if ($sql_result != -1) {
		return $sql_result;
	} else {
		$sql_result = $db->query($sql, 1, "c");

		// ���»����ļ�:
		$tm = array();
		$find = 0;
		$time = time();
		if (file_exists($cache_file)) {
			$tm = @explode("\n", str_replace("\r", "", file_get_contents($cache_file)));
			foreach ($tm as $k => $tml) {
				list($a, $b, $c) = explode("|", trim($tml));
				if ($a == $sql_md5) {
					$tm[$k] = $sql_md5."|".$time."|".intval($sql_result);
					$find = 1;
				} else {
					if ($time - $b > $timeout) {
						unset($tm[$k]); //ɾȥ��ʱ��
					}
				}
			}
		}
		if ($find == 0) {
			$tm[] = $sql_md5."|".$time."|".intval($sql_result);
		}
		@file_put_contents($cache_file, implode("\r\n", $tm));
		// ���½���:

		return $sql_result;
	}
}
?>
<html>
<head>
<title>��̨��ҳ</title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="../res/base.css" rel="stylesheet" type="text/css">
<script src="../res/base.js" language="javascript"></script>
<script language="javascript">
function hgo(dir) {
	var obj = byid("hospital_id");
	if (dir == "up") {
		if (obj.selectedIndex > 1) {
			obj.selectedIndex = obj.selectedIndex - 1;
			obj.onchange();
		} else {
			parent.msg_box("�Ѿ�������һ��ҽԺ��", 3);
		}
	}
	if (dir == "down") {
		if (obj.selectedIndex < obj.options.length-1) {
			obj.selectedIndex = obj.selectedIndex + 1;
			obj.onchange();
		} else {
			parent.msg_box("�Ѿ�������һ��ҽԺ��", 3);
		}
	}
}
</script>
<style type="text/css">
<!--
.STYLE1 {
	color: #FF0000;
	font-weight: bold;
}
.STYLE2 {
	color:#009900;
	font-weight: bold;
}

-->
</style>
</head>

<body>

<div style='padding:20px 12px 12px 40px;'>
	<div style="line-height:24px"> 
<?php
$str = '���ã�<font color="#FF0000"><b>'.$realname.'</b></font>';
if ($uinfo["hospitals"] || $uinfo["part_id"] > 0) {
	if ($uinfo["part_id"] > 0) {
		$str .= '��(���ݣ�'.$part_id_name[$uinfo["part_id"]].")";
	}
}

$onlines = $db->query("select count(*) as count from sys_admin where online=1", 1, "count");
$str .= '���������� <font color="red"><b>'.$onlines.'</b></font> ��';

if ($uinfo["part_id"] == 12) {
	//$str .= '<br><a href="#" onclick="parent.load_box(1,\'src\',\'patient_huifang_list_all.php\')">[�鿴�б�]</a>';
}

echo $str;
?> 
	</div>

<?php if (count($hospital_ids) > 1) { ?>
	<div style="margin-top:20px;">
		<b>�л�ҽԺ��</b>
		<select name="hospital_id" id="hospital_id" class="combo" onChange="location='?do=change&hospital_id='+this.value" style="width:200px;">
			<option value="" style="color:gray">--��ѡ��--</option>
			<?php echo list_option($hospital_list, 'id', 'name', $_SESSION[$cfgSessionName]["hospital_id"]); ?> 
		</select>&nbsp;
		<button class="button" onClick="hgo('up');">��</button>&nbsp;
		<button class="button" onClick="hgo('down');">��</button>&nbsp;
<?php if ($user_hospital_id > 0) { ?>
		<button class="buttonb" onClick="self.location='/m/patient/patient.php?time_type=order_date&sort=ԤԼʱ��&show=today&come=0'" title="�鿴����δ����طò���">�طò���</button>&nbsp;
	<?php if ($debug_mode || $username == "admin" || $uinfo["part_id"] == 3) { ?>
		<button class="buttonb" onClick="self.location='/m/patient/patient.php?list_huifang=1'" title="�鿴������طù��Ĳ���">�ҵĻط�</button>&nbsp;
	<?php } ?>
<?php }?>

<?php
if($_SESSION[$cfgSessionName]["username"]=="admin")
{
	echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="tel_go" type="button" value="Ⱥ�����ոõ�Ժ���߶���" onClick="if(confirm(\'��ȷ�ϴ˲������˲�����Ⱥ�����н���ԤԼ��Ժ���߶��ţ�\')){window.open(\'/gettel/index.php\');}">';
}
?>
	</div>
<?php } else if ($user_hospital_id > 0) { ?>
	<div style="margin-top:20px;">��ǰҽԺ��<b><?php echo $hospital_list[$user_hospital_id]["name"];?></b></div>
<?php } else { ?>
	<div style="margin-top:20px;">û��Ϊ������ҽԺ������ϵ�ϼ�������Ա������</div>
<?php }?>
</div>


<!-- ѡ��ҽԺ�� -->
<?php if ($user_hospital_id > 0) { ?>

<!-- ԤԼ����Ȩ�� -->
<?php
$table = "patient_".$user_hospital_id;

$where = array();
$where[] = '1';
if (!$debug_mode) {
	$read_parts = get_manage_part(); //�����Ӳ��ţ���ͬ����������)
	$manage_parts = explode(",", $read_parts);
	if ($uinfo["part_admin"] || $uinfo["part_manage"]) { //���Ź���Ա�����ݹ���Ա
		$where[] = "(part_id in (".$read_parts.") or binary author='".$realname."')";
	} else { //��ͨ�û�ֻ��ʾ�Լ�������
		$where[] = "binary author='".$realname."'";
	}
}

// �绰�ط�ֻ��ʾ�ѵ�����:
if ($uinfo["part_id"] == 12) {
	//$where[] = "status=1";
}

$sqlwhere = implode(" and ", $where);

$today_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$today_tb and order_date<$today_te and status<>3", 1, "count");
if ($_GET["show"] == "sql") {
	echo $db->sql."<br>";
}
$today_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$today_tb and order_date<$today_te and status=1", 1, "count");
$today_not = $today_all - $today_come;

$yesterday_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$yesterday_tb and order_date<$today_tb and status<>3", 1, "count");
$yesterday_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$yesterday_tb and order_date<$today_tb and status=1", 1, "count");
$yesterday_not = $yesterday_all - $yesterday_come;


$this_month_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$month_tb and order_date<$month_te and status<>3", 1, "count");
$this_month_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$month_tb and order_date<$month_te and status=1", 1, "count");
$this_month_not = $this_month_all - $this_month_come;

$last_month_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$lastmonth_tb and order_date<$month_tb and status<>3", 1, "count");
$last_month_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$lastmonth_tb and order_date<$month_tb and status=1", 1, "count");
$last_month_not = $last_month_all - $last_month_come;

// ͬ��:
$tb_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$tb_tb and order_date<$tb_te and status<>3", 1, "count");
$tb_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$tb_tb and order_date<$tb_te and status=1", 1, "count");
$tb_not = $zhoubi_all - $zhoubi_come;

?>
<div style="float:left">
<table width="510" class="edit" style="margin-top:10px;margin-left:40px;">
	<tr>
		<td colspan="2" class="head">�Һ�ͳ������</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right"><a href="/m/patient/patient.php?show=today">�ܹ�: <b><?=$today_all?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=today&come=1">�ѵ�: <b><?=$today_come?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=today&come=0">δ��: <b><?=$today_not?></b></a></td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right"><a href="/m/patient/patient.php?show=yesterday">�ܹ�: <b><?=$yesterday_all?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=yesterday&come=1">�ѵ�: <b><?=$yesterday_come?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=yesterday&come=0">δ��: <b><?=$yesterday_not?></b></a></td>
	</tr>
	
<?php if ($username == "linhanxing"){ ?>

<?php }else{?>
	<tr>
		<td class="left">���£�</td>
		<td class="right"><a href="/m/patient/patient.php?show=thismonth">�ܹ�: <b><?=$this_month_all?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=thismonth&come=1">�ѵ�: <b><?=$this_month_come?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=thismonth&come=0">δ��: <b><?=$this_month_not?></b></a></td>
	</tr>
	<tr>
		<td class="left" style="color:silver">ͬ�ȣ�</td>
		<td class="right" style="color:silver">�ܹ�: <b><?=$tb_all?></b> &nbsp;&nbsp; �ѵ�: <b><?=$tb_come?></b> &nbsp;&nbsp; δ��: <b><?=$tb_not?></b></td>
	</tr>
	<tr>
		<td class="left">���£�</td>
		<td class="right"><a href="/m/patient/patient.php?show=lastmonth">�ܹ�: <b><?=$last_month_all?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=lastmonth&come=1">�ѵ�: <b><?=$last_month_come?></b></a> &nbsp;&nbsp; <a href="/m/patient/patient.php?show=lastmonth&come=0">δ��: <b><?=$last_month_not?></b></a></td>
	</tr>
<?php }?>	
	
</table>

	<?php if ($username == "linhanxing"){ ?>

<?php }else{?>

<?php
$today_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$today_tb and order_date<$today_te", 1, "count");
if ($_GET["show"] == "sql") {
	echo $db->sql."<br>";
}
$today_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$today_tb and order_date<$today_te and status=3", 1, "count");
$today_not = $today_all - $today_come;

$yesterday_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$yesterday_tb and order_date<$today_tb", 1, "count");
$yesterday_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$yesterday_tb and order_date<$today_tb and status=3", 1, "count");
$yesterday_not = $yesterday_all - $yesterday_come;

$this_month_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$month_tb and order_date<$month_te", 1, "count");
$this_month_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$month_tb and order_date<$month_te and status=3", 1, "count");
$this_month_not = $this_month_all - $this_month_come;

$last_month_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$lastmonth_tb and order_date<$month_tb", 1, "count");
$last_month_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$lastmonth_tb and order_date<$month_tb and status=3", 1, "count");
$last_month_not = $last_month_all - $last_month_come;

// ͬ��:
$tb_all = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$tb_tb and order_date<$tb_te", 1, "count");
$tb_come = $db->query("select count(*) as count from $table where $sqlwhere and order_date>=$tb_tb and order_date<$tb_te and status=3", 1, "count");
$tb_not = $zhoubi_all - $zhoubi_come;
?>
<table width="510" class="edit" style="margin-top:10px;margin-left:40px;">
	<tr>
		<td colspan="2" class="head">ԤԼδ������ͳ��</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right"><a href="/m/patient/patient.php?show=today&come=3">��: <b><?=$today_come?></b></a></td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right"><a href="/m/patient/patient.php?show=yesterday&come=3">��: <b><?=$yesterday_come?></b></a></td>
	</tr>
	


	<tr>
		<td class="left">���£�</td>
		<td class="right"><a href="/m/patient/patient.php?show=thismonth&come=3">��: <b><?=$this_month_come?></b></a></td>
	</tr>
	<tr>
		<td class="left" style="color:silver">ͬ�ȣ�</td>
		<td class="right" style="color:silver">��: <b><?=$tb_come?></b></td>
	</tr>
	<tr>
		<td class="left">���£�</td>
		<td class="right"><a href="/m/patient/patient.php?show=lastmonth&come=3">��: <b><?=$last_month_come?></b></a></td>
	</tr>
	
</table>
	<?php }?>

</div>

<?php
   $by_daoyuan_order = $db->query("select count(*) as count,author from $table where status = 1 and order_date>=$month_tb and order_date<$month_te and part_id!=4 group by author order by count desc limit 0,5");
   $by_yuyue_order = $db->query("select count(*) as count,author from $table where status != 3 and addtime>=$month_tb and addtime<$month_te and part_id!=4 group by author order by count desc limit 0,5");
   $sy_daoyuan_order = $db->query("select count(*) as count,author from $table where status = 1 and order_date>=$lastmonth_tb and order_date<$month_tb and part_id!=4 group by author order by count desc limit 0,5");
   $sy_yuyue_order = $db->query("select count(*) as count,author from $table where status != 3 and addtime>=$lastmonth_tb and addtime<$month_tb and part_id!=4 group by author order by count desc limit 0,5");
?>  
<style>
.paihangbang{margin-top:10px;padding-left:10px;float:left;width:380px;}
.paihangbang td{ height:26px;}
.paihangbang span{width:20px;height:20px;display:block;margin-left:10px;}
.paihangbang .crown{background:url("/res/img/crown.png") no-repeat; }
.paihangbang .moon{background:url("/res/img/moon.png") no-repeat; }
.paihangbang .star{background:url("/res/img/star.png") no-repeat; }
.paihangbang .sun{background:url("/res/img/sun.png") no-repeat; }
.paihangbang .nums{font-size:14px;font-weight:bold;padding-right:10px;}
</style>

<?php if ($username == "linhanxing"){ ?>

<?php }else{?>
<?php if($_SESSION[$cfgSessionName]["part_id"]!=5){?>

<div class="paihangbang">
  <div style="float:left">
    <table width="180" class="edit" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3" class="head">���µ�Ժ���а�</td>
      </tr>
   <?php 
      if(!is_array($by_daoyuan_order)){$by_daoyuan_order = array();}
	  
      foreach($by_daoyuan_order as $key=>$val){  
   ?>
      <tr>
        <td><?php if($key == 0){echo '<span class="crown"></span>';}elseif($key == 1){echo '<span class="sun"></span>';}elseif($key==2){echo '<span class="moon"></span>';}else{echo '<span class="star"></span>';}?></td>
        <td><?php echo $val['author'];?></td>
        <td class="nums"><?php echo $val['count'];?></td>
      </tr>
   <?php
      }
   ?>
    </table>
  </div>
  <div style="float:left; padding-left:10px;" >
    <table width="180" class="edit" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3" class="head">����ԤԼ���а�</td>
      </tr>
   <?php 
      if(!is_array($by_yuyue_order)){$by_yuyue_order = array();}

      foreach($by_yuyue_order as $key=>$val){  
   ?>
      <tr>
        <td><?php if($key == 0){echo '<span class="crown"></span>';}elseif($key == 1){echo '<span class="sun"></span>';}elseif($key==2){echo '<span class="moon"></span>';}else{echo '<span class="star"></span>';}?></td>
        <td><?php echo $val['author'];?></td>
        <td class="nums"><?php echo $val['count'];?></td>
      </tr>
   <?php
      }
   ?>
    </table>
  </div>
  <div class="clear"></div>
  <div style="float:left; margin-top:10px;">
    <table width="180" class="edit" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3" class="head">���µ�Ժ���а�</td>
      </tr>
   <?php 
      if(!is_array($sy_daoyuan_order)){$sy_daoyuan_order = array();}
      foreach($sy_daoyuan_order as $key=>$val){  
   ?>
      <tr>
        <td><?php if($key == 0){echo '<span class="crown"></span>';}elseif($key == 1){echo '<span class="sun"></span>';}elseif($key==2){echo '<span class="moon"></span>';}else{echo '<span class="star"></span>';}?></td>
        <td><?php echo $val['author'];?></td>
        <td class="nums"><?php echo $val['count'];?></td>
      </tr>
   <?php
      }
   ?>
    </table>
  </div>
  <div style="float:left; padding-left:10px; margin-top:10px;" >
    <table width="180" class="edit" border="0" cellpadding="0" cellspacing="0">
      <tr>
        <td colspan="3" class="head">����ԤԼ���а�</td>
      </tr>
   <?php 
      if(!is_array($sy_yuyue_order)){$sy_yuyue_order = array();}
      foreach($sy_yuyue_order as $key=>$val){  
   ?>
      <tr>
        <td><?php if($key == 0){echo '<span class="crown"></span>';}elseif($key == 1){echo '<span class="sun"></span>';}elseif($key==2){echo '<span class="moon"></span>';}else{echo '<span class="star"></span>';}?></td>
        <td><?php echo $val['author'];?></td>
        <td class="nums"><?php echo $val['count'];?></td>
      </tr>
   <?php
      }
   ?>
    </table>
  </div>
</div>
<?php }?>
<?php }?>

<div class="clear"></div>
<p style="margin-top:20px;"><strong>��������</strong></p>
<!-- ����Ա����ͳ������ -->
<!--<?php if ($username == "admin" || $debug_mode || in_array($uinfo["part_id"], array(1,9)) || ($uinfo["part_admin"] && in_array(2,$manage_parts)) ) { ?>
<?php
$table = "patient_".$user_hospital_id;
$web_1 = $db->query("select count(*) as count from $table where part_id=2 and addtime>=$today_tb and addtime<$today_te and status<>3", 1, "count");
$web_2 = $db->query("select count(*) as count from $table where part_id=2 and addtime>=$yesterday_tb and addtime<$today_tb and status<>3", 1, "count");
$web_3 = $db->query("select count(*) as count from $table where part_id=2 and addtime>=$month_tb and addtime<$month_te and status<>3", 1, "count");

$web_4 = $db->query("select count(*) as count from $table where part_id=2 and status=1 and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_5 = $db->query("select count(*) as count from $table where part_id=2 and status=1 and order_date>=$yesterday_tb and order_date<$today_tb  and status<>3", 1, "count");
$web_6 = $db->query("select count(*) as count from $table where part_id=2 and status=1 and order_date>=$month_tb and order_date<$month_te  and status<>3", 1, "count");

$web_7 = $db->query("select count(*) as count from $table where part_id=2 and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_8 = $db->query("select count(*) as count from $table where part_id=2 and order_date>=$yesterday_tb and order_date<$today_tb and status<>3", 1, "count");
$web_9 = $db->query("select count(*) as count from $table where part_id=2 and order_date>=$month_tb and order_date<$month_te and status<>3", 1, "count");

// ͬ��
$web_tb1 = $db->query("select count(*) as count from $table where part_id=2 and addtime>=$tb_tb and addtime<$tb_te and status<>3", 1, "count");
$web_tb2 = $db->query("select count(*) as count from $table where part_id=2 and order_date>=$tb_tb and order_date<$tb_te and status<>3", 1, "count");
$web_tb3 = $db->query("select count(*) as count from $table where part_id=2 and order_date>=$tb_tb and order_date<$tb_te and status=1", 1, "count");

?>
<div style="float:left; width:250px; padding-left:40px;">
<table width="275" class="edit" style="margin-top:10px;">
	<tr>
		<td colspan="2" class="head">���粿</td>
	</tr>
	<tr>
		<td class="left" style="width:20%">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:<a href="/m/patient/patient.php?show=today&time_type=addtime&part_id=2">&nbsp;<b><?php echo $web_1; ?></b>&nbsp;</a></span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:<a href="/m/patient/patient.php?show=today&part_id=2">&nbsp;<b><?php echo $web_7; ?></b>&nbsp;</a></span>
			<span title="�����Ѿ���Ժ����">��:<a href="/m/patient/patient.php?show=today&part_id=2&come=1">&nbsp;<b><?php echo $web_4; ?></b>&nbsp;</a></span>
		</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:<a href="/m/patient/patient.php?show=yesterday&time_type=addtime&part_id=2">&nbsp;<b><?php echo $web_2; ?></b>&nbsp;</a></span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:<a href="/m/patient/patient.php?show=yesterday&part_id=2">&nbsp;<b><?php echo $web_8; ?></b>&nbsp;</a></span>
			<span title="�����Ѿ���Ժ����">��:<a href="/m/patient/patient.php?show=yesterday&part_id=2&come=1">&nbsp;<b><?php echo $web_5; ?></b>&nbsp;</a></span>
		</td>
	</tr>
	<tr>
		<td class="left">���£�</td>
		<td class="right">
			<span title="���¿ͷ�ԤԼ����">Լ:<a href="/m/patient/patient.php?show=thismonth&time_type=addtime&part_id=2">&nbsp;<b><?php echo $web_3; ?></b>&nbsp;</a></span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:<a href="/m/patient/patient.php?show=thismonth&part_id=2">&nbsp;<b><?php echo $web_9; ?></b>&nbsp;</a></span>
			<span title="�����Ѿ���Ժ����">��:<a href="/m/patient/patient.php?show=thismonth&part_id=2&come=1">&nbsp;<b><?php echo $web_6; ?></b>&nbsp;</a></span>
		</td>
	</tr>
	<tr>
		<td class="left" style="color:silver">ͬ�ȣ�</td>
		<td class="right" style="color:silver">
			Լ:<b>&nbsp;<?=$web_tb1?>&nbsp;</b>
			Ԥ��:<b>&nbsp;<?=$web_tb2?>&nbsp;</b>
			��:<b>&nbsp;<?=$web_tb3?>&nbsp;</b>
		</td>
	</tr>
</table>
</div>
<?php } ?>
-->
<?php
$ar=$db->query("select * from media where hospital_id='$user_hospital_id' order by id asc","id","name");
foreach($ar as $key=>$value)
{
?>
<!-- ����Ա����ͳ������ -->
<?php if ($username == "admin" ||$username == "linhanxing" || $debug_mode || in_array($uinfo["part_id"], array(1,9)) || ($uinfo["part_admin"] && in_array(2,$manage_parts)) ) { ?>
<?php
$table = "patient_".$user_hospital_id;
$web_1 = $db->query("select count(*) as count from $table where media_from='{$value}' and addtime>=$today_tb and addtime<$today_te and status<>3", 1, "count");
$web_2 = $db->query("select count(*) as count from $table where media_from='{$value}' and addtime>=$yesterday_tb and addtime<$today_tb and status<>3", 1, "count");
$web_3 = $db->query("select count(*) as count from $table where media_from='{$value}' and addtime>=$month_tb and addtime<$month_te and status<>3", 1, "count");

$web_4 = $db->query("select count(*) as count from $table where media_from='{$value}' and status=1 and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_5 = $db->query("select count(*) as count from $table where media_from='{$value}' and status=1 and order_date>=$yesterday_tb and order_date<$today_tb  and status<>3", 1, "count");
$web_6 = $db->query("select count(*) as count from $table where media_from='{$value}' and status=1 and order_date>=$month_tb and order_date<$month_te  and status<>3", 1, "count");

$web_7 = $db->query("select count(*) as count from $table where media_from='{$value}' and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_8 = $db->query("select count(*) as count from $table where media_from='{$value}' and order_date>=$yesterday_tb and order_date<$today_tb and status<>3", 1, "count");
$web_9 = $db->query("select count(*) as count from $table where media_from='{$value}' and order_date>=$month_tb and order_date<$month_te and status<>3", 1, "count");

// ͬ��
$web_tb1 = $db->query("select count(*) as count from $table where media_from='{$value}' and addtime>=$tb_tb and addtime<$tb_te and status<>3", 1, "count");
$web_tb2 = $db->query("select count(*) as count from $table where media_from='{$value}' and order_date>=$tb_tb and order_date<$tb_te and status<>3", 1, "count");
$web_tb3 = $db->query("select count(*) as count from $table where media_from='{$value}' and order_date>=$tb_tb and order_date<$tb_te and status=1", 1, "count");

?>
<div style="float:left; width:250px; padding-left:30px;">
<table width="275" class="edit" style="margin-top:10px;">
	<tr>
		<td colspan="2" class="head"><?php echo $value?></td>
	</tr>
	<tr>
		<td class="left" style="width:20%">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:<a href="/m/patient/patient.php?show=today&time_type=addtime&part_id=&media=<?php echo $value?>">&nbsp;<b><?php echo $web_1; ?></b>&nbsp;</a></span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:<a href="/m/patient/patient.php?show=today&part_id=&media=<?php echo $value?>">&nbsp;<b><?php echo $web_7; ?></b>&nbsp;</a></span>
			<span title="�����Ѿ���Ժ����">��:<a href="/m/patient/patient.php?show=today&part_id=&media=<?php echo $value?>&come=1">&nbsp;<b><?php echo $web_4; ?></b>&nbsp;</a></span>
		</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:<a href="/m/patient/patient.php?show=yesterday&time_type=addtime&part_id=&media=<?php echo $value?>">&nbsp;<b><?php echo $web_2; ?></b>&nbsp;</a></span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:<a href="/m/patient/patient.php?show=yesterday&part_id=&media=<?php echo $value?>">&nbsp;<b><?php echo $web_8; ?></b>&nbsp;</a></span>
			<span title="�����Ѿ���Ժ����">��:<a href="/m/patient/patient.php?show=yesterday&part_id=&media=<?php echo $value?>&come=1">&nbsp;<b><?php echo $web_5; ?></b>&nbsp;</a></span>
		</td>
	</tr>
	
	<?php if ($username == "linhanxing"){ ?>

<?php }else{?>
	<tr>
		<td class="left">���£�</td>
		<td class="right">
			<span title="���¿ͷ�ԤԼ����">Լ:<a href="/m/patient/patient.php?show=thismonth&time_type=addtime&part_id=&media=<?php echo $value?>">&nbsp;<b><?php echo $web_3; ?></b>&nbsp;</a></span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:<a href="/m/patient/patient.php?show=thismonth&part_id=&media=<?php echo $value?>">&nbsp;<b><?php echo $web_9; ?></b>&nbsp;</a></span>
			<span title="�����Ѿ���Ժ����">��:<a href="/m/patient/patient.php?show=thismonth&part_id=&media=<?php echo $value?>&come=1">&nbsp;<b><?php echo $web_6; ?></b>&nbsp;</a></span>
		</td>
	</tr>
	<tr>
		<td class="left" style="color:silver">ͬ�ȣ�</td>
		<td class="right" style="color:silver">
			Լ:<b>&nbsp;<?=$web_tb1?>&nbsp;</b>
			Ԥ��:<b>&nbsp;<?=$web_tb2?>&nbsp;</b>
			��:<b>&nbsp;<?=$web_tb3?>&nbsp;</b>
		</td>
	</tr>
	
	<?php }?>
</table>
</div>
<?php 
}
} ?>

<div class="clear"></div>


<p><strong>���ҵ���</strong></p>
<?php
$ar=$db->query("select * from depart where hospital_id='$user_hospital_id' order by id ASC","id","name");
foreach($ar as $key=>$value)
{
?>
<!-- ����Ա����ͳ������ -->
<?php if ($username == "admin"  ||$username == "linhanxing" || $debug_mode || in_array($uinfo["part_id"], array(1,9)) || ($uinfo["part_admin"] && in_array(2,$manage_parts)) ) { ?>
<?php
$table = "patient_".$user_hospital_id;
$web_1 = $db->query("select count(*) as count from $table where depart='{$key}' and addtime>=$today_tb and addtime<$today_te and status<>3", 1, "count");
$web_2 = $db->query("select count(*) as count from $table where depart='{$key}' and addtime>=$yesterday_tb and addtime<$today_tb and status<>3", 1, "count");
$web_3 = $db->query("select count(*) as count from $table where depart='{$key}' and addtime>=$month_tb and addtime<$month_te and status<>3", 1, "count");

$web_4 = $db->query("select count(*) as count from $table where depart='{$key}' and status=1 and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_5 = $db->query("select count(*) as count from $table where depart='{$key}' and status=1 and order_date>=$yesterday_tb and order_date<$today_tb  and status<>3", 1, "count");
$web_6 = $db->query("select count(*) as count from $table where depart='{$key}' and status=1 and order_date>=$month_tb and order_date<$month_te  and status<>3", 1, "count");

$web_7 = $db->query("select count(*) as count from $table where depart='{$key}' and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_8 = $db->query("select count(*) as count from $table where depart='{$key}' and order_date>=$yesterday_tb and order_date<$today_tb and status<>3", 1, "count");
$web_9 = $db->query("select count(*) as count from $table where depart='{$key}' and order_date>=$month_tb and order_date<$month_te and status<>3", 1, "count");

?>
<div style="float:left; width:250px; padding-left:30px;">
<table width="275" class="edit" style="margin-top:10px;">
	<tr>
		<td colspan="2" class="head"><?php echo $value?></td>
	</tr>
	<tr>
		<td class="left" style="width:20%">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����"  class="STYLE1">Լ:&nbsp;<?php echo $web_1; ?>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_7; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����" class="STYLE2">��:&nbsp;<b><?php echo $web_4; ?></b>&nbsp;</span>	
		</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:&nbsp;<b><?php echo $web_2; ?></b>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_8; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����">��:&nbsp;<b><?php echo $web_5; ?></b>&nbsp;</span>
		</td>
	</tr>
	<tr>
		<td class="left">���£�</td>
		<td class="right">
			<span title="���¿ͷ�ԤԼ����">Լ:&nbsp;<b><?php echo $web_3; ?></b>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_9; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����">��:&nbsp;<b><?php echo $web_6; ?></b>&nbsp;</span>
		</td>
	</tr>
</table>
</div>
<?php 
}
} ?>
<div class="clear"></div>
<p><strong>���ֵ���</strong></p>
<?php
$ar=$db->query("select * from  disease where hospital_id='$user_hospital_id' order by id asc","id","name");
foreach($ar as $key=>$value)
{
?>
<!-- ����Ա����ͳ������ -->
<?php if ($username == "admin" ||$username == "linhanxing" || $debug_mode || in_array($uinfo["part_id"], array(1,9)) || ($uinfo["part_admin"] && in_array(2,$manage_parts)) ) { ?>
<?php
$table = "patient_".$user_hospital_id;
$web_1 = $db->query("select count(*) as count from $table where disease_id='{$key}' and addtime>=$today_tb and addtime<$today_te and status<>3", 1, "count");
$web_2 = $db->query("select count(*) as count from $table where disease_id='{$key}' and addtime>=$yesterday_tb and addtime<$today_tb and status<>3", 1, "count");
$web_3 = $db->query("select count(*) as count from $table where disease_id='{$key}' and addtime>=$month_tb and addtime<$month_te and status<>3", 1, "count");

$web_4 = $db->query("select count(*) as count from $table where disease_id='{$key}' and status=1 and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_5 = $db->query("select count(*) as count from $table where disease_id='{$key}' and status=1 and order_date>=$yesterday_tb and order_date<$today_tb  and status<>3", 1, "count");
$web_6 = $db->query("select count(*) as count from $table where disease_id='{$key}' and status=1 and order_date>=$month_tb and order_date<$month_te  and status<>3", 1, "count");

$web_7 = $db->query("select count(*) as count from $table where disease_id='{$key}' and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_8 = $db->query("select count(*) as count from $table where disease_id='{$key}' and order_date>=$yesterday_tb and order_date<$today_tb and status<>3", 1, "count");
$web_9 = $db->query("select count(*) as count from $table where disease_id='{$key}' and order_date>=$month_tb and order_date<$month_te and status<>3", 1, "count");

?>
<div style="float:left; width:250px; padding-left:30px;">
<table width="275" class="edit" style="margin-top:10px;">
	<tr>
		<td colspan="2" class="head"><?php echo $value?></td>
	</tr>
	<tr>
		<td class="left" style="width:20%">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����"  class="STYLE1">Լ:&nbsp;<?php echo $web_1; ?>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_7; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����" class="STYLE2">��:&nbsp;<b><?php echo $web_4; ?></b>&nbsp;</span>	
		</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:&nbsp;<b><?php echo $web_2; ?></b>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_8; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����">��:&nbsp;<b><?php echo $web_5; ?></b>&nbsp;</span>
		</td>
	</tr>
	<tr>
		<td class="left">���£�</td>
		<td class="right">
			<span title="���¿ͷ�ԤԼ����">Լ:&nbsp;<b><?php echo $web_3; ?></b>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_9; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����">��:&nbsp;<b><?php echo $web_6; ?></b>&nbsp;</span>
		</td>
	</tr>
</table>
</div>
<?php 
}
} ?>
<div class="clear"></div>
<p><strong>��ѯԱ����</strong></p>
<?php
$ar=$db->query("select * from sys_admin where hospitals='$user_hospital_id' and character_id=17 order by id desc","id","realname");
foreach($ar as $key=>$value)
{
?>
<!-- ����Ա����ͳ������ -->
<?php if ($username == "admin" || $debug_mode || in_array($uinfo["part_id"], array(1,9)) || ($uinfo["part_admin"] && in_array(2,$manage_parts)) ) { ?>
<?php
$table = "patient_".$user_hospital_id;
$web_1 = $db->query("select count(*) as count from $table where author='{$value}' and addtime>=$today_tb and addtime<$today_te and status<>3", 1, "count");
$web_2 = $db->query("select count(*) as count from $table where author='{$value}' and addtime>=$yesterday_tb and addtime<$today_tb and status<>3", 1, "count");
$web_3 = $db->query("select count(*) as count from $table where author='{$value}' and addtime>=$month_tb and addtime<$month_te and status<>3", 1, "count");

$web_4 = $db->query("select count(*) as count from $table where author='{$value}' and status=1 and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_5 = $db->query("select count(*) as count from $table where author='{$value}' and status=1 and order_date>=$yesterday_tb and order_date<$today_tb  and status<>3", 1, "count");
$web_6 = $db->query("select count(*) as count from $table where author='{$value}' and status=1 and order_date>=$month_tb and order_date<$month_te  and status<>3", 1, "count");

$web_7 = $db->query("select count(*) as count from $table where author='{$value}' and order_date>=$today_tb and order_date<$today_te  and status<>3", 1, "count");
$web_8 = $db->query("select count(*) as count from $table where author='{$value}' and order_date>=$yesterday_tb and order_date<$today_tb and status<>3", 1, "count");
$web_9 = $db->query("select count(*) as count from $table where author='{$value}' and order_date>=$month_tb and order_date<$month_te and status<>3", 1, "count");

?>
<div style="float:left; width:250px; padding-left:30px;">
<table width="275" class="edit" style="margin-top:10px;">
	<tr>
		<td colspan="2" class="head"><?php echo $value?></td>
	</tr>
	<tr>
		<td class="left" style="width:20%">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����"  class="STYLE1">Լ:&nbsp;<?php echo $web_1; ?>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_7; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����" class="STYLE2">��:&nbsp;<b><?php echo $web_4; ?></b>&nbsp;</span>	
		</td>
	</tr>
	<tr>
		<td class="left">���գ�</td>
		<td class="right">
			<span title="���տͷ�ԤԼ����">Լ:&nbsp;<b><?php echo $web_2; ?></b>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_8; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����">��:&nbsp;<b><?php echo $web_5; ?></b>&nbsp;</span>
		</td>
	</tr>
	<tr>
		<td class="left">���£�</td>
		<td class="right">
			<span title="���¿ͷ�ԤԼ����">Լ:&nbsp;<b><?php echo $web_3; ?></b>&nbsp;</span>
			<span title="����Ԥ�Ƶ�Ժ����">Ԥ��:&nbsp;<b><?php echo $web_9; ?></b>&nbsp;</span>
			<span title="�����Ѿ���Ժ����">��:&nbsp;<b><?php echo $web_6; ?></b>&nbsp;</span>
		</td>
	</tr>
</table>
</div>
<?php 
}
}
}?>
<div class="clear"></div>
<!-- ע�� -->
<div style="padding-top:40px; padding-left:50px;">
	* <b>ͬ��</b>���ϸ��µ�ͬ�����ݡ����磬������3��28�գ���ͬ�Ⱦ���2��1����2��28�����ʱ�������<br>
</div>

</body>
</html>