<?php
// --------------------------------------------------------
// - ����˵�� : ��ӡ��޸�����
// - �������� : ��ҽս�� 
// - ����ʱ�� : 2010-10-05 13:31
// --------------------------------------------------------
$date = $_REQUEST["date"];

if (!$date) {
	exit("��������");
}

$date = date("Y-m-d", strtotime($date));

if ($username != "admin") {
	//if (!in_array($date, array(date("Y-m-d"), date("Y-m-d", strtotime("-1 day")), date("Y-m-d", strtotime("-2 day")) ) )) {
	//	exit_html("�Բ���ֻ���޸�ǰ�졢�������������");
	//}
}

$kefu = $_GET["kefu"];
if (!$kefu) {
	exit("��������");
}

if ($_POST) {
	$r = array();

	// �ж��Ƿ��Ѿ����:
	$mode = "add";
	$s_date = date("Ymd", strtotime($date." 0:0:0"));
	$kefu = $_POST["kefu"];
	$cur_data = $db->query("select * from $table where type_id=$cur_type and kefu='$kefu' and date='$s_date' limit 1", 1);
	$cur_id = $cur_data["id"];
	if ($cur_id > 0) {
		$mode = "edit";
		$id = $cur_id;
	}

	if ($mode == "add") {
		$r["type_id"] = $cur_type;
		$r["type_name"] = $db->query("select name from count_type where id=".$r["type_id"]." limit 1", 1, "name");
		$r["date"] = $s_date;
		$r["kefu"] = $_POST["kefu"];
	}

	$r["tel_all"] = $_POST["tel_all"];
	$r["tel_ok"] = $_POST["tel_ok"];
	$r["yuyue"] = $_POST["yuyue"];
	$r["jiuzhen"] = $_POST["jiuzhen"];
	$r["wangluo"] = $_POST["wangluo"];
	$r["zazhi"] = $_POST["zazhi"];
	$r["laobao"] = $_POST["laobao"];
	$r["xinbao"] = $_POST["xinbao"];
	$r["t400"] = $_POST["t400"];
	$r["t114"] = $_POST["t114"];
	$r["jieshao"] = $_POST["jieshao"];
	$r["luguo"] = $_POST["luguo"];
	$r["qita"] = $_POST["qita"];

	if ($mode == "add") {
		$r["uid"] = $uid;
		$r["uname"] = $realname;
		$r["addtime"] = time();
	}

	// ������־:
	if ($mode == "add") {
		$r["log"] = date("Y-m-d H:i")." ".$realname." ��Ӽ�¼\r\n";
	} else {
		// ��¼�����޸�����Щ:
		$log_it = array();
		foreach ($r as $x => $y) {
			if ($cur_data[$x] != $y) {
				$log_it[] = $x.":".$cur_data[$x]."=>".$y;
			}
		}
		if (count($log_it) > 0) {
			$r["log"] = $cur_data["log"].date("Y-m-d H:i")." ".$realname." �޸�: ".implode(", ", $log_it)." \r\n";
		}
	}

	$sqldata = $db->sqljoin($r);
	if ($mode == "add") {
		$sql = "insert into $table set $sqldata";
	} else {
		$sql = "update $table set $sqldata where id='$id' limit 1";
	}

	if ($nid = $db->query($sql)) {
		//exit("�ɹ�");
		msg_box("�����ύ�ɹ�", "?date=".$date."&kefu=".urlencode($kefu), 1);
	} else {
		exit("�������ʧ��");
		//msg_box("�����ύʧ�ܣ�ϵͳ��æ�����Ժ����ԡ�", "back", 1, 5);
	}
}

if ($op == "edit") {
	$s_date = date("Ymd", strtotime($date." 0:0:0"));
	$line = $db->query("select * from $table where type_id=$cur_type and kefu='$kefu' and date='$s_date' limit 1", 1);
}


$title = $op == "edit" ? "�޸�����" : "�������";
?>
<html>
<head>
<title><?php echo $title; ?></title>
<meta http-equiv="Content-Type" content="text/html;charset=gb2312">
<link href="/res/base.css" rel="stylesheet" type="text/css">
<script src="/res/base.js" language="javascript"></script>
<style>
.item {padding:8px 3px 6px 3px; }
</style>

<script language="javascript">
function check_data() {
	var oForm = document.mainform;
	if (oForm.code.value == "") {
		alert("�����롰��š���"); oForm.code.focus(); return false;
	}
	return true;
}
</script>
</head>

<body>
<!-- ͷ�� begin -->
<div class="headers">
	<div class="headers_title"><span class="tips"><?php echo $title; ?></span></div>
	<div class="headers_oprate"><button onclick="history.back()" class="button">����</button></div>
</div>
<!-- ͷ�� end -->

<div class="space"></div>

<div class="description">
	<div class="d_title">��ʾ��</div>
	<div class="d_item">��Ҫ������������ϣ�����ύ����</div>
</div>

<div class="space"></div>

<form name="mainform" action="" method="POST" onsubmit="return check_data()">
<table width="100%" class="edit">
	<tr>
		<td colspan="2" class="head">�绰��ϸ����</td>
	</tr>

	<tr>
		<td class="left">�ܵ绰��</td>
		<td class="right">
			<input name="tel_all" value="<?php echo $line["tel_all"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">��Ч��</td>
		<td class="right">
			<input name="tel_ok" value="<?php echo $line["tel_ok"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">ԤԼ��</td>
		<td class="right">
			<input name="yuyue" value="<?php echo $line["yuyue"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">���</td>
		<td class="right">
			<input name="jiuzhen" value="<?php echo $line["jiuzhen"]; ?>" class="input" style="width:100px">
		</td>
	</tr>


	<tr>
		<td colspan="2" class="head">��Դ��ϸ</td>
	</tr>
	<tr>
		<td class="left">���磺</td>
		<td class="right">
			<input name="wangluo" value="<?php echo $line["wangluo"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">��־��</td>
		<td class="right">
			<input name="zazhi" value="<?php echo $line["zazhi"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">�ͱ���</td>
		<td class="right">
			<input name="laobao" value="<?php echo $line["laobao"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">�±���</td>
		<td class="right">
			<input name="xinbao" value="<?php echo $line["xinbao"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">400��</td>
		<td class="right">
			<input name="t400" value="<?php echo $line["t400"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">114��</td>
		<td class="right">
			<input name="t114" value="<?php echo $line["t114"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">���ܣ�</td>
		<td class="right">
			<input name="jieshao" value="<?php echo $line["jieshao"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">·����</td>
		<td class="right">
			<input name="luguo" value="<?php echo $line["luguo"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
	<tr>
		<td class="left">������</td>
		<td class="right">
			<input name="qita" value="<?php echo $line["qita"]; ?>" class="input" style="width:100px">
		</td>
	</tr>
</table>
<input type="hidden" name="linkinfo" value="<?php echo $linkinfo; ?>">
<input type="hidden" name="op" value="<?php echo $op; ?>">
<input type="hidden" name="date" value="<?php echo date("Y-m-d", strtotime($date." 0:0:0")); ?>">
<input type="hidden" name="kefu" value="<?php echo $kefu; ?>">

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
</body>
</html>