<?php

/*

// - ����˵�� : oa system

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-03-28 14:21

*/



function just_text($content) {

	$content = format_text($content);

	$content = str_replace(" ", "", $content);

	$content = str_replace("\r\n", "", $content);

	$content = str_replace("\r", "", $content);

	$content = str_replace("\n", "", $content);



	return $content;

}



function get_sub_content($content, $length, $overflow_chars='...') {

	$content2 = str_replace("<br>", "\n", $content);

	$content2 = str_replace("<BR>", "\n", $content2);

	$content2 = str_replace("<br />", "\n", $content2);

	$content2 = str_replace("<BR />", "\n", $content2);



	$content2 = format_text($content2);

	$content2 = str_replace("&nbsp;", " ", $content2);

	if (strlen_ch($content2) > ($length + strlen($overflow_chars))) {

		$content2 = strcut_ch($content2, ($length - strlen($overflow_chars))).$overflow_chars;

	}



	return text_show($content2);

}



function strlen_ch($string) {

	if (function_exists("mb_strlen")) {

		return mb_strlen($string, "gb2312");

	} else {

		return strlen($string);

	}

}



function strcut_ch($string, $length) {

	if (function_exists("mb_strcut")) {

		return mb_strcut($string, 0, $length, "gb2312");

	} else {

		return cut($string, $length, '');

	}

}



function check_power($type, $info) {



	// ʵ�� ......



	return true;

}



function parse_question($s, $only_question = 0) {

	$questions = explode(chr(13), str_replace(chr(10), "", trim($s)));

	$q_strings = array();

	foreach ($questions as $qi) {

		$qis = explode("|", $qi, 2);

		$q_strings[] = $only_question ? $qis[0] : $qis;

	}



	return $q_strings;

}



function get_config($code = '') {

	if ($code == "") return "";

	if (time() - $_SESSION["web_config_last_read"] > 10) {

		unset($_SESSION["web_config"]);

	}



	if (!isset($_SESSION["web_config"]) || !is_array($_SESSION["web_config"])) {

		global $db;

		$c = $db->query("select * from config where isshow=1 order by sort desc,binary name asc");

		foreach ($c as $l) {

			$_SESSION["web_config"][$l["code"]] = $l["content"];

		}

		$_SESSION["web_config_last_read"] = time();

	}



	return $_SESSION["web_config"][$code];

}





function header_more($type = "") {

	return "";

}



function check_post_get() {

	$not_allow_post = array(";", "union");

	foreach ($_POST as $key => $value) {

		if (!in_array(strtolower($key), array("content"))) {

			if (! @test_allow($value, $not_allow_post)) {

				return false;

			}

		}

	}



	$not_allow_get = array("union", "'", '"');

	foreach ($_GET as $key => $value) {

		if (! @test_allow($value, $not_allow_get)) {

			return false;

		}

	}



	return true;

}



function test_allow($c_string, $a_notallow) {

	foreach ($a_notallow as $key => $value) {

		if (eregi($value, $c_string)) {

			return false;

		}

	}

	return true;

}



function convert($string, $from_char_set, $to_char_set) {

	if (function_exists("iconv")) {

		return iconv($from_char_set, $to_char_set, $string);

	} else if (function_exists("mb_convert_encoding")) {

		return mb_convert_encoding($string, $to_char_set, $from_char_set);

	} else {

		exit("������php����û�а�װ����ת��������������ϵ����������Ա���������ϵͳ�޷�����...");

	}

}



/*

	$link_array �ǻ������飬$not_used_var ��ʾ�ӻ���������ɾ����Щֵ

	$used_array ��ʾ���ڽ���м�����Щֵ

*/

function make_link_info($link_array, $not_used_var='', $used_array = array()) {

	$not_used_vars = array();

	if ($not_used_var) {

		$not_used_vars = explode(',', $not_used_var);

	}



	$result = array();

	foreach ($link_array as $local_var_name => $call_var_name) {

		global $$local_var_name;

		if ($$local_var_name && !@in_array($call_var_name, $not_used_vars)) {

			$result[] = $call_var_name."=".$$local_var_name;

		}

	}



	foreach ($used_array as $var_name => $var_value) {

		$result[] = $var_name."=".$var_value;

	}

	if (count($result)) {

		$result = '?'.implode("&", $result);

	} else {

		$result = '?';

	}



	return $result;

}



