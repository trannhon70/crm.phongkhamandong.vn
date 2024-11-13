<?php
/*
// - ����˵�� : �����б�
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-01 05:09
*/
//date_default_timezone_set('Asia/Shanghai');
require "../../core/core.php";
$mod = "patient";
$table = "patient_".$user_hospital_id;

if ($user_hospital_id == 0) {
	exit_html("�Բ���û��ѡ��ҽԺ������ִ�иò�����");
}

// ��ɫ���� 2010-07-31
$line_color = array('black', 'red', 'silver', '#8AC6C6', '#8000FF');
$line_color_tip = array("�ȴ�", "�ѵ�", "δ��", "����", "�ط�");
$area_id_name = array(0 => "δ֪", 1 => "����", 2 => "���");



if(!isset($_GET["page"]))
{
	$page=1;
	$s=0;
}
else
{
	$page=$_GET["page"];
	$s=$page*20-20;
}

	$part_today_all = $db->query("select * from yy_list order by id desc limit $s,20");
	$res_report = '';
	$count_come = $db->query("select count(*) as count from yy_list where status=1 order by id desc", 1, "count");
	$count_not = $db->query("select count(*) as count from yy_list where status!=1 order by id desc", 1, "count");

	$count_all = $count_come + $count_not;
	$res_report = "�ܹ�: <b>".$count_all."</b> &nbsp; �ѵ�: <b>".$count_come."</b> &nbsp; δ��: <b>".$count_not."</b>";

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
	
//ͳ��
//����
$today_all = $db->query("select count(*) as count from yy_list where addtime>=$today_tb", 1, "count");
//����
$yesterday_all = $db->query("select count(*) as count from yy_list where addtime>=$yesterday_tb and addtime<$today_tb", 1, "count");
//ǰ������
$q_all = $count_all - $today_all - $yesterday_all;

function Yesterday($a,$b)
{
	$Date_1=$a;
	$Date_2=$b;
	
	$Date_explode_1=explode("-",$Date_1);
	$Date_explode_2=explode("-",$Date_2);
	
	$Day_1=mktime(0,0,0,$Date_explode_1[1],$Date_explode_1[2],$Date_explode_1[0]);
	$Day_2=mktime(0,0,0,$Date_explode_2[1],$Date_explode_2[2],$Date_explode_2[0]);
	$Days=round(($Day_1-$Day_2)/3600/24);
	return $Days;
}
//-----------------------

function mlist()
{
	global $part_today_all,$today_all,$yesterday_all,$q_all;
	$a=true;
	$b=true;
	$c=true;
	foreach ($part_today_all as $row)
	{
		$d_date=str_replace('|', ' ', @date("Y-m-d|H:i", $row["addtime"]));
		$conttent = mb_substr($row['content'],0,50,"gbk");
		
		if(date("Y-m-d", $row["addtime"])==date("Y-m-d") and $a==true)
		{
			echo '<tr class="pagex"><td height="35" colspan="9">����('.$today_all.')</td></tr>';
			$a=false;
		}
		if(Yesterday(date("Y-m-d"),$d_date)==1 and $b==true)
		{
			echo '<tr class="pagex"><td height="35" colspan="9">����('.$yesterday_all.')</td></tr>';
			$b=false;
		}
		if(Yesterday(date("Y-m-d"),$d_date)>1 and $c==true)
		{
			echo '<tr class="pagex"><td height="35" colspan="9">ǰ������('.$q_all.')</td></tr>';
			$c=false;
		}
		
		echo $row["status"]==1?"<tr class=\"mtrx\">":"<tr class=\"mtr\">";
		$id = $row["id"];
		$ks = mb_substr($row['ks'],0,8,"gbk");
		echo "
		<td height=\"30\">{$row['name']}</td>
		<td>{$row['tel']}</td>
		<td>{$row['qq']}</td>
		<td>{$row['age']}</td>
		<td>{$row['zhuanjia_num']}</td>
		<td title=\"{$row['ks']}\">{$ks}</td>
		<td>{$d_date}</td>
		<td>{$conttent}</td>
		<td><a href=\"w_list_ck.php?id={$id}\" title='�鿴����'><img src=\"/res/img/b_detail.gif\" /></a> <a href=\"w_list.edit.php?op=edit&id={$id}&go=back\" title='�޸�����'><img src=\"/res/img/b_edit.gif\" /></a> <a href=\"w_list.delete.php?page={$_GET['page']}&id={$id}\" title='ɾ��' onclick='return confirm(\"��ȷ��Ҫɾ��������Ϣ���˲����޷��ָ���\")'><img src=\"/res/img/b_delete.gif\" /></a></td>
	  </tr>";
	  
	}
}

function page()
{
	global $page,$count_all;
	
	$mcount=ceil($count_all / 20);
	echo "�ϼƣ�".$count_all . "�� " .$mcount. "ҳ ";
	$index=$page<2 ? "��ҳ" : $index="<a href=\"?page=1\">��ҳ</a>";
	$pre=$page<2 ? "��һҳ":"<a href=\"?page=". ($page-1) ."\">��һҳ</a>";
	$next=$page<$mcount ? "<a href=\"?page=". ($page+1) ."\">��һҳ</a>":"��һҳ";
	$Last=$page<$mcount ? "<a href=\"?page=". ($mcount) ."\">βҳ</a>":"βҳ";

	echo "$index $pre $next $Last ";
	
	echo '<select name="page" id="page" onchange="MM_jumpMenu(\'self\',this,0)">';
	for($i=1;$i<$mcount+1;$i++)
	{
		if($i==$page)
		{
			echo "<option value=\"?page={$i}\" selected=\"selected\">{$i}ҳ</option>";
		}
		else
		{
			echo "<option value=\"?page={$i}\">{$i}ҳ</option>";
		}
	}
	echo '</select>';
}
include "w_list.tpl.php";








?>