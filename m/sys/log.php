<?php

/*

// - ����˵�� : ������־

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-05-15 02:32

*/

require "../../core/core.php";

$table = "sys_log";



// ���� uid (2011-01-05):

$db->query("update sys_log g, sys_admin u set g.uid=u.id where g.uid=0 and g.username=u.name");



// ��ҽԺ��ȡ��־:

$tm_uids_str = '';

if ($hid > 0) {

	$tm_uids = $db->query("select id from sys_admin where concat(',',hospitals,',') like '%,{$hid},%'", "", "id");

	if (count($tm_uids) > 0) {

		$tm_uids[] = 1; //1 ��admin��uid

	}

	$tm_uids_str = implode(",", $tm_uids);

}





if ($op) {

	include "log.do.php";

}



// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:

$link_param = array("page","sort","sorttype","searchword");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// ���嵥Ԫ���ʽ:

$list_heads = array(

	"ѡ" => array("width"=>"4%", "align"=>"center"),

	"����" => array("width"=>"10%", "align"=>"center", "sort"=>"binary t.type", "order"=>"asc"),

	"˵��" => array("width"=>"25%", "align"=>"left", "sort"=>"binary t.title", "order"=>"asc"),

	"ҳ��" => array("width"=>"26%", "align"=>"left", "sort"=>"binary t.url", "order"=>"desc"),

	"�û�" => array("width"=>"10%", "align"=>"center", "sort"=>"binary t.username", "order"=>"desc"),

	"ʱ��" => array("width"=>"15%", "align"=>"center", "sort"=>"t.addtime", "order"=>"desc"),

	"����" => array("width"=>"10%", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "ѡ":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "����":

			return $li["type"];

		case "˵��":

			return $li["title"];

		case "ҳ��":

			return $li["url"];

		case "�û�":

			return $li["username"];

		case "ʱ��":

			return date("Y-m-d H:i", $li["addtime"]);

		case "����":

			$op = array();

			if (check_power("view")) {

				$op[] = "<a href='?op=view&id=".$li["id"]."' class='op'>�鿴</a>";

			}

			if (check_power("edit")) {

				$op[] = "<a href='?op=edit&id=".$li["id"]."&back_url=".$GLOBALS["back_url"]."' class='op' title='�޸�����'>�޸�</a>";

			}

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

	$where[] = "(binary t.title like '%{$searchword}%' or binary t.username like '%{$searchword}%' or binary t.data like '%{$searchword}%')";

}



if ($tm_uids_str) {

	$where[] = "uid in (".$tm_uids_str.")";

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



include "log.list.php";

?>