function text_show($string) {

	$string = str_replace(" ", "&nbsp;", $string);

	$string = str_replace(chr(10).chr(13), "<br>", $string);

	$string = str_replace(chr(10), "<br>", $string);

	$string = str_replace(chr(13), "<br>", $string);

	return $string;

}





function in($string, $letter) {

	if (trim($letter) == "") return 0;

	if (strpos($string, $letter) !== false) {

		return 1;

	} else {

		return 0;

	}

}



function get_file_list($dir) {

	$count = 0;

	$files = array();

	if (is_dir($dir)) {

		if ($dh = opendir($dir)) {

			while (($file = readdir($dh)) !== false) {

				$name = $file;

				if (! in_array($file, array(".", ".."))) {

					$files[$count]["prop"] = is_dir($dir.$name) ? 1 : 2;

					$files[$count]["path"] = $dir;

					$files[$count]["name"] = $name;

					$files[$count]["size"] = filesize($dir.$name);

					$count++;

				}

			}

			closedir($dh);

		}

	}

	sort($files);

	return $files;

}



function get_dir_size($dir) {

	$size = 0;

	if (is_dir($dir)) {

		if ($dh = opendir($dir)) {

			while (($file = readdir($dh)) !== false) {

				$name = $dir.$file;

				if (! in_array($file, array(".", ".."))) {

					if (is_dir($name)) {

						$size += get_dir_size($name."/");

					} else {

						$size += filesize($name);

					}

				}

			}

			closedir($dh);

		}

	}

	return $size;

}



// �趨��ʾ��ɫ:

function set_color($title, $color) {

	return strlen(trim($color))>0 ? "<font color='$color'>$title</font>" : $title;

}



// ����ļ�����ʾ�ֽڴ�С:

function get_display_size($filename) {

	$out = "0";

	if ($filename && $nsize = @filesize($filename)) {

		$out = display_size($nsize);

	}

	return $out;

}



// ���ļ����ֽڵ�λת��Ϊ��ʾ��С:

function display_size($nsize) {

	if ($nsize / 1024 > 1) {

		$nsize = $nsize / 1024;

		if ($nsize / 1024 > 1) {

			$nsize = $nsize / 1024;

			if ($nsize / 1024 > 1) {

				$out = num_group(round($nsize / 1024, 2)) . " GB";

			} else {

				$out = num_group(round($nsize, 2)) . " MB";

			}

		} else {

			$out = num_group(round($nsize, 2)) . " KB";

		}

	} else {

		$out = num_group($nsize);

	}



	return $out;

}



// �� "123456789.12" ����Ϊ "123,456,789.12"��ֻ������������

function num_group($num, $numspergroup = 3, $splitchar = ",") {

	$out = "";

	$rightpoint = strrchr($num, ".");

	$leftint = substr($num, 0, strlen($num) - strlen($rightpoint));

	$count = 0xff;

	$now = "";

	$nlen = strlen($leftint);

	for ($ni=0; $ni<$nlen; $ni++) {

		$now = substr($num, $nlen-$ni-1, 1) . $now;

		if (strlen($now) == $numspergroup || $ni == ($nlen - 1)) {

			$anum[$count--] = $now;

			$now = "";

		}

	}

	ksort($anum);



	return implode($splitchar, $anum) . $rightpoint;

}



// ȥ���ı��е�html����:

function format_text($string) {

	$search = array("'<script[^>]*?>.*?</script>'si", "'<[\/\!]*?[^<>]*?>'si", "'([\r\n])[\s]+'", "'&(quot|#34);'i",

		"'&(amp|#38);'i", "'&(lt|#60);'i", "'&(gt|#62);'i", "'&(nbsp|#160);'i", "'&(iexcl|#161);'i", "'&(cent|#162);'i",

		"'&(pound|#163);'i", "'&(copy|#169);'i", "'&#(\d+);'e");

	$replace = array ("", "", "\\1", "\"", "&", "<", ">", " ", chr(161), chr(162), chr(163), chr(169), "chr(\\1)");

	$string = preg_replace($search, $replace, $string);



	return rtrim($string);

}



// ��ȡһ�������ַ�����ƴ��,maxlen�޶����ص���󳤶�,0��ʾ������

