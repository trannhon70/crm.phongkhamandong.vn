<?php

/*

// - 功能说明 : menu.php

// - 创建作者 : 爱医战队 

// - 创建时间 : 2013-11-20 20:27

*/

require "../../core/core.php";

$table = "sys_notice";



$power = load_class("power", $db);

$oprate = $power->get_oprate();



if ($op) {

	include "sys_notice.do.php";

}



// 定义当前页需要用到的调用参数:

$link_param = array("page","sort","sorttype","key");

foreach ($link_param as $v) {

	if ($v != '') $$v = $_GET[$v];

}



// 定义单元格格式:

$list_heads = array(

	"选" => array("width"=>"4%", "align"=>"center"),

	"标题" => array("width"=>"", "align"=>"left", "sort"=>"title", "order"=>"asc"),

	"开始时间" => array("width"=>"8%", "align"=>"center", "sort"=>"begintime", "order"=>"desc"),

	"结束时间" => array("width"=>"8%", "align"=>"center", "sort"=>"endtime", "order"=>"desc"),

	"发起人" => array("width"=>"15%", "align"=>"center", "sort"=>"", "order"=>"asc"),

	"添加时间" => array("width"=>"8%", "align"=>"center", "sort"=>"addtime", "order"=>"desc"),

	"操作" => array("width"=>"10%", "align"=>"center"),

);



function show_data($t, $li) {

	switch ($t) {

		case "选":

			return '<input name="delcheck" type="checkbox" value="'.$li["id"].'" onclick="set_item_color(this)">';

		case "标题":

			return $li["title"];

		case "开始时间":

			return $li["begintime"] ? date("Y-m-d", $li["begintime"]) : "-";

		case "结束时间":

			return $li["endtime"] ? date("Y-m-d", $li["endtime"]) : "-";

		case "发起人":

			return $li["u_realname"];

		case "添加时间":

			return str_replace(" ", "<br>", date("Y-m-d H:i", $li["addtime"]));

		case "操作":

			$op = array();

			if (check_power("edit")) $op[] = "<a href='?op=edit&id=".$li["id"]."&back_url=".$GLOBALS["back_url"]."' class='op' title='修改内容'>修改</a>";

			if (check_power("delete")) $op[] = "<a href='?op=delete&id=".$li["id"]."' onclick='return isdel()' class='op'>删除</a>";

			return implode($GLOBALS["button_split"], $op);

	}

}



// 默认排序方式:

$defaultsort = "添加时间";

$defaultorder = "desc";





// 查询条件:

$where = array();

if ($key) {

	$where[] = "(title like '%{$key}%' or content like '%{$key}%' or u_realname like '%{$key}%')";

}

$sqlwhere = count($where) > 0 ? ("where ".implode(" and ", $where)) : "";



// 对排序的处理：

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



// 分页数据:

$count = $db->query("select count(*) as count from $table", 1, "count");

$pagecount = max(ceil($count / $pagesize), 1);

$page = max(min($pagecount, intval($page)), 1);

$offset = ($page - 1) * $pagesize;



// sql查询:

$list = $db->query("select * from $table $sqlwhere $sqlsort limit $offset, $pagesize");



$back_url = make_back_url();



// 表格头部:

$table_header = '<tr>';

foreach ($list_heads as $k => $v) {

	list($tdalign, $tdwidth, $tdtitle) = build_table_head($k, $v);

	$table_header .= '<td class="head" align="'.$tdalign.'" width="'.$tdwidth.'">'.$tdtitle.'</td>';

}

$table_header .= '</tr>';



// 表格数据:

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