<?php

/*

// ˵��:

// ����: ��ҽս�� 

// ʱ��:

*/



$tmp = "<table width='80%' align='center'><tr><td><h1>�����ύ����ʧ�ܣ���</h1><br>&nbsp;&nbsp;&nbsp;&nbsp;���Ѿ����û�кͷ�����ͨ���ˣ���ˣ���������Ϊ���Ѿ��뿪�ˣ������Զ�ע�������ĵ�¼״̬�����������ύ�����ϣ������һ����������ڣ����µ�¼��Ȼ�����ϸ��ƹ�ȥ�����ύ���������ô�������ύ����Щ���Ͻ��ᶪʧ��</td></tr></table>";

$tmp .= "<table width='80%' align='center' border='1' bordercolor='#C0C0C0' cellpadding='3' style='border-collapse:collapse'>";

foreach ($_POST as $key => $value) {

	$tmp .= "<tr><td width='100' align='right' bgcolor='#F0F0F0'>$key:</td><td>$value</td></tr>";

}

$tmp .= "</table>";

echo $tmp;



?>