function get_pinyin($string, $maxlen=0) {

	$file = dirname(__FILE__)."/pinyin.dat";

	$out = "";

	if (file_exists($file)) {

		$handle = fopen($file, 'r', true);

		$data = fread($handle, filesize($file));

		fclose($handle);



		$aPinYin = explode("*", $data);

		foreach ($aPinYin as $line) {

			list($char, $pinyin) = explode(":", $line);

			if (strpos($pinyin, ",") !== false) {

				list($pinyin) = explode(",", $pinyin);

			}

			$apy[$char] = $pinyin;

		}



		for ($ni=0; $ni<strlen($string); $ni++) {

			$char = substr($string, $ni, 1);

			if (ord($char) > 128) {

				$char = substr($string, $ni++, 2);

				$tmp = ucwords($apy[$char]);

			} else {

				$tmp = $char;

			}

			if ($maxlen > 0 and (strlen($out) + strlen($tmp) > $maxlen)) {

				break;

			}

			$out .= $tmp;

		}

	}



	return $out;

}



// ~~~~~~~~~~ ��������ʽ�ı�ǩʽҳ����(ռ�ý϶��λ�ã��ҳ��ȱ仯�ϴ�)

function pagelink($page, $pagecount, $count=-1, $linkbase='')

{

	$base = $linkbase ? ($linkbase . "&") : "?";

	$pagecount = $pagecount<=0 ? 1 : $pagecount;



	$linksize = 10; $pagebegin = 1; $pageend = $linksize;

	while ($page > $pageend) {

		$pagebegin += $linksize; $pageend += $linksize;

	}

	$pageend = $pageend>$pagecount ? $pagecount : $pageend;



	$pagelink = '<div class="p_page">';

	$pagelink .= '<span class="p_page_info">��<font color="red"><b>'.$page.'</b></font>/<font color="blue"><b>'.$pagecount.'</b></font>ҳ';

	$pagelink .= ($count>-1 ? (' ��<font color="red"><b>'.$count.'</b></font>��') : '').'</span>';

	if ($pagecount > $linksize) {

		$pre10 = $pagebegin - $linksize;

		$pagelink .= ($pagebegin>$linksize ? "<a href='{$base}page=$pre10' title='ǰһ��' onfocus='this.blur()'>ǰ��</a>" : "");

	}

	$pagelink .= ($page>1 ? "<a href='{$base}page=" . ($page - 1) ."' onfocus='this.blur()'>��ҳ</a>" : "<span class='p_none'>��ҳ</span>");

	for ($ni=$pagebegin; $ni<=$pageend; $ni++) {

		$pagelink .= ($ni<>$page ? "<a href='{$base}page=$ni' onfocus='this.blur()' title='��{$ni}ҳ'>{$ni}</a>" : "<span class='p_current'>$ni</span>");

	}

	$pagelink .= ($page<$pagecount ? "<a href='{$base}page=" . ($page + 1) . "' onfocus='this.blur()'>��ҳ ��</a>" : "<span class='p_none'>��ҳ ��</span>");

	if ($pagecount > $linksize) {

		$next10 = $pagebegin + $linksize;

		$pagelink .= ($next10<$pagecount ? "<a href='{$base}page=$next10' title='��һ��' onfocus='this.blur()'>����</a>" : "");

	}

	$pagelink .= '</div>';



	return $pagelink;

}



// ~~~~~~~~~~ ��ť��ѡ���ʽ��ҳ����

function pagelinkc($page, $pagecount, $reccount='-1', $linkbase='', $class='pagelink_button', $selectclass='pagelink')

