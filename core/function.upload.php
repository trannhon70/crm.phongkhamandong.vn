<?php

/*

// - ����˵�� : �ļ��ϴ�����(���ļ�upfiles,�����ļ�upfile)

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2013-01-22 16:16

*/



/*

	�ϴ������ļ��Ĵ���

	����:

		$file_id_name �����ļ�������� <input type='file' name='pic'> �е�"pic"

		$to_dir Ҫ���ļ����浽���ļ���λ��

		$file_max_size ��ѡ���� �ֽ�Ϊ��λ���޶��ļ��Ĵ�С

		$allow_file_type ��ѡ���� �޶��ļ������� array('mp3', 'wma')

	����ֵ:

		����ϴ��ɹ����򷵻ز�����·�������ļ��������򷵻�false

	�޸���ʷ:

		2013-01-22 17:25

			������ԭ�汾�� $sql_read_old_file ����,���Ǻ����ڲ���һ���ԺͰ�ȫ��,ȡ���˹���,���ڳ��������ɿ���

			������� $allow_file_type

*/

function upfile($file_id_name, $to_dir='', $file_max_size=0, $allow_file_type=array()) {

	$count = 0; //�����ۼӼ���



	// �ж�Ŀ¼�Ƿ����,�����ڵĻ������Դ���֮,ʧ���򷵻�false:

	$to_dir = $to_dir ? (substr($to_dir, -1, 1) != "/" ? $to_dir."/" : $to_dir) : "./";

	if ($to_dir && !file_exists($to_dir) && !@mkdir($to_dir, 0777)) {

		return false;

	}



	if ($oriname = $_FILES[$file_id_name]['name']) {

		// ��ʱ�ļ�������:

		$tmpname = $_FILES[$file_id_name]['tmp_name'];



		// �ļ���С���:

		if ($file_max_size > 0 && filesize($tmpname) > $file_max_size) {

			echo "�ļ�̫�󣬳�������(������� ".round($file_max_size/1024)."KB)";

			return false;

		}



		// �ļ����ͼ��:

		$ext = strtolower(strrchr($oriname,'.'));

		$just_ext = substr($ext, 1); // ��������ŵ�ext

		if ($allow_file_type && !in_array($just_ext, $allow_file_type)) {

			echo "�ļ�������(".implode("��", $allow_file_type).")��ʽ��";

			return false;

		}



		// ����һ�����ظ����ļ���:

		$onlyname = date("Ymd_His_").rand(100, 999);

		$newname = $onlyname.$ext; // ����:20130122_122340_432.jpg

		while (file_exists($to_dir.$newname)) {

			$newname = $onlyname."_".(++$count).$ext; // ����:20130122_122340_432_1.jpg

		}



		// �ƶ���ʱ�ļ�:

		if (move_uploaded_file($tmpname, $to_dir.$newname)) {

			return $newname;

		} else {

			echo $newname;

			echo "�ļ��ƶ�ʧ�ܣ�����Ŀ¼д��Ȩ�ޣ�";

			return false;

		}

	}



	return false;

}





/*

	ͬʱ�ϴ�����ļ�

	����:

		$file_id_name: ���ָ�ʽ�����: <input type='file' name='pic[]'> "pic"���������file_id_name

		$to_dir: �ļ�����·��;

		$file_max_size: �����ļ������ֵ,���Բ�ָ��(������)

		$allow_file_type: ��ʽ array('jpg', 'gif') �� array('mp3', 'wma', 'wav', 'rm')

	����ֵ:

		�ϴ��ɹ�����ļ�������,����·��,���ʧ�ܷ��ؿ�����

	����޸�:

		2013-01-22 16:37 by ��ҽս��

*/

function upfiles($file_id_name, $to_dir='', $file_max_size=0, $allow_file_type=array()) {



	// to_dir �Ĵ��������ڽ����Դ������������ʧ�ܣ��������ϴ�ʧ��:

	$to_dir = $to_dir ? (substr($to_dir, -1, 1) != "/" ? $to_dir."/" : $to_dir) : "./";

	if ($to_dir && !file_exists($to_dir) && !@mkdir($to_dir, 0777)) {

		return false;

	}



	// ����ֵ�����ʼ��:

	$result = array();



	// ������ϴ��ļ�:

	if ($file_count = count($_FILES[$file_id_name]["name"])) {

		$count = 0;

		for ($ni=0; $ni<$file_count; $ni++) {

			if ($upname = $_FILES[$file_id_name]["name"][$ni]) {

				// �ϴ���Ϣ:

				$uptmpname = $_FILES[$file_id_name]["tmp_name"][$ni];

				$upsize = $_FILES[$file_id_name]["size"][$ni];



				// ��չ��:

				$ext = strtolower(strrchr($upname, '.'));

				$just_ext = substr($ext, 1); // ��������ŵ�ext



				// �ļ���С���Ƽ��:

				if ($file_max_size > 0 && $upsize > $file_max_size) {

					continue;

				}



				// �ļ��������Ƽ��:

				if (is_array($allow_file_type) && count($allow_file_type) && !in_array($just_ext, $allow_file_type)) {

					continue;

				}



				// �����µ��ļ���:

				$onlyname = date("Ymd_His_").rand(100, 999)."_".$ni; // ����:20070925_092835_345_1.jpg

				$newname = $onlyname.$ext;



				// �ж�������û���ظ�������ظ��Ļ����ݼ�count,for ѭ����count������

				while (file_exists($to_dir.$newname)) {

					$newname = $onlyname.(++$count).$ext; //����:20070925_092835_345_12.jpg

				}



				// �ƶ�����ļ�:

				if (move_uploaded_file($uptmpname, $to_dir.$newname)) {

					$result[] = $newname;

				}

			}

		} //--> end for

	}



	return $result;

}



?>