<?php

/*

// - ����˵�� : menu.php

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-11-20 20:27

*/

require "../../core/core.php";

$table = "sys_notice";



$power = load_class("power", $db);

$oprate = $power->get_oprate();



if ($op) {

	include "sys_notice.do.php";

}



// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:

$link_param = array("page","sort","sorttype","key");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// ���嵥Ԫ���ʽ:

$list_heads = array(

	"ѡ" => array("width"=>"4%", "align"=>"center"),

	"����" => array("width"=>"", "align"=>"left", "sort"=>"title", "order"=>"asc"),

	"��ʼʱ��" => array("width"=>"8%", "align"=>"center", "sort"=>"begintime", "order"=>"desc"),

	"����ʱ��" => array("width"=>"8%", "align"=>"center", "sort"=>"endtime", "order"=>"desc"),

	"������" => array("width"=>"15%", "align"=>"center", "sort"=>"", "order"=>"asc"),

	"���ʱ��" => array("width"=>"8%", "align"=>"center", "sort"=>"addtime", "order"=>"desc"),

	"����" => array("width"=>"10%", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "ѡ":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "����":

			return $li["title"];

		case "��ʼʱ��":

			return $li["begintime"] ? date("Y-m-d", $li["begintime"]) : "-";

		case "����ʱ��":

			return $li["endtime"] ? date("Y-m-d", $li["endtime"]) : "-";

		case "������":

			return $li["u_realname"];

		case "���ʱ��":

			return str_replace(" ", "<br>", date("Y-m-d H:i", $li["addtime"]));

		case "����":

			$op = array();

			if (check_power("edit")) $op[] = "<a href='?op=edit&id=".$li["id"]."&back_url=".$GLOBALS["back_url"]."' class='op' title='�޸�����'>�޸�</a>";

			if (check_power("delete")) $op[] = "<a href='?op=delete&id=".$li["id"]."' onclick='return isdel()' class='op'>ɾ��</a>";

			return implode($GLOBALS["button_split"], $op);

	}

}



// Ĭ������ʽ:

$defaultsort = "���ʱ��";

$defaultorder = "desc";





// ��ѯ����:

$where = array();

if ($key) {

	$where[] = "(title like '%{$key}%' or content like '%{$key}%' or u_realname like '%{$key}%')";

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

$count = $db->query("select count(*) as count from $table", 1, "count");

$pagecount = max(ceil($count / $pagesize), 1);

$page = max(min($pagecount, intval($page)), 1);

$offset = ($page - 1) * $pagesize;



// sql��ѯ:

$list = $db->query("select * from $table $sqlwhere $sqlsort limit $offset, $pagesize");



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



include "sys_notice.list.php";

?>