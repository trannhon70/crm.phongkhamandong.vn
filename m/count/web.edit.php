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

	$r["click"] = $_POST["click"];
	$r["click_local"] = $_POST["click_local"];
	$r["click_other"] = $_POST["click_other"];

	$r["ok_click"] = $_POST["ok_click"];
	$r["ok_click_local"] = $_POST["ok_click_local"];
	$r["ok_click_other"] = $_POST["ok_click_other"];

	$r["talk"] = $_POST["talk"];
	$r["talk_local"] = $_POST["talk_local"];
	$r["talk_other"] = $_POST["talk_other"];

	$r["orders"] = $_POST["orders"];
	$r["order_local"] = $_POST["order_local"];
	$r["order_other"] = $_POST["order_other"];

	$r["come"] = $_POST["come"];
	$r["come_local"] = $_POST["come_local"];
	$r["come_other"] = $_POST["come_other"];

	if ($mode == "add") {
		$r["addtime"] = time();
		$r["uid"] = $uid;
		$r["u_realname"] = $realname;
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
		msg_box("�����ύ�ɹ�", "?date=".$date."&kefu=".urlencode($kefu), 1);
	} else {
		exit("�������ʧ��");
	}
}


//if ($op == "add") {
	// ��ӦҽԺID:
	$hs_id = $type_detail["hid"];

	// ��ȡ������Լ��������
	$day_begin = strtotime($date." 0:0:0");
	$day_end = strtotime($date." 23:59:59");

	if ($hs_id > 0) {
		// ����Լ:
		$talk_all = $db->query("select count(*) as count from patient_{$hs_id} where part_id=2 and from_account='$cur_type' and addtime>=$day_begin and addtime<=$day_end and author='$kefu'", 1, "count");
		// ����Լ - ����:
		$talk_local = $db->query("select count(*) as count from patient_{$hs_id} where part_id=2 and from_account='$cur_type' and addtime>=$day_begin and addtime<=$day_end and author='$kefu' and is_local=1", 1, "count");
		// ����Լ - ��أ�
		$talk_other = $talk_all - $talk_local;

		// Ԥ�Ƶ�Ժ:
		$order_all = $db->query("select count(*) as count from patient_{$hs_id} where part_id=2 and from_account='$cur_type' and order_date>=$day_begin and order_date<=$day_end and author='$kefu'", 1, "count");
		// Ԥ�Ƶ�Ժ - ����:
		$order_local = $db->query("select count(*) as count from patient_{$hs_id} where part_id=2 and from_account='$cur_type' and order_date>=$day_begin and order_date<=$day_end and author='$kefu' and is_local=1", 1, "count");
		// Ԥ�Ƶ�Ժ - ��أ�
		$order_other = $order_all - $order_local;

		// ʵ�ʵ�Ժ:
		$come_all = $db->query("select count(*) as count from patient_{$hs_id} where part_id=2 and from_account='$cur_type' and order_date>=$day_begin and order_date<=$day_end and author='$kefu' and status=1", 1, "count");
		// ʵ�ʵ�Ժ: - ����:
		$come_local = $db->query("select count(*) as count from patient_{$hs_id} where part_id=2 and from_account='$cur_type' and order_date>=$day_begin and order_date<=$day_end and author='$kefu' and is_local=1 and status=1", 1, "count");
		// ʵ�ʵ�Ժ: - ��أ�
		$come_other = $come_all - $come_local;
	}
//}


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

function update_data() {
	for (var i = 1; i <= 9; i++) {
		byid("dt"+i).value = byid("d"+i).innerHTML;
	}
}

