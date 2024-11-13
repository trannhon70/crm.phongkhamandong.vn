<?php
defined("ROOT") or exit;

// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:
$aLinkInfo = array("page", "sortid", "sorttype", "key");

// ��ȡҳ����ò���:
foreach ($aLinkInfo as $local_var_name) {
	$$local_var_name = $_GET[$local_var_name];
}

// ���嵥Ԫ���ʽ:
$aOrderType = array(0 => "", 1 => "asc", 2 => "desc");
$aTdFormat = array(
	0=>array("title"=>"ѡ", "width"=>"32", "align"=>"center"),
	4=>array("title"=>"ID", "width"=>"50", "align"=>"center"),
	1=>array("title"=>"ҽԺ����", "width"=>"200", "align"=>"left"),
	6=>array("title"=>"�¹���վ", "width"=>"", "align"=>"left"),
	2=>array("title"=>"���ʱ��", "width"=>"100", "align"=>"center"),
	5=>array("title"=>"���ȶ�", "width"=>"80", "align"=>"center"),
	3=>array("title"=>"����", "width"=>"80", "align"=>"center"),
);

// Ĭ������ʽ:
$defaultsort = 5;
$defaultorder = 2;


// ��ѯ����:
$where = array();
if ($key) {
	$where[] = "(binary name like '%{$key}%')";
}
$sqlwhere = count($where) > 0 ? ("where ".implode(" and ", $where)) : "";

// ������Ĵ���
if ($sortid > 0) {
	$sqlsort = "order by ".$aTdFormat[$sortid]["sort"]." ";
	if ($sorttype > 0) {
		$sqlsort .= $aOrderType[$sorttype];
	} else {
		$sqlsort .= $aOrderType[$aTdFormat[$sortid]["defaultorder"]];
	}
} else {
	//if ($defaultsort > 0 && array_key_exists($defaultsort, $aTdFormat)) {
		//$sqlsort = "order by ".$aTdFormat[$defaultsort]["sort"]." ".$aOrderType[$defaultorder];
		$sqlsort = "order by sort desc,id asc";
	//} else {
	//	$sqlsort = "";
	//}
}
//$sqlsort = "order by type desc, sort asc";

// ��ҳ����:
$pagesize = 50;
$count = $db->query_count("select count(*) from $table");
$pagecount = max(ceil($count / $pagesize), 1);
$page = max(min($pagecount, intval($page)), 1);
$offset = ($page - 1) * $pagesize;

// ��ѯ:
$data = $db->query("select * from $table $sqlwhere $sqlsort limit $offset,$pagesize");

// ��ѯ�¹���վ:
$hids = array();
foreach ($data as $k => $v) {
	$hids[] = $v["id"];
}

$hid_str = implode(",", $hids);
$hid_site = array();
if ($hid_str) {
	$urls = $db->query("select id,hid,url from sites where hid in ($hid_str)");
	foreach ($urls as $v) {
		$hid_site[$v["hid"]][] = '<a href="?op=edit_site&hid='.$v["hid"].'&id='.$v["id"].'" class="op">'.$v["url"].'</a>';
	}

	foreach ($data as $k => $v) {
		$data[$k]["sites"] = array();
		if (count($hid_site[$v["id"]]) > 0) {
			$data[$k]["sites"] = $hid_site[$v["id"]];
		}
		$data[$k]["sites"][] = '<a href="?op=add_site&hid='.$v["id"].'" class="op">���</a>';
	}
}

include $mod.".list.tpl.php";

?>