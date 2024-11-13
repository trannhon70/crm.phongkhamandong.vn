<?php

/*
// ˵��: ��鲢���±��ֶ�
// ����: ��ҽս�� 
// ʱ��: 2010-10-21 14:45
*/

require "../../core/core.php";


// ��һ�������������н���ֶ�

function parse_fields($s) {

	$list = explode("\n", $s);

	$out = array();

	foreach ($list as $k) {

		$k = trim($k);

		if (substr($k, 0, 1) == "`") {

			$fname = ltrim($k, "`");

			list($sa, $sb) = explode(" ", $fname, 2);

			$sa = rtrim($sa, "`");

			$out[$sa] = rtrim(trim($k), ',');

		}

	}



	return $out;

}



?>

<html>

<head>

<title>���±�ṹ</title>

<meta charset="gbk" />

<link href="/res/base.css" rel="stylesheet" type="text/css">

</head>



<body style="padding:30px 50px;">



<?php

if ($_GET["op"] == "update_table") {

	include_once ROOT."core/function.create_table.php";



	// ��ȡ����µ�ÿ��ҽԺ:

	$hids = $db->query("select id from hospital", "", "id");



	// Ҫ����ı�:

	$table_names = array();

	$table_names[] = "patient";

	foreach ($hids as $hid) {

		$table_names[] = "patient_".$hid;

	}



	// ����:

	foreach ($table_names as $table_name) {

		$cs = $db_tables["patient"];



		if (!table_exists($table_name, $db->dblink)) {

			//$db->query(str_replace("{hid}", $hid, $cs));

			//echo "������ ".$table_name." <br>";

		} else {

			$fields = parse_fields($cs);

			foreach ($fields as $f => $fs) {

				if (!field_exists($f, $table_name, $db->dblink)) {

					$tm = array_keys($fields);

					$tm2 = array_search($f, $tm);

					if ($tm2 == 0) {

						$pos = " first";

					} else {

						$pos = " after `".$tm[$tm2-1]."`";

					}

					$sql = "alter table `".$table_name."` add ".$fs.$pos;

					$db->query($sql);

					echo "�� ".$table_name." ����ֶ� ".$f."<br>";

				}

			}

		}

	}



	echo "<br>ȫ�����";

	exit;

}

?>

<form method="GET">
	<input type="submit" class="buttonb" value="�������">
	<input type="hidden" name="op" value="update_table">
</form>

</body>
</html>