function update_cnt(o, id_a, id_b, id_c) {
	var a = byid(id_a).value;
	var b = byid(id_b).value;
	var c = byid(id_c).value;

	var cnt = (a != "" ? 1 : 0) + (b != "" ? 1 : 0) + (c != "" ? 1 : 0);

	if (cnt == 2 && (a == "" || b == "" || c == "")) {
		if (a == "") {
			byid(id_a).value = parseInt(b) + parseInt(c);
		} else if (b == "") {
			byid(id_b).value = a - c;
		} else {
			byid(id_c).value = a - b;
		}
	}
	if (cnt == 3) {
		if (o.id == id_a) {
			byid(id_c).value = a - b;
		} else if (o.id == id_b) {
			byid(id_c).value = a - b;
		} else {
			byid(id_b).value = a - c;
		}
	}
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
		<td colspan="2" class="head">������ϸ����</td>
	</tr>

	<tr>
		<td class="left">�ܵ����</td>
		<td class="right">
			<input name="click" id="c_a" onchange="update_cnt(this,'c_a', 'c_b', 'c_c')" value="<?php echo $line["click"]; ?>" class="input" style="width:100px">
			��=�����أ�<input name="click_local" id="c_b" onchange="update_cnt(this,'c_a', 'c_b', 'c_c')" value="<?php echo $line["click_local"]; ?>" class="input"style="width:100px">
			��+����أ�<input name="click_other" id="c_c" onchange="update_cnt(this,'c_a', 'c_b', 'c_c')" value="<?php echo $line["click_other"]; ?>" class="input" style="width:100px">
		</td>
	</tr>

	<tr>
		<td class="left">����Ч��</td>
		<td class="right">
			<input name="ok_click" id="d_a" onchange="update_cnt(this,'d_a', 'd_b', 'd_c')" value="<?php echo $line["ok_click"]; ?>" class="input" style="width:100px">
			��=�����أ�<input name="ok_click_local" id="d_b" onchange="update_cnt(this,'d_a', 'd_b', 'd_c')" value="<?php echo $line["ok_click_local"]; ?>" class="input"style="width:100px">
			��+����أ�<input name="ok_click_other" id="d_c" onchange="update_cnt(this,'d_a', 'd_b', 'd_c')" value="<?php echo $line["ok_click_other"]; ?>" class="input" style="width:100px">
		</td>
	</tr>

<?php if ($op == "edit") { ?>
	<tr>
		<td class="left"></td>
		<td class="right">
			<button type="button" class="buttonb" onclick="update_data()">����</button>&nbsp;&nbsp;&nbsp;&nbsp;(��������������������Ǵ�ԤԼ���˹�����ѯ�ó�)
		</td>
	</tr>
<?php } ?>

	<tr>
		<td class="left">����Լ��</td>
		<td class="right">
			<input name="talk" id="dt1" value="<?php echo $op == "add" ? $talk_all : $line["talk"]; ?>" class="input" style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d1"><?php echo $talk_all; ?></span>)<?php } ?>
			��=�����أ�<input name="talk_local" id="dt2" value="<?php echo $op == "add" ? $talk_local : $line["talk_local"]; ?>" class="input"style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d2"><?php echo $talk_local; ?></span>)<?php } ?>
			��+����أ�<input name="talk_other" id="dt3" value="<?php echo $op == "add" ? $talk_other : $line["talk_other"]; ?>" class="input" style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d3"><?php echo $talk_other; ?></span>)<?php } ?>
		</td>
	</tr>

	<tr>
		<td class="left">Ԥ�Ƶ�Ժ��</td>
		<td class="right">
			<input name="orders" id="dt4" value="<?php echo $op == "add" ? $order_all : $line["orders"]; ?>" class="input" style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d4"><?php echo $order_all; ?></span>)<?php } ?>
			��=�����أ�<input name="order_local" id="dt5" value="<?php echo $op == "add" ? $order_local : $line["order_local"]; ?>" class="input"style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d5"><?php echo $order_local; ?></span>)<?php } ?>
			��+����أ�<input name="order_other" id="dt6" value="<?php echo $op == "add" ? $order_other : $line["order_other"]; ?>" class="input" style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d6"><?php echo $order_other; ?></span>)<?php } ?>
		</td>
	</tr>

	<tr>
		<td class="left">ʵ�ʵ�Ժ��</td>
		<td class="right">
			<input name="come" id="dt7" value="<?php echo $op == "add" ? $come_all : $line["come"]; ?>" class="input" style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d7"><?php echo $come_all; ?></span>)<?php } ?>
			��=�����أ�<input name="come_local" id="dt8" value="<?php echo $op == "add" ? $come_local : $line["come_local"]; ?>" class="input"style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d8"><?php echo $come_local; ?></span>)<?php } ?>
			��+����أ�<input name="come_other" id="dt9" value="<?php echo $op == "add" ? $come_other : $line["come_other"]; ?>" class="input" style="width:100px">
			<?php if ($op == "edit") { ?>(<span id="d9"><?php echo $come_other; ?></span>)<?php } ?>
		</td>
	</tr>

</table>
<input type="hidden" name="op" value="<?php echo $op; ?>">
<input type="hidden" name="date" value="<?php echo date("Y-m-d", strtotime($date." 0:0:0")); ?>">
<input type="hidden" name="kefu" value="<?php echo $kefu; ?>">

<div class="button_line"><input type="submit" class="submit" value="�ύ����"></div>
</form>
</body>
</html>