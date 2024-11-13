<?php
/*
// - ����˵�� : get_page_info.php
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2013-05-25 01:23
*/
require "../core/core.php";
require "../core/class.fastjson.php";
header("Content-Type:text/html;charset=GB2312");
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");

$timestamp = time();

$page = $_GET["p"];

$findit = 0;
for ($w = 0; $w < 2; $w++) {
	// ��һ������:
	if ($w == 0) {
		$pn = $page;
	}

	// �ڶ�������:
	if ($w == 1) {
		list($pn, $pageinfo) = explode("?", $page, 2);

		$has_id = 0;
		if ($pageinfo) {
			$paras = explode("&", $pageinfo);
			foreach ($paras as $pa) {
				list($n, $v) = explode("=", $pa);
				if ($n == "id" && $v > 0) {
					$has_id = 1;
					break;
				}
			}
		} else {
			list($pn, $other) = explode("#", $page, 2);
		}
	}

	// ����ҳ��:
	if ($p = $db->query_first("select * from sys_menu where link='$pn' or insertpage='$pn' or viewpage='$pn' or editpage='$pn' or checkpage='$pn' limit 1")) {
		$findit = 1;
		$pp = array();
		if ($p["type"] == "0") {
			$pp = $db->query_first("select id,title from sys_menu where type='1' and mid='".$p["mid"]."' limit 1");
		}

		$top_mid = $p["type"] == "0" ? $pp["id"] : $p["id"];
		$left_mid = $p["type"] == "0" ? $p["id"] : 0;

		$navi = $p["type"] == "0" ? ($pp["title"].",".$p["title"]) : $p["title"];

		if ($p["link"] != $pn) {
			if ($p["insertpage"] == $p["editpage"] && $p["editpage"] == $pn) {
				$navi .= $has_id ? ",�޸�" : ",����";
			} elseif ($p["insertpage"] == $pn) {
				$navi .= ",����";
			} elseif ($p["editpage"] == $pn) {
				$navi .= ",�޸�";
			} elseif ($p["viewpage"] == $pn) {
				$navi .= ",�鿴";
			} elseif ($p["checkpage"] == $pn) {
				$navi .= ",���";
			}
		}
	} else {
		$top_mid = $left_mid = 0;
		$navi = '';
	}

	if ($findit) {
		echo FastJSON::convert(array("url"=>$page, "top_mid"=>$top_mid, "left_mid"=>$left_mid, "navi"=>$navi));
		exit;
	}
}
echo "{}";
?>