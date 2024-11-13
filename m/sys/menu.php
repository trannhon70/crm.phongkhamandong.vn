<?php

/*

// - ����˵�� : menu.php

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-11-20 20:27

*/

require "../../core/core.php";

$table = "sys_menu";



$power = load_class("power", $db);

$oprate = $power->get_oprate();



if ($op) {

	include "menu.do.php";

}



// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:

$link_param = array("page","sort","sorttype","searchword");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// ���嵥Ԫ���ʽ:

$list_heads = array(

	"ѡ" => array("width"=>"4%", "align"=>"center"),

	"����" => array("width"=>"5%", "align"=>"center", "sort"=>"", "order"=>"asc"),

	"���" => array("width"=>"5%", "align"=>"center", "sort"=>"", "order"=>"asc"),

	"����?" => array("width"=>"6%", "align"=>"center", "sort"=>"", "order"=>"desc"),

	"����" => array("width"=>"15%", "align"=>"left", "sort"=>"", "order"=>"asc"),

	"����" => array("width"=>"25%", "align"=>"left", "sort"=>"", "order"=>"asc"),

	"����˵��" => array("width"=>"30%", "align"=>"left", "sort"=>"", "order"=>"asc"),

	"����" => array("width"=>"10%", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "ѡ":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "����":

			return $li["sort"];

		case "���":

			return $li["mid"];

		case "����?":

			return $li["type"] > 0 ? "<b>��</b>" : "";

		case "����":

			return $li["title"];

		case "����":

			return $li["link"];

		case "����˵��":

			return $li["tips"];

		case "����":

			$op = array();

			if (check_power("edit")) $op[] = "<a href='?op=edit&id=".$li["id"]."&back_url=".$GLOBALS["back_url"]."' class='op' title='�޸�����'>�޸�</a>";

			if (check_power("delete")) $op[] = "<a href='?op=delete&id=".$li["id"]."' onclick='return isdel()' class='op'>ɾ��</a>";

			return implode($GLOBALS["button_split"], $op);

		default:

			return '';

	}

}



// Ĭ������ʽ:

$defaultsort = "����";

$defaultorder = "asc";





// ��ѯ����:

$where = array();

if ($searchword) {

	$where[] = "(binary t.title like '%{$searchword}%' or binary t.link like '%{$searchword}%' or binary tips like '%{$searchword}%')";

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



//$sqlsort = "order by type desc, sort asc";



// ��ҳ����:

$pagesize = 9999;

$count = $db->query_count("select count(*) from $table");

$pagecount = max(ceil($count / $pagesize), 1);

$page = max(min($pagecount, intval($page)), 1);

$offset = ($page - 1) * $pagesize;



// sql��ѯ:

$list = array();

$tm = $db->query("select * from $table where type=1 order by sort asc,id asc");

foreach ($tm as $tml) {

	$list[] = $tml;

	$tm2 = $db->query("select * from $table where mid=".$tml["mid"]." and type=0 order by sort asc, id asc");

	foreach ($tm2 as $tml2) {

		$list[] = $tml2;

	}

}



$back_url = make_back_url();



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



include "menu.list.php";

?>