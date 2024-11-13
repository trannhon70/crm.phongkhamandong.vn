<?php

/*

// - ����˵�� : ͼ������

// - ע������ : �ÿ��еĺ�����Ҫ����ģ��gd2.dll����ʹ��

// - �������� : ��ҽս�� 

// - ����ʱ�� : 2006-01-15 21:50

*/

function GenThumb($InFile, $ToDir, $nWidth=128, $nHeight=128)

{

	$Ext = strrchr($InFile, ".");

	$OnlyName = basename(substr($InFile, 0, strlen($InFile) - strlen($Ext)));

	$NewName = $ToDir . $OnlyName . "s.jpg";

	$Count = 0;

	while (file_exists($NewName))

	{

		$NewName = $ToDir . $OnlyName . "s" . (++$Count) . ".jpg";

	}

	if (! image_resize($InFile, $NewName, $nWidth, $nHeight))

	{

		$NewName = "";

	}



	return $NewName;

}



function ResizeTo($cImageFile, $nNewWidth, $nNewHeight)

{

	$cOutFile = md5(time()*rand()) . "jpg";

	if (image_resize($cImageFile, $cOutFile, $nNewWidth, $nNewHeight))

	{

		unlink($cImageFile);

		rename($cOutFile, $cImageFile);

	}



	return $cImageFile;

}



function image_resize($cImageFile, $cOutFile, $nNewWidth, $nNewHeight)

{

	if (! file_exists($cImageFile))

	{

		return false;

	}



	if (! function_exists("imagejpeg"))

	{

		//copy($cImageFile, $cOutFile);

		//return true;

		return false;

	}



	list($nW, $nH) = getimagesize($cImageFile);



	// ����ͼ���ʽ����һ��ͼƬ:

	switch (strtolower(substr($cImageFile, -4, 4)))

	{

		case ".jpg":

			$im = imagecreatefromjpeg($cImageFile); break;

		case ".gif":

			$im = imagecreatefromgif($cImageFile); break;

		case ".png":

			$im = imagecreatefrompng($cImageFile); break;

		case ".bmp":

			$im = imagecreatefromwbmp($cImageFile); break;

		default:

			// ָ�����ļ����ܲ���ͼ���ļ���

			return false;

	}



	// �����ȱ���������ԭͼ�������¼�����ͼƬ�߳�:

	if ($nW / $nH != $nNewWidth / $nNewHeight)

	{

		$nRate = min($nNewWidth / $nW, $nNewHeight / $nH);

		$nNewW = $nW * $nRate;

		$nNewH = $nH * $nRate;

	} else {

		$nNewW = $nNewWidth;

		$nNewH = $nNewHeight;

	}

	$im1 = imagecreatetruecolor($nNewW, $nNewH);

	imagecopyresampled($im1, $im, 0, 0, 0, 0, $nNewW, $nNewH, $nW, $nH);

	$nW = $nNewW;

	$nH = $nNewH;

	imagedestroy($im);

	$im = $im1;



	// ��ͼ��������ļ����ͷ��ڴ�:

	if (file_exists($cOutFile))

	{

		unlink($cOutFile);

	}

	imagejpeg($im, $cOutFile, 85);

	imagedestroy($im);



	return true;

}



// ~~~~~~~~~~ ���еķ����任ͼƬ��С

function image_resize_cut($cImageFile, $cOutFile, $nNewW, $nNewH)

{

	if (!file_exists($cImageFile) || !function_exists("imagejpeg"))

	{

		return false;

	}



	list($nW, $nH) = getimagesize($cImageFile);



	// ����ͼ���ʽ����һ��ͼƬ:

	switch (strtolower(substr($cImageFile, -4, 4)))

	{

		case ".jpg":

			$im = imagecreatefromjpeg($cImageFile); break;

		case ".gif":

			$im = imagecreatefromgif($cImageFile); break;

		case ".png":

			$im = imagecreatefrompng($cImageFile); break;

		case ".bmp":

			$im = imagecreatefromwbmp($cImageFile); break;

		default:

			return false;

	}



	$im1 = imagecreatetruecolor($nNewW, $nNewH);



	// �����ȡ��С�ͽ�ȡλ������:

	$nRate1 = $nW / $nH;

	$nRate2 = $nNewW / $nNewH;

	if ($nRate1 > $nRate2)

	{

		$w1 = $nNewW * ($nH / $nNewH);

		$h1 = $nH;

		$x1 = ($nW - $w1) / 2;

		$y1 = 0;

	} elseif ($nRate1 < $nRate2) {

		$w1 = $nW;

		$h1 = $nNewH * ($nW / $nNewW);

		$x1 = 0;

		$y1 = ($nH - $h1) / 2;

	} else {

		$w1 = $nW;

		$h1 = $nH;

		$x1 = $y1 = 0;

	}



	imagecopyresampled($im1, $im, 0, 0, $x1, $y1, $nNewW, $nNewH, $w1, $h1);

	imagedestroy($im);



	// ��ͼ��������ļ����ͷ��ڴ�:

	if (file_exists($cOutFile))

	{

		unlink($cOutFile);

	}

	imagejpeg($im1, $cOutFile, 85);

	imagedestroy($im1);



	return true;

}



