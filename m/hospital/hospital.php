<?php

/*

// - ����˵�� : ҽԺ�б�

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-05-01 00:36

*/

require "../../core/core.php";

$mod = $table = "hospital";



// �����Ĵ���:

if ($op = $_GET["op"]) {

	include $mod.".op.php";

}



include $mod.".list.php";



?>