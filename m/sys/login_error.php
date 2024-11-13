<?php
/*
// - ����˵�� : ��¼�����¼
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-15 03:11
*/
require "../../core/core.php";
$table = "sys_login_error";

if ($op) {
	include "login_error.do.php";
}

// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:
$link_param = array("page","sort","sorttype","searchword");

foreach ($link_param as $v) {
	if ($v != '') $$v = $_GET[$v];
}

// ���嵥Ԫ���ʽ:

$list_heads = array(
	"ѡ" => array("width"=>"4%", "align"=>"center"),
	"��������" => array("width"=>"20%", "align"=>"center", "sort"=>"binary t.tryname", "order"=>"asc"),
	"��������" => array("width"=>"20%", "align"=>"center", "sort"=>"binary t.trypass", "order"=>"desc"),
	"������IP" => array("width"=>"21%", "align"=>"center", "sort"=>"binary t.userip", "order"=>"desc"),
	"ʱ��" => array("width"=>"15%", "align"=>"center", "sort"=>"t.addtime", "order"=>"desc"),
	"����" => array("width"=>"10%", "align"=>"center"),
);

function show_data($t, $li) {
	switch ($t) {
		case "ѡ":
			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';
		case "��������":
			return $li["tryname"];
		case "��������":
			return $li["trypass"];
		case "������IP":
			return $li["userip"];
		case "ʱ��":
			return date("Y-m-d H:i", $li["addtime"]);
		case "����":
			$op = array();
			if (check_power("delete")) {
				$op[] = "<a href='?op=delete&id=".$li["id"]."' onclick='return isdel()' class='op'>ɾ��</a>";
			}
			return implode($GLOBALS["button_split"], $op);
		default:
			return '';
	}
}

// Ĭ������ʽ:
$defaultsort = "ʱ��";
$defaultorder = "desc";


// ��ѯ����:
$where = array();
if ($searchword) {
	$where[] = "(binary t.tryname like '%{$searchword}%' or binary t.trypass like '%{$searchword}%')";
}
$sqlwhere = count($where) > 0 ? ("where ".implode(" and ", $where)) : "";

// ������Ĵ���
$sqlsort = "";
if (!in_array($sorttype, array("", "asc", "desc"))) {
	$sorttype = "asc";
}
if ($sort) {
	$sqlsort = "order by ".$list_heads[$sort]["sort"]." ";
	$sqlsort .= $sorttype ? $sorttype : $list_heads[$sort]["order"];
} else {
	if ($defaultsort && array_key_exists($defaultsort, $list_heads)) {
		$sqlsort = "order by ".$list_heads[$defaultsort]["sort"]." ".$defaultorder;
	}
}


// ��ҳ����:
$count = $db->query_count("select count(*) from $table t $sqlwhere");
$pagecount = max(ceil($count / $pagesize), 1);
$page = max(min($pagecount, intval($page)), 1);
$offset = ($page - 1) * $pagesize;

// sql��ѯ:
$list = $db->query("select * from $table t $sqlwhere $sqlsort limit $offset, $pagesize");
if (!is_array($list)) {
	exit("Error: ".$db->sql);
}

// ���ͷ��:
$table_header = '<tr>';
foreach ($list_heads as $k => $v) {
	list($tdalign, $tdwidth, $tdtitle) = build_table_head($k, $v);
	$table_header .= '<td class="head" align="'.$tdalign.'" width="'.$tdwidth.'">'.$tdtitle.'</td>';
}
$table_header .= '</tr>';

// �������:
$table_items = array();
foreach ($list as $li) {

	$show_line = get_line_show($li, $pinfo);
	$item_data = '<tr id="#'.$li["id"].'"'.($show_line ? '' : ' class="hide"').' onmouseover="mi(this)" onmouseout="mo(this)">';
	foreach ($list_heads as $k => $v) {
		$tdalign = $v["align"];
		$item_data .= '<td class="item"'.($tdalign ? ' align="'.$tdalign.'"' : '').'>';
		$item_data .= show_data($k, $li);
		$item_data .= '</td>';
	}
	$item_data .= '</tr>';

	$table_items[] = $item_data;
}

$pagelink = pagelinkc($page, $pagecount, $count, make_link_info($link_param, "page"), "button");

include "login_error.list.php";
?>