{

	//$class='pagelink_button'; $selectclass='pagelink';

	$sp = '&nbsp;';

	$bigpage = 200;

	$base = $linkbase ? ($linkbase . "&") : "?";

	$pagelink = "<style>.pagelink{font-size:12px;background-color:#F6F6F6; border:1px solid gray}.pagelink_button{font-size:12px; background:#F3F3F3; padding:2px 0px 0px 0px; height:20px; border:1px solid gray; cursor:pointer}</style>";

	$pagelink .= "<span style='border:1px solid #FFDECE; background:#FFFAF7; height:12px; padding:2px 6px 1px 6px'>��<font color=red><b>$page</b></font>/<font color=blue><b>$pagecount</b></font>ҳ$sp";

	$pagelink .= $reccount > -1 ? ("��<font color='green'><b>$reccount</b></font>��") : "";

	$pagelink .= "</span>".$sp.$sp;

	$useful = $page>1 ? "" : "disabled='true'";

	$pagelink .= "<button onclick=\"location='{$base}page=" . ($page-1) ."'\" $useful class='$class'>��ҳ</button>$sp";

	$useful = $page<$pagecount ? "" : "disabled='true'";

	$pagelink .= "<button onclick=\"location='{$base}page=" . ($page+1) ."'\" $useful class='$class'>��ҳ</button>$sp";



	$pagelink .= "<select name='plcombo' onchange=\"location='{$base}page='+this.value;\" class='$selectclass'>";

	$begin = $pagecount>$bigpage ? max($page-100, 1) : 1;

	$end = $pagecount>$bigpage ? min($page+100, $pagecount) : $pagecount;

	for ($ni=$begin; $ni<=$end; $ni++)

	{

		$value = ($ni==$page ? ($ni . " *") : $ni);

		$select = $ni==$page ? " selected" : "";

		$pagelink .= "<option value='$ni'{$select}>$value";

	}

	$pagelink .= "</select>";

	if ($pagecount > $bigpage)

	{

		$pagelink .= "{$sp}ת����<input name='pltext' class='$class' size=6 onkeydown=\"if (event.keyCode==13){location='{$base}page='+this.value;}\">ҳ$sp";

		$pagelink .= "<button onclick=\"location='{$base}page='+document.getElementById('pltext').value;\" class='$class'>ȷ��</button>";

	}



	return $pagelink;

}



// ~~~~~~~~~~ ��ʽ����(ռ�ý��ٵ�λ�ã��ٶ�Ҳ�Ͽ�)

function pagelinks($page, $pagecount, $linkbase='', $showpageinfo=1)

{

	$sp = '&nbsp;';

	$base = $linkbase ? ($linkbase . "&") : "?";

	$pagelink = $showpageinfo ? "��<font color=red><b>$page</b></font>ҳ ��<font color=red><b>$pagecount</b></font>ҳ$sp$sp" : "";

	$pagelink .= $page>1 ? "<a href='{$base}page=1'>[��ҳ]</a>$sp" : "[��ҳ]$sp";

	$pagelink .= ($page>1 ? "<a href='{$base}page=" . ($page - 1) ."'>[��ҳ]</a>$sp" : "[��ҳ]$sp");

	$pagelink .= ($page<$pagecount ? "<a href='{$base}page=" . ($page + 1) . "'>[��ҳ]</a>$sp" : "[��ҳ]$sp");

	$pagelink .= $page<$pagecount ? "<a href='{$base}page=$pagecount'>[ĩҳ]</a>$sp" : "[ĩҳ]$sp";



	return $pagelink;

}



// ~~~~~~~~~~ ��ͨ��ʽ��ʾҳ�����ӣ�����ǰ̨ҳ��϶�

function pagelinkn($page, $pagecount, $linkbase='', $showpageinfo=0)

{

	$sp = '&nbsp;';

	$base = $linkbase ? ($linkbase . "&") : "?";

	$pagelink = $showpageinfo ? "��<font color=red><b>$page</b></font>/<font color=blue><b>$pagecount</b></font>ҳ$sp$sp" : "";

	$pagelink .= ($page>1 ? "<a href='{$base}page=1'>��ҳ</a>" : "��ҳ") . "$sp|$sp";

	$pagelink .= ($page>1 ? "<a href='{$base}page=" . ($page - 1) ."'>��һҳ</a>$sp" : "��һҳ") . "$sp|$sp";

	$pagelink .= ($page<$pagecount ? "<a href='{$base}page=" . ($page + 1) . "'>��һҳ</a>" : "��һҳ") . "$sp|$sp";

	$pagelink .= ($page<$pagecount ? "<a href='{$base}page=$pagecount'>[ĩҳ]</a>" : "[ĩҳ]") . "$sp";



	return $pagelink;

}



/*

// ����Ч���ģ�

echo pagelinkc($_GET["page"], 100) . "<br><br>" .

	pagelink($_GET["page"], 100) . "<br><br>" .

	pagelinks($_GET["page"], 100) . "<br><br>" .

	pagelinkn($_GET["page"], 100);

*/



