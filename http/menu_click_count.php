<?php
/*
// - ����˵�� : ��¼�û��˵��ĵ������
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-06-14 10:45
*/
require "../core/core.php";
header("Content-Type:text/html;charset=GB2312");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

//username�ļ��:
if ($username == "") exit;

//$_GET["mid"] = 32;
//$uinfo["menuclicks"] = "44:2;32:4;65:1";

//������ȡ:
$mid = intval($_GET["mid"]);
if ($mid == 0) exit;

//����ԭ����:
$mm = array();
if ($mc = trim($uinfo["menuclicks"])) {
	$mcs = explode(";", $mc);
	foreach ($mcs as $mci) {
		list($mcid, $mcclick) = explode(":", $mci);
		if ($mcid > 0) {
			$mm[$mcid] = $mcclick;
		}
	}
}

//���뱾�μ���:
$mm[$mid] = intval($mm[$mid]) + 1;

//��������
arsort($mm);

//�ϲ�:
$out = array();
$mmcount = 0;
foreach ($mm as $k => $v) {
	if ($v > 0) {
		$out[] = $k.":".$v;
		if (++$mmcount >= 20) break; //������ǰ20��
	}
}
$menuclicks = implode(";", $out);

echo $menuclicks;

//����:
$db->query("update sys_admin set menuclicks='$menuclicks' where name='$username' limit 1");

exit("1");
?>