function image_resize_back($cImageFile, $cOutFile, $nNewW, $nNewH, $sBackColor="255,255,255", $sBorder="")

{

	if (!file_exists($cImageFile) || !function_exists("imagejpeg"))

	{

		return false;

	}



	list($cr, $cg, $cb) = explode(",", $sBackColor);

	list($nW, $nH) = getimagesize($cImageFile);



	// ���㱳�������Ĵ�С:

	if ($nW/$nH > $nNewW/$nNewH)

	{

		$nBackW = $nW > $nNewW ? $nNewW : $nW;

		$nBackH = $nNewH * $nBackW / $nNewW;

	} else {

		$nBackH = $nH > $nNewH ? $nNewH : $nH;

		$nBackW = $nNewW * $nBackH / $nNewH;

	}



	// ����Դͼ����������Լ����ű���:

	if ($nW > $nBackW || $nH > $nBackH)

	{

		$nRate = max($nW / $nBackW, $nH / $nBackH);

		$nImgW = $nW / $nRate;

		$nImgH = $nH / $nRate;

	} else {

		$nImgW = $nW;

		$nImgH = $nH;

	}



	// ���㸴��ͼ��ʱ x,y �����λ��:

	$nx = ($nBackW - $nImgW) / 2;

	$ny = ($nBackH - $nImgH) / 2;



	$bk = imagecreatetruecolor($nBackW, $nBackH);

	$back_color = imagecolorallocate($bk, $cr, $cg, $cb);

	imagefilledpolygon($bk, array(0,0, 0,$nBackH-1, $nBackW-1,$nBackH-1, $nBackW-1, 0), 4, $back_color);



	// ����ͼ���ʽ����һ��ͼƬ:

	switch (strtolower(substr($cImageFile, -4, 4)))

	{

		case ".jpg":

			$im = imagecreatefromjpeg($cImageFile); break;

		case ".gif":

			$im = imagecreatefromgif($cImageFile); break;

		case ".png":

			$im = imagecreatefrompng($cImageFile); break;

		case ".bmp":

			$im = imagecreatefromwbmp($cImageFile); break;

		default:

			return false;

	}



	// �����ȡ��С�ͽ�ȡλ������:

	imagecopyresampled($bk, $im, $nx, $ny, 0, 0, $nImgW, $nImgH, $nW, $nH);

	imagedestroy($im);



	if ($sBorder)

	{

		list($br, $bg, $bb) = explode(",", $sBorder);

		$border_color = imagecolorallocate($bk, $br, $bg, $bb);

		imagepolygon($bk, array(0,0, 0,$nBackH-1, $nBackW-1,$nBackH-1, $nBackW-1, 0), 4, $border_color);

	}



	// ��ͼ��������ļ����ͷ��ڴ�:

	if (file_exists($cOutFile))

	{

		unlink($cOutFile);

	}

	imagejpeg($bk, $cOutFile, 85);

	imagedestroy($bk);



	return true;

}



//image_resize_back("1.jpg", "2.jpg", "400", "400", "255,255,255", "");

//echo "<img src='2.jpg'><br><br>";

//echo "<img src='1.jpg'><br>";

//echo "���!";



/*

if ($handle = opendir('upload/'))

{

	while (false !== ($file = readdir($handle)))

	{

		if (in_array(strtolower(strrchr($file, '.')), array('.gif', '.jpg', '.bmp', '.png')) && substr($file, 0, 2) != "s_" && filesize("upload/".$file) > 20*1024)

		{

			$aFiles[] = $file;

		}

	}

	closedir($handle);

}

*/



/*

set_time_limit(600);

require "manage/lib/config_mysql.php";

require "manage/lib/function_mysql.php";



$db = new mysql();

$link = $db->query("select pic from gbo_product where pic!=''");

while ($line = $db->fetch_array($link))

{

	$aFiles[] = $line[0];

}



foreach ($aFiles as $filename)

{

	image_resize_back("upload/".$filename, "upload2/".$filename, "400", "400", "255,255,255", "");

	echo "ͼ�� $filename ���ɳɹ���<br>";

}

*/



?>