// ��ȡͼƬ�ĺ�����ʾ��С:

function proper_size($picturename, $nnewwidth, $nnewheight=0) {

	list($nw, $nh) = @getimagesize($picturename);

	if (($nw > $nnewwidth) || ($nnewheight > 0 && $nh > $nnewheight)) {

		if ($nnewheight > 0) {

			$nrate = min($nnewwidth / $nw, $nnewheight / $nh);

		} else {

			$nrate = $nnewwidth / $nw;

		}

		$nw = $nw * $nrate;

		$nh = $nh * $nrate;

	}



	return "width='" . round($nw) . "' height='" . round($nh) . "'";

}



// url��ת����;

function location($cfilename, $exitrun=0) {

	echo "<script language='javascript'> location='$cfilename'; </script>";

	if ($exitrun) {

		exit;

	}

}



// ���һ�������ʼ��ĸ�ʽ�Ƿ���ȷ:

function is_mail($cmail) {

	return eregi("^[_\.0-9a-z-]+@([0-9a-z][0-9a-z-]+\.)+[a-z]{2,3}$", $cmail);

}



// ��ȡ��ǰ�û���ip��ַ:

function get_ip() {

	$long_ip = $_SERVER["HTTP_X_FORWARDED_FOR"];

	if ($long_ip != "") {

		foreach (explode(",", $long_ip) as $cur_ip) {

			list($ip1, $ip2) = explode(".", $cur_ip, 2);

			if ($ip1 <> "10") {

				return $cur_ip;

			}

		}

	}

	return $_SERVER["REMOTE_ADDR"];

}



// �����ַ������http://��ͷ���Զ����������ͷ���������Ӳ�������ʹ�ã�

function full_link($clinkstring) {

	if (! empty($clinkstring)) {

		if (strtolower(substr($clinkstring, 0, 7)) <> "http://") {

			$clinkstring = "http://" . $clinkstring;

		}

	}

	return $clinkstring;

}



// ��ʾһ����Ϣ����ʾ:

function tip($tipstring) {

	echo "<script language='javascript'> alert('$tipstring'); </script>";

}



function back() {

	echo "<script language='javascript'> history.back(); </script>";

}



function msg_box($Tips, $Action="", $ExitRunning=0, $Timeout=0) {

	$Action =strtolower($Action);

	echo "<script language='javascript'>\n";

	if ($Tips) {

		echo "if (window.parent && window.parent.msg_box) {\n

			window.parent.msg_box(\"".$Tips."\",".$Timeout.");\n

		} else {

			alert(\"".$Tips."\");\n

		}\n";

	}

	if ($Action != "") {

		if ($Action == "back") {

			$next_url = "history.back()";

		} else {

			$next_url = "location='".$Action."'";

		}

		if ($next_url) {

			echo $next_url.";\n";

		}

	}

	echo "</script>\n";

	if ($ExitRunning) {

		exit;

	}

}



// ��ȡ�Ӵ����ú�����Ҫ����˫�ֽ��ַ���ʹ���ȡʱ������ִ���

function cut($str, $len, $cut_flag='...') {

	if(strlen($str) <= $len) {

		return $str;

	}



	$nmax = $cut_flag ? ($len - strlen($cut_flag)) : $len;

	$out = "";

	for($ni = 0; $ni < $nmax; $ni++) {

		$char = substr($str, $ni, 1);

		if(ord($char) > 128) {

			$char .= substr($str, ++$ni, 1);

		}

		if (strlen($out) + strlen($char) <= $nmax) {

			$out .= $char;

		}

	}



	return $out.$cut_flag;

}



function b($string) {return "<b>$string</b>";}



function red($string) {return "<font color='red'>$string</font>";}

function blue($string) {return "<font color='blue'>$string</font>";}

function green($string) {return "<font color='green'>$string</font>";}

function fuchsia($string) {return "<font color='fuchsia'>$string</font>";}

function gold($string) {return "<font color='gold'>$string</font>"; }

function gray($string) {return "<font color='gray'>$string</font>";}

function purple($string) {return "<font color='purple'>$string</font>";}

function aqua($string) {return "<font color='aqua'>$string</font>";}



function color($string, $color) {

	if (trim($color) != "") {

		return "<font color='$color'>$string</font>";

	}

	return $string;

}

?>