<?php

/*

// ˵��: admin.list

// ����: ��ҽս�� 

// ʱ��: 2010-07-06

*/



// ���嵱ǰҳ��Ҫ�õ��ĵ��ò���:

$link_param = array("page","sort","sorttype","searchword");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// ���嵥Ԫ���ʽ:

$list_heads = array(

	"ѡ" => array("width"=>"32", "align"=>"center"),

	"��¼��" => array("width"=>"80", "align"=>"center", "sort"=>"binary t.name", "order"=>"asc"),

	"��ʵ����" => array("width"=>"80", "align"=>"center", "sort"=>"binary t.realname", "order"=>"asc"),

	"Ȩ��" => array("width"=>"80", "align"=>"center", "sort"=>""),

	"ҽԺ" => array("width"=>"", "align"=>"left", "sort"=>"binary t.hospitals", "order"=>"asc"),

	"��¼����" => array("width"=>"80", "align"=>"center", "sort"=>"t.logintimes", "order"=>"desc"),

	"����" => array("width"=>"60", "align"=>"center", "sort"=>"t.online ", "order"=>"desc"),

	"����" => array("width"=>"150", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "ѡ":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "��¼��":

			return $li["name"];

		case "��ʵ����":

			return $li["realname"];

		case "Ȩ��":

			return $li["powermode"] == 1 ? "<font color='gray'>ֱ���趨</font>" : ("<a href='character.php?op=view&id=".$li["character_id"]."'>".$GLOBALS["ch_list"][$li["character_id"]]."</a>");

		case "ҽԺ":

			if ($li["name"] == "admin") {

				return '<font color="gray">����ҽԺ</font>';

			} else {

				$hospitals = $GLOBALS["power"]->parse_hospitals($li["hospitals"]);

				$shows = array();

				foreach ($hospitals as $k => $v) {

					$shows[] = $GLOBALS["hospital_id_name"][$k];

				}

				return implode(" | ", $shows);

			}

		case "�ϴε�¼":

			return $li["thislogin"] > 0 ? date("Y-m-d H:i", $li["thislogin"]) : '-';

		case "��¼����":

			return $li["logintimes"];

		case "����":

			return $li["online"] > 0 ? "��" : "-";

		case "����":

			$op = array();

			if (check_power("view")) $op[] = "<a href='?op=view&id=".$li["id"]."' class='op'>�鿴</a>";

			if ($li["power_compare"] <= 0) {

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

$defaultsort = "����";

$defaultorder = "desc";





// ��ѯ����:

$where = array();

if ($searchword) {

	$where[] = "(binary t.name like '%{$searchword}%' or binary t.realname like '%{$searchword}%')";

}

//if (($show == "" || $show == "cur_hospital") && $hospital_id > 0) {

	//$where[] = "concat(',',hospitals,',') like '%,".$hospital_id.",%'";

//}

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

	//if ($defaultsort && array_key_exists($defaultsort, $list_heads)) {

	//	$sqlsort = "order by ".$list_heads[$defaultsort]["sort"]." ".$defaultorder;

	//}

	$sqlsort = "order by is_curhospital desc, online desc, id asc";

}



// hostpital_id_name:

$hospital_id_name = $db->query("select id,name from hospital", "id", "name");



// ��ʼ����ɫ����:

if (!$power->ch_data) {

	$power->init_ch_data();

}



// �ҵ�Ȩ��:

$my_power = $power->get_user_power($username, $uinfo);



// ɸѡ����Ա:

$admin_list = $db->query("select *,if(concat(',',hospitals,',') like '%,".intval($hostpital_id).",%' or name='admin',1,0) as is_curhospital from $table t $sqlwhere $sqlsort", "id");

$use_list = array();

foreach ($admin_list as $k => $li) {

	$li["power_compare"] = $power->compare_user($li, $uinfo);

	$li["hospitals_compare"] = $power->compare_hospitals($li, $uinfo);



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

$list = array_slice($use_list, $offset, $pagesize);



// ���ݷ���:

if ($hospital_id > 0) {

	$groups = array();

	foreach ($list as $k => $v) {

		$groups[$v["is_curhospital"]][] = $v;

	}



	$list = array();

	$list[] = array('id'=>0, "name"=>$hospital_name." ������Ա [".count($groups[1]).']');

	$list = array_merge($list, $groups[1]);

	$list[] = array('id'=>0, "name"=>"����������Ա [".count($groups[0]).']');

	$list = array_merge($list, $groups[0]);

}



if (!is_array($list)) {

	exit("Error: ".$db->sql);

}



// ��ɫϵͳ���ݣ�

//$ch_list = $db->query("select id,name from sys_character order by binary name", "id", "name");

$ch_list = $power->ch_id_name;



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

	if ($li["id"] > 0) {

		$show_line = get_line_show($li, $pinfo);

		$item_data = '<tr id="#'.$li["id"].'"'.($show_line ? '' : ' class="hide"').' onmouseover="mi(this)" onmouseout="mo(this)">';

		foreach ($list_heads as $k => $v) {

			$tdalign = $v["align"];

			$item_data .= '<td class="item"'.($tdalign ? ' align="'.$tdalign.'"' : '').'>';

			$item_data .= show_data($k, $li);

			$item_data .= '</td>';

		}

		$item_data .= '</tr>';

	} else {

		$item_data = '<tr class="line_tips"><td colspan="'.count($list_heads).'">'.$li["name"].'</td></tr>';

	}



	$table_items[] = $item_data;

}



$pagelink = pagelinkc($page, $pagecount, $count, make_link_info($link_param, "page"), "button");



include "admin.list.tpl.php";



?>