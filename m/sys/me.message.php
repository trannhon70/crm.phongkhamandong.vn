<?php

/*

// - ����˵�� : sys_message_list

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-04-28 21:05

*/

require "../../core/core.php";

$table = "sys_message";



check_power('', $pinfo) or msg_box("û�д�Ȩ��...", "back", 1);



if ($op) {

	include "me.message.do.php";

}



// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:

$link_param = array("page","sort","sorttype","searchword");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// ���嵥Ԫ���ʽ:

$list_heads = array(

	"ѡ" => array("width"=>"4%", "align"=>"center"),

	"״̬" => array("width"=>"5%", "align"=>"center"),

	"������" => array("width"=>"8%", "align"=>"center", "sort"=>"binary fromname", "order"=>"asc"),

	"������" => array("width"=>"8%", "align"=>"center", "sort"=>"binary toname", "order"=>"asc"),

	"��Ϣ����" => array("width"=>"45%", "align"=>"center", "sort"=>"binary content", "order"=>"asc"),

	"ʱ��" => array("width"=>"20%", "align"=>"center", "sort"=>"addtime", "order"=>"asc"),

	"����" => array("width"=>"10%", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "ѡ":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "״̬":

			return $li["flag1"] > 0 ? "<b><font color=red>�£�</font></b>" : "-";

		case "������":

			return $GLOBALS["name_2_nick"][$li["fromname"]];

		case "������":

			return $GLOBALS["name_2_nick"][$li["toname"]];

		case "��Ϣ����":

			return $li["link"] ? ('<a href="'.$li["link"].'">'.$li["content"].'</a>') : $li["content"];

		case "ʱ��":

			return date("Y-m-d H:i:s", $li["addtime"]);

		case "����":

			$op = array();

			if (check_power("view")) $op[] = "<a href='?op=view&id=".$li["id"]."' class='op' title='�鿴'>�鿴</a>";

			if (check_power("edit")) $op[] = "<a href='?op=edit&id=".$li["id"]."&back_url=".$GLOBALS["back_url"]."' class='op' title='�޸�����'>�޸�</a>";

			if (check_power("delete")) $op[] = "<a href='?op=delete&id=".$li["id"]."' onclick='return isdel()' class='op'>ɾ��</a>";

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

$where[] = "(binary fromname='{$username}' or binary toname='{$username}')";

if ($searchword) {

	$where[] = "(binary content like '%{$searchword}%')";

}

$sqlwhere = count($where) > 0 ? ("where ".implode(" and ", $where)) : "";



// ������Ĵ���

if ($sortid > 0) {

	$sqlsort = "order by ".$list_heads[$sortid]["sort"]." ";

	if ($sorttype > 0) {

		$sqlsort .= $aOrderType[$sorttype];

	} else {

		$sqlsort .= $aOrderType[$list_heads[$sortid]["defaultorder"]];

	}

} else {

	if ($defaultsort > 0 && array_key_exists($defaultsort, $list_heads)) {

		$sqlsort = "order by flag1 desc,".$list_heads[$defaultsort]["sort"]." ".$aOrderType[$defaultorder];

	} else {

		$sqlsort = "";

	}

}



// ��ҳ����:

$count = $db->query_count("select count(*) from $table $sqlwhere");

$pagecount = max(ceil($count / $pagesize), 1);

$page = max(min($pagecount, intval($page)), 1);

$offset = ($page - 1) * $pagesize;



// sql��ѯ:

$list = $db->query("select *,if(readtime=0 and toname='$username',1,0) as flag1 from $table $sqlwhere $sqlsort limit $offset, $pagesize");

if (!is_array($list)) {

	exit("Error: ".$db->sql);

}



// ��¼�� - ��ʵ���� �������ݣ�

$name_2_nick = $db->query("select name,realname from sys_admin", "name", "realname");



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

	$li["content"] = face_show($li["content"]);



	$show_line = get_line_show($li, $pinfo);

	$item_data = '<tr id="#'.$li["id"].'"'.($show_line ? '' : ' class="hide"').'>';

	foreach ($list_heads as $k => $v) {

		$tdalign = $v["align"];

		$item_data .= '<td class="item"'.($tdalign ? ' align="'.$tdalign.'"' : '').'>';

		$item_data .= show_data($k, $li);

		$item_data .= '</td>';

	}

	$item_data .= '</tr>';



	$table_items[] = $item_data;

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



include "me.message.list.php";

?>