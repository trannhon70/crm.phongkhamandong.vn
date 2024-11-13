<?php

/*

// - ����˵�� : character.php

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-05-15 02:19

*/

require "../../core/core.php";

$table = "sys_character";



if ($op) {

	include "character.do.php";

}



// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:

$link_param = array("page","sort","sorttype","searchword");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// ���嵥Ԫ���ʽ:

$list_heads = array(

	"ѡ" => array("width"=>"4%", "align"=>"center"),

	"����" => array("width"=>"11%", "align"=>"center", "sort"=>"binary t.name", "order"=>"asc"),

	"��ǰʹ����" => array("width"=>"", "align"=>"left", "sort"=>""),

	"ʱ��" => array("width"=>"15%", "align"=>"center", "sort"=>"binary t.addtime", "order"=>"desc"),

	"����" => array("width"=>"12%", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "ѡ":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "����":

			return $li["name"];

		case "��ǰʹ����":

			return @implode(" | ", $GLOBALS["ch_info"][$li["id"]]);

		case "ʱ��":

			return date("Y-m-d H:i", $li["addtime"]);

		case "����":

			$op = array();

			if (check_power("view")) {

				$op[] = "<a href='?op=view&id=".$li["id"]."' class='op' title='�鿴����'>�鿴</a>";

			}

			if ($li["power_compare"] < 0) {

				if (check_power("edit")) {

					$op[] = "<a href='?op=edit&id=".$li["id"]."&back_url=".$GLOBALS["back_url"]."' class='op' title='�޸�����'>�޸�</a>";

				}

				if (check_power("delete")) {

					$op[] = "<a href='?op=delete&id=".$li["id"]."' onclick='return isdel()' class='op'>ɾ��</a>";

				}

			}

			return implode($GLOBALS["button_split"], $op);

		default:

			return '';

	}

}



// Ĭ������ʽ:

$defaultsort = "ʱ��";

$defaultorder = "asc";





// ��ѯ����:

$where = array();

if ($searchword) {

	$where[] = "(binary t.name like '%{$searchword}%' or binary t.author like '%{$searchword}%')";

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



$my_power = $power->get_user_power($username, $uinfo);



// ����ɸѡ:

$all_list = $db->query("select * from $table t $sqlwhere");

echo "<!--";
print_r("select * from $table t $sqlwhere");
echo "-->";

$use_list = array();

foreach ($all_list as $li) {

	$li["power_compare"] = $power->compare_power($li["menu"], $my_power);

	if ($li["power_compare"] <= 0) {

		$use_list[] = $li;

	}

}



// ��ҳ����:

$count = count($use_list);

$pagecount = max(ceil($count / $pagesize), 1);

$page = max(min($pagecount, intval($page)), 1);

$offset = ($page - 1) * $pagesize;



// sql��ѯ:

//$list = $db->query("select * from $table t $sqlwhere $sqlsort limit $offset, $pagesize");

$list = array_slice($use_list, $offset, $pagesize);

if (!is_array($list)) {

	exit("Error: ".$db->sql);

}



// admin ��Ϣ:

$tm_admins = $db->query("select id,name,realname,character_id from sys_admin where powermode='2' order by binary realname");

$ch_info = array();

foreach ($tm_admins as $tm_ad_info) {

	$ch_info[$tm_ad_info["character_id"]][] = $tm_ad_info["realname"]."(".$tm_ad_info["name"].")";

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



include "character.list.php";

?>