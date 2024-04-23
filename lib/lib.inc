<?php
// 에러 출력
function error($msg, $go_url="")
{

	if($go_url == "")
		echo "<script>alert(\"$msg\");history.go(-1);</script>";
	else
		echo "<script>alert(\"$msg\");document.location=\"$go_url\";</script>";

	exit;

}

//OTP Code 발생 함수

function generateOtpCode() {
		//$randomNum = mt_rand(1, 999999);
		$randomNum =sprintf('%06d',mt_rand(1, 999999)); 
		return $randomNum;

}

function new_get_file_contents($url) {
	
	$__PHPVERSION = phpversion();

	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	); 
	
	if ($__PHPVERSION >= "5.6") { 
		$result =  @file_get_contents($url, false, stream_context_create($arrContextOptions));
	} else {
		$result =  @file_get_contents($url);
	}
	
	echo $result;

}

function new_get_file_contents_return($url) {
	
	$__PHPVERSION = phpversion();

	$arrContextOptions=array(
		"ssl"=>array(
			"verify_peer"=>false,
			"verify_peer_name"=>false,
		),
	); 
	
	if ($__PHPVERSION >= "5.6") { 
		$result =  @file_get_contents($url, false, stream_context_create($arrContextOptions));
	} else {
		$result =  @file_get_contents($url);
	}
	
	return $result;

}

function getSslPage($url,  $http_method, $post_data_array) {
			
			$ch = curl_init();
			
			if($http_method == "GET") {
				
				//$url = $url . "?email=" . $email;

				//echo "url = " . $url . "<br>";

				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
				
			} else { //POST 일경우
				//echo "email = " . $email . "<br>";
				$data = array('email' => $email);
					
		/*
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_HEADER, FALSE);
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_REFERER, $url);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS,  http_build_query($data));       //POST data
				curl_setopt($ch, CURLOPT_POST, TRUE);              //true시 post 전송 

				curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8'));
		*/

				curl_setopt($ch, CURLOPT_URL, $url); 
				curl_setopt($ch, CURLOPT_POST, TRUE); 
				curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data_array); 
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); 
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 

			}

			$result = curl_exec($ch);

			//var_dump($result);

			
			curl_close($ch);
			return $result;
}

function new_get_file_contents_curl($url) {
	
	$ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec($ch);
    curl_close($ch);

	//echo "result = " . $result;
    return $result;
	/*
	$ch = curl_init();
	$timeout = 0; // set to zero for no timeout
	curl_setopt ($ch, CURLOPT_URL, $url);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$file_contents = curl_exec($ch); // take out the spaces of curl statement!!
	curl_close($ch);
	
	return $file_contents;
	*/
}

function downStrPathCut($sPath, $str, $divStr) {
	$totLen = strLen($str);
	$findLen = strLen($divStr);
	
	$findpos = stripos($str,  "+");
	
	$sStr = substr($str, 0, $findpos );

	$eStr = substr($str, $findpos + $findLen , 5 );

	$sPath = $sStr . "+" .  $eStr;
	
}


function getDefineDateFormat($ymdhis) {
	if($ymdhis != "") {
		$yyyy =		substr($ymdhis, 0,4) ;
		$mm =		substr($ymdhis, 4,2) ;
		$dd =		substr($ymdhis, 6,2) ;
		$hh =		substr($ymdhis, 8,2) ;
		$min =		substr($ymdhis, 10,2) ;
		$sec =		substr($ymdhis, 12,2) ;
		if($hh != "") {
			$yymmddhis = $yyyy . "/" . $mm . "/" . $dd . " " . $hh . ":" . $min . ":" . $sec . "'"; 
		} else {
			$yymmddhis = $yyyy . "/" . $mm . "/" . $dd; 
		}
		//$yymmddhis = $yyyy . "/" . $mm . "/" . $dd . " " . $hh . ":" . $min . ":" . $sec . "'"; 
	} else {
		$yymmddhis = "";
	}
	return $yymmddhis;
}

function getDefineDateFormatShort($ymdhis) {
	if($ymdhis != "") {
		$yyyy =		substr($ymdhis, 0,4) ;
		$mm =		substr($ymdhis, 4,2) ;
		$dd =		substr($ymdhis, 6,2) ;
		$hh =		substr($ymdhis, 8,2) ;
		$min =		substr($ymdhis, 10,2) ;
		$sec =		substr($ymdhis, 12,2) ;
		if($hh != "") {
			$yymmddhis = $yyyy . "/" . $mm . "/" . $dd . " " . $hh . ":" . $min . "'"; 
		} else {
			$yymmddhis = $yyyy . "/" . $mm . "/" . $dd; 
		}
		
	} else {
		$yymmddhis = "";	
	}
	return $yymmddhis;
}

function getDefineDateFormatShortYear($ymdhis) {
	if($ymdhis != "") {
		$yyyy =		substr($ymdhis, 2,2) ;
		$mm =		substr($ymdhis, 4,2) ;
		$dd =		substr($ymdhis, 6,2) ;
		$hh =		substr($ymdhis, 8,2) ;
		$min =		substr($ymdhis, 10,2) ;
		$sec =		substr($ymdhis, 12,2) ;
		if($hh != "") {
			$yymmddhis = $yyyy . "/" . $mm . "/" . $dd . " " . $hh . ":" . $min . "'"; 
		} else {
			$yymmddhis = $yyyy . "/" . $mm . "/" . $dd; 
		}
		
	} else {
		$yymmddhis = "";	
	}
	return $yymmddhis;
}

function getDefineDateFormatShortYearMD($ymd) {
	if($ymd != "") {
		$yyyy =		substr($ymd, 2,2) ;
		$mm =		substr($ymd, 4,2) ;
		$dd =		substr($ymd, 6,2) ;
		$ymd = $yyyy . "-" . $mm . "-" . $dd ; 
	} else {
		$ymd = "";	
	}
	return $ymd;
}


function getDefineDateFormatShortYearMDB($ymd) {
	if($ymd != "") {
		$yyyy =		substr($ymd, 0,4) ;
		$mm =		substr($ymd, 4,2) ;
		$dd =		substr($ymd, 6,2) ;
		$ymd = $yyyy . "-" . $mm . "-" . $dd ; 
	} else {
		$ymd = "";	
	}
	return $ymd;
}

function getDefineDateFormatShortYearMDH($ymd) {
	if($ymd != "") {
		$yyyy =		substr($ymd, 2,2) ;
		$mm =		substr($ymd, 4,2) ;
		$dd =		substr($ymd, 6,2) ;
		$hh =		substr($ymd, 8,2) ;
		$ymd = $yyyy . "/" . $mm . "/" . $dd  . ":" . $hh ; 
	} else {
		$ymd = "";	
	}
	return $ymd;
}


function getDefineDateFormatDot($ymdhis) {
	$yyyy =		substr($ymdhis, 0,4) ;
	$mm =		substr($ymdhis, 4,2) ;
	$dd =		substr($ymdhis, 6,2) ;
	$hh =		substr($ymdhis, 8,2) ;
	$min =		substr($ymdhis, 10,2) ;
	$sec =		substr($ymdhis, 12,2) ;
	$yymmddhis = $yyyy . "-" . $mm . "-" . $dd . " " . $hh . ":" . $min . ":" . $sec; 
	return $yymmddhis;
}

function getDefineDateFormatDotShort($ymdhis) {
	$yyyy =		substr($ymdhis, 0,4) ;
	$mm =		substr($ymdhis, 4,2) ;
	$dd =		substr($ymdhis, 6,2) ;
	$hh =		substr($ymdhis, 8,2) ;
	$min =		substr($ymdhis, 10,2) ;
	$sec =		substr($ymdhis, 12,2) ;
	$yymmddhis = $yyyy . "-" . $mm . "-" . $dd . " " . $hh . ":" . $min; 
	return $yymmddhis;
}

//byte 단위로 넘어 오기 때문에 이를 변형해서 리턴해 준다.
function getSizeCheck($val) {

	return formatBytes($val);
	
	/* 변환값이 맞지 않음...
	if($val < 999) {
		if($val > 0) {
			$rtn_val = number_format($val, 0);
		} else {
			$rtn_val = $val;
		}
		if($rtn_val == "") {
			$rtn_val = 0;
		} else {
			$rtn_val = $rtn_val . " Byte";
		}
		//$rtn_val = $val . "Byte";
	} else if($val > 999 && $val < 1000000) {
		$rtn_val = number_format(round($val / 1024),2);
		$rtn_val = (int)$rtn_val;
		$rtn_val = $rtn_val . " KB";
	} else if($val > 999999 && $val < 1000000000) {
		$rtn_val = number_format(round($val / (1024*1024)),2);
		$rtn_val = (int)$rtn_val;
		$rtn_val = $rtn_val . " MB";
	} else if($val > 999999999) {
		$rtn_val = number_format(round($val / (1024*1024*1024)),2);
		$rtn_val = (int)$rtn_val;
		$rtn_val = $rtn_val . " GB";	
	} else if($val > 999999999999) {
		$rtn_val = number_format(round($val / (1024*1024*1024*1024)),2);
		$rtn_val = (int)$rtn_val;
		$rtn_val = $rtn_val . " TB";	
	}
	//$rtn_val = $val . " ..... " . $rtn_val;
	return $rtn_val;
	*/
}

function formatBytes($size, $precision = 2)
{
	$base = log($size, 1024);
	$suffixes = array('Byte', 'KB', 'MB', 'GB', 'TB');   
	
	if($size==0){
		
		return "0 Byte";

	}else{

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
}

// 경고창 출력
function alert($msg, $go_url="")
{

	if($go_url == "")
		echo "<script>alert(\"$msg\");history.go(-1);</script>";
	else
		echo "<script>alert(\"$msg\");document.location=\"$go_url\";</script>";

}

// 완료 메세지 출력
function complete($com_msg, $go_url="")
{

	if($go_url == "")
		echo "<script>window.setTimeout(\"history.go(-1)\",600);</script>";
	else
	echo "<script>window.setTimeout(\"document.location='$go_url';\",600);</script>";

	echo "<body><table width=100% height=100%><tr><td align=center><font size=2>$com_msg</font></td></tr></table></body>";

}



// 문자열 끊기 (이상의 길이일때는 ... 로 표시)
function cut_str($msg, $cut_size)
{

	if($cut_size<=0) return $msg;
	if(ereg("\[re\]",$msg)) $cut_size=$cut_size+4;
	for($i=0;$i<$cut_size;$i++) if(ord($msg[$i])>127) $han++; else $eng++;
	$cut_size=$cut_size+(int)$han*0.6;
	$point=1;
	for ($i=0;$i<strlen($msg);$i++) {
		if ($point>$cut_size) return $pointtmp."...";
		if (ord($msg[$i])<=127) {
			$pointtmp.= $msg[$i];
			if ($point%$cut_size==0) return $pointtmp."...";
		} else {
			if ($point%$cut_size==0) return $pointtmp."...";
			$pointtmp.=$msg[$i].$msg[++$i];
			$point++;
		}
		$point++;
	}

	return $pointtmp;

}

// 문자열 자르기(한글깨짐방지)
function trim_str($str, $length) {
	if (!$str) return '';
	if(preg_match("/\[re\]/",$str)) $length=$length+4;
	$tmp=mb_substr($str, 0, $length, 'utf-8');
	return strlen($str) == strlen($tmp) ? $str : $tmp."...";
}



// 문자열의 마지막 문자를 * 로 처리해서 반환
function set_passwd($str)
{
   $re_str = "";
   $strlen = strlen($str) - 2;
   $re_str = substr($str,0,2);
   for($ii=0;$ii<$strlen;$ii++){
      $re_str .= "*";
   }

   return $re_str;

}




// 비방글, 욕설체크
function check_abuse($str)
{


	global $bbs_info;
	global $poll_info;
	if(!empty($bbs_info)) {
		$abuse_list = explode(",",$bbs_info[abtxt]);
		for($ii=0; $ii < count($abuse_list); $ii++){
			$abuse_list[$ii] = trim($abuse_list[$ii]);
			if(!empty($abuse_list[$ii])){
				if( strpos($str, $abuse_list[$ii]) !== false){
					error("'$abuse_list[$ii]' 단어는 사용하실 수 없습니다.");
				}
			}
		}
	}

	if(!empty($poll_info)) {
		$abuse_list = explode(",",$poll_info[abtxt]);
		for($ii=0; $ii < count($abuse_list); $ii++){
			$abuse_list[$ii] = trim($abuse_list[$ii]);
			if(!empty($abuse_list[$ii])){
				if( strpos($str, $abuse_list[$ii]) !== false){
					error("'$abuse_list[$ii]' 단어는 사용하실 수 없습니다.");
				}
			}
		}
	}

}

function thumbnail($source_file, $save_file, $fix_width, $fix_height, $log="")
{
  
  
  $_FILE_INFO = @getimagesize($source_file);
  
  if(!$_FILE_INFO) { return 0; }

  # 소스 이미지의 비율
 
  
  switch($_FILE_INFO[mime])
  {
    case "image/jpeg" : 
    	$sc_img = imagecreatefromjpeg($source_file); 
    	
    	break;
    case "image/gif"  : 
    	$sc_img = imagecreatefromgif($source_file);  
		//echo "gif <br/>";
    	break;
    case "image/png"  : 
    	$sc_img = imagecreatefrompng($source_file);  
    	break;
  }
	
  
  $rs_img = @imagecreatetruecolor($fix_width, $fix_height);
  //bcbec0
  $bgc = imagecolorallocate ($rs_img, 188, 189, 190);
  $tc = imagecolorallocate ($rs_img, 0, 0, 0);
  imagefilledrectangle ($rs_img, 0, 0, $fix_width, $fix_height, $bgc);
 
  //imagecopyresampled($rs_img, $sc_img, 0, 0, 0, 0, $_FIX_SIZE[0], $_FIX_SIZE[1], $_FILE_INFO[0], $_FILE_INFO[1]);
  imagecopyresampled($rs_img, $sc_img, 0, 0, 0, 0, $fix_width, $fix_height, $_FILE_INFO[0], $_FILE_INFO[1]);
  
  switch($_FILE_INFO[mime])
  {
    case "image/jpeg" : @imagejpeg($rs_img, $save_file, 100); break;
    case "image/gif"  : @imagegif($rs_img, $save_file, 100);  break;
    case "image/png"  : @imagepng($rs_img, $save_file, 100);  break;
  }
  @imagedestroy($sc_img);
}

// 이미지 리사이즈
function img_resize($srcimg, $dstimg, $imgpath, $rewidth, $reheight, $mode="")
{
	$src_info = getimagesize($imgpath."/".$srcimg);

	if(!strcmp($src_info['channels'], "4")) {
		//echo "<script>alert('현재 업로드하신 이미지는 CMYK 형식입니다. \\n\\n웹상에서 보이지 않을 수 있습니다.');</script>";
	}

	if($rewidth < $src_info[0] || $reheight < $src_info[1] ){

		if(!strcmp($mode, "width")) {

			$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);

		} else {

			if(($src_info[0]-$rewidth) > ($src_info[1]-$reheight)){
				$reheight = round(($src_info[1]*$rewidth)/$src_info[0]);
			}else{
				$rewidth = round(($src_info[0]*$reheight)/$src_info[1]);
			}

		}

	}else{
		copy($imgpath."/".$srcimg,$imgpath."/".$dstimg);
		return;
	}

	if(function_exists(imageCreatetrueColor)) {

		$dst = @imageCreatetrueColor($rewidth,$reheight);

		if($src_info[2] == 1){

			$src = @ImageCreateFromGIF($imgpath."/".$srcimg);
			@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
			@Imagejpeg($dst,$imgpath."/".$dstimg,100);

		}else if($src_info[2] == 2){

			$src = @ImageCreateFromJPEG($imgpath."/".$srcimg);
			@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
			@Imagejpeg($dst,$imgpath."/".$dstimg,100);

		}else if($src_info[2] == 3){

			$src = @ImageCreateFromPNG($imgpath."/".$srcimg);
			@imagecopyResampled($dst, $src,0,0,0,0,$rewidth,$reheight,ImageSX($src),ImageSY($src));
			@Imagepng($dst,$imgpath."/".$dstimg,100);

		}else{

			copy($imgpath."/".$srcimg,$imgpath."/".$dstimg);

		}

		@imageDestroy($src);
		@imageDestroy($dst);

	} else {

			link($imgpath."/".$srcimg,$imgpath."/".$dstimg);

	}

}

// 파일이 이미지인지
function img_type($srcimg)
{
	if(is_file($srcimg)){

		$image_info = getimagesize($srcimg);
		switch ($image_info['mime']) {
			case 'image/gif': return true; break;
			case 'image/jpeg': return true; break;
			case 'image/png': return true; break;
			case 'image/bmp': return true; break;
			default : return false; break;
		}
	}else{
		return false;
	}

}


function imageResize($way, $save=null, $max_new_width, $max_new_height ){

		$imgInfo = getimagesize($way);

		switch($imgInfo[2])
		{
			case(1):
			$main = imagecreatefromgif($way);
			break;

			case(2):
			$main = imagecreatefromjpeg($way);
			break;

			case(3):
			$main = imagecreatefrompng($way);
			break;

			default:
			//trigger_error('NEPODPOROVANY FORMAT OBRAZKU', E_USER_WARNING);
			//exit();
			break;
		}

		list($width, $height) = $imgInfo;

		/*resizing image*/
		$originalWidth = $width;
		$originalHeight = $height;
		$ratio = $width / $height;
		$height = $max_new_height;
		$width = $height * $ratio;

		if($width > 150)
		{
			$ratio = $height / $width;
			$width = $max_new_width;
			$height = $width * $ratio;
		}

		$newImg = imagecreatetruecolor($width, $height);

		/*saved alpha in image (in bachkgound image)*/
		if(($imgInfo[2] == 3))
		{
			imagealphablending($newImg, false);
			imagesavealpha($newImg, true);
		}
		elseif(($imgInfo[2] == 1))
		{
			/*find color, that is reserved for alpha. set this color as transparent for truecolor background ($newImg), and fill this image with transparent color.*/

			$transparent = imageColorTransparent($main);

			if($transparent != -1)
			{
				$transparent_color = imageColorsForIndex($main, $transparent);

				$transparent_new = imageColorAllocate($newImg, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
				$transparent_new_index = imageColorTransparent($newImg, $transparent_new);
				imageFill($newImg, 0, 0, $transparent_new_index); /*<– don't forget*/
			}

			imageCopyResized($newImg, $main, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight); /*<– if you merge images with function imageCopyResampled(), It will not work*/
		}

		if($imgInfo[2] != 1)
		imagecopyresampled($newImg, $main, 0, 0, 0, 0, $width, $height, $originalWidth, $originalHeight);

		/*output*/
		if(empty($save))
		header('Content-type: ' . $imgInfo['mime']);
		switch($imgInfo[2])
		{
			case(1): imagegif($newImg, $save); break;
			case(2): imagejpeg($newImg, $save, 100); break;
			case(3): imagepng($newImg, $save, 9); break;

			default:
			trigger_error('Resizovani obraku se posralo, proc, to fakt nevim', E_USER_WARNING);
			break;
		}

		imagedestroy($main);
		imagedestroy($newImg);

		return $save;
}

// 페이지 리스트 출력
function print_pagelistNew3($page, $list_amount, $page_count, $param, $page_type = "", $total = 0)
{

   global $code, $catcode, $orderby, $skin_dir, $ptype, $_img_server, $_COOKIE,  $_LANG_TEXT;

	//echo "  $page, $list_amount, $page_count, $param";
	//exit;

	$numbertext = $_LANG_TEXT['numbertext'][$_COOKIE['dpt_lang']];

   if($skin_dir == "") $skin_dir = ".";
   if($param != "") $param = "&".$param;

	if(($page%$list_amount) == 0) $tmp = $page-1;
	else $tmp = $page;
	
	$spage = floor($tmp/$list_amount)*$list_amount+1;
	if($spage <= 1) $ppage = 1;
	else $ppage = $spage - $list_amount;

	$epage = $spage+$list_amount-1;
	if($epage >= $page_count){
		$epage = $page_count;
		$npage = $page_count;
	}else{
		$npage = $epage + 1;
	}

	if(!empty($page_type)) {
		$page_name = strtolower($page_type)."page";
	} else {
		$page_name = "page";
	}


	echo '		<div id="paging2"><ul>
			';

	if($epage > 0) {
		
		/* get parameter 로 데이터 전송
		echo "<li><a href='#' onClick=\"location.href='$PHP_SELF?$page_name=1$param';\"><img src='".$_img_server."/b_prev2.jpg'></a></li>";
		echo "<li class='prev'><a href='#' onClick=\"location.href='$PHP_SELF?$page_name=$ppage$param';\"><img src='".$_img_server."/b_prev1.jpg'></a></li>";
	   
	   for($spage; $spage <= $epage; $spage++){
	      if($page == $spage) {
				
				echo "<li class='num active'><a href='#'><b>$spage</b></a></li> ";
				if($spage != $epage) echo " ";
	      } else {
	      	if($spage == $epage) {
	      		echo "<li class='num'><a href='#' onClick=\"location.href='$PHP_SELF?$page_name=$spage$param';\"> $spage </a></li> ";
	      	} else {
	      		echo "<li class='num'><a href='#' onClick=\"location.href='$PHP_SELF?$page_name=$spage$param';\"> $spage </a></li> ";
	      	}
	      }
	   }

	   echo "          <li class='next'><a href='#' onClick=\"location.href='$PHP_SELF?$page_name=$npage$param';\"><img src='" . $_img_server . "/b_next1.jpg' /></a></li>";
	   echo "          <li><a href='#' onClick=\"location.href='$PHP_SELF?$page_name=$page_count$param';\"><img src='" . $_img_server . "/b_next2.jpg'  /></a></li>";
	   echo "         </ul></div> ";
	*/


	echo "<li><a href='javascript:void(0)' onClick=\"sendPostForm('$PHP_SELF?$page_name=1$param')\"><img src='".$_img_server."/b_prev2.jpg'></a></li>";
			echo "<li class='prev'><a href='#' onClick=\"sendPostForm('$PHP_SELF?$page_name=$ppage$param')\"><img src='".$_img_server."/b_prev1.jpg'></a></li>";
		   
		   for($spage; $spage <= $epage; $spage++){
			  if($page == $spage) {
					
					echo "<li class='num active'><a href='javascript:void(0)'><b>$spage</b></a></li> ";
					if($spage != $epage) echo " ";
			  } else {
				if($spage == $epage) {
					echo "<li class='num'><a href='javascript:void(0)' onClick=\"sendPostForm('$PHP_SELF?$page_name=$spage$param')\"> $spage </a></li> ";
				} else {
					echo "<li class='num'><a href='javascript:void(0)' onClick=\"sendPostForm('$PHP_SELF?$page_name=$spage$param')\"> $spage </a></li> ";
				}
			  }
		   }

		   echo "          <li class='next'><a href='javascript:void(0)' onClick=\"sendPostForm('$PHP_SELF?$page_name=$npage$param')\"><img src='" . $_img_server . "/b_next1.jpg' /></a></li>";
		   echo "          <li><a href='javascript:void(0)' onClick=\"sendPostForm('$PHP_SELF?$page_name=$page_count$param')\"><img src='" . $_img_server . "/b_next2.jpg'  /></a></li>";
		   echo "         </ul></div> ";	   

	}

}

// 페이지 리스트 출력
function print_pagelistNew3Func($obj_id,$data_url,$page, $list_amount, $page_count, $param, $page_type = "", $total = 0)
{

   global $code, $catcode, $orderby, $skin_dir, $ptype, $_img_server, $_COOKIE,  $_LANG_TEXT;

	//echo "  $page, $list_amount, $page_count, $param";
	//exit;

	$numbertext = $_LANG_TEXT['numbertext'][$_COOKIE['dpt_lang']];

   if($skin_dir == "") $skin_dir = ".";
   if($param != "") $param = "&".$param;

	if(($page%$list_amount) == 0) $tmp = $page-1;
	else $tmp = $page;
	
	$spage = floor($tmp/$list_amount)*$list_amount+1;
	if($spage <= 1) $ppage = 1;
	else $ppage = $spage - $list_amount;

	$epage = $spage+$list_amount-1;
	if($epage >= $page_count){
		$epage = $page_count;
		$npage = $page_count;
	}else{
		$npage = $epage + 1;
	}

	if(!empty($page_type)) {
		$page_name = strtolower($page_type)."page";
	} else {
		$page_name = "page";
	}


	echo '		<div id="paging2"><ul>
			';

	if($epage > 0) {

		echo "<li><a href='javascript:LoadPageDataList(\"$obj_id\",\"$data_url\",\"$page_name=1$param\")' ><img src='".$_img_server."/b_prev2.jpg'></a></li>";
		echo "<li class='prev'><a href='javascript:LoadPageDataList(\"$obj_id\",\"$data_url\",\"$page_name=$ppage$param\")' ><img src='".$_img_server."/b_prev1.jpg'></a></li>";
	   
	   for($spage; $spage <= $epage; $spage++){
	      if($page == $spage) {
				
				echo "<li class='num active'><a href='javascript:'><b>$spage</b></a></li> ";
				if($spage != $epage) echo " ";
	      } else {
	      	if($spage == $epage) {
	      		echo "<li class='num'><a href='javascript:LoadPageDataList(\"$obj_id\",\"$data_url\",\"$page_name=$spage$param\")' > $spage </a></li> ";
	      	} else {
	      		echo "<li class='num'><a href='javascript:LoadPageDataList(\"$obj_id\",\"$data_url\",\"$page_name=$spage$param\")' > $spage </a></li> ";
	      	}
	      }
	   }

	   echo "          <li class='next'><a href='javascript:LoadPageDataList(\"$obj_id\",\"$data_url\",\"$page_name=$npage$param\")' ><img src='" . $_img_server . "/b_next1.jpg' /></a></li>";
	   echo "          <li><a href='javascript:LoadPageDataList(\"$obj_id\",\"$data_url\",\"$page_name=$page_count$param\")' ><img src='" . $_img_server . "/b_next2.jpg'  /></a></li>";
	   echo "         </ul></div> ";
	   
	}

}


function info_replace($site_info, $re_info, $msg)
{

	global $HTTP_HOST;

	$date = date('Y')."년 ".date('m')."월 ".date('d')."일";

	$msg = str_replace("{DATE}", $date, $msg);
	$msg = str_replace("{MEM_ID}", $re_info[id], $msg);
	$msg = str_replace("{MEM_PW}", $re_info[passwd], $msg);
	$msg = str_replace("{MEM_NAME}", $re_info[name], $msg);
	$msg = str_replace("{SITE_NAME}", $site_info[site_name], $msg);
	$msg = str_replace("{SITE_EMAIL}", $site_info[site_email], $msg);
	$msg = str_replace("{SITE_TEL}", $site_info[site_tel], $msg);
	$msg = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $msg);

	return $msg;

}

function site_replace($site_info, $re_info, $msg) {

	global $HTTP_HOST;

	$date = date('Y')."년 ".date('m')."월 ".date('d')."일";

	$msg = str_replace("{SITE_URL}", "http://".$HTTP_HOST, $msg);

	return $msg;

}

// 이메일 발송
function send_mail($se_name, $se_email, $re_name, $re_email, $subject, $content, $cc="", $bcc="")
{

	$headers .= "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=euc-kr\r\n";
	$headers .= "From: ".$se_name." <".$se_email.">\r\n";
	//$headers .= "To: ".$re_name." <".$re_email.">\r\n";
	$headers .= "Reply-To: ".$se_name." <".$se_email.">\r\n";
	$headers .= "X-Priority: 3\r\n";
	$headers .= "X-MSMail-Priority: High\r\n";
	$headers .= "X-Mailer: Just My Server";

	if($cc) $headers .= "Cc: $cc\n";
	if($bcc) $headers .= "Bcc: $bcc";

	$subject = stripslashes($subject);
	$content = stripslashes($content);

	$result = mail( $re_email , $subject , $content , $headers );

	return $result;

}


// SMS 발송
function send_sms($se_num, $re_num, $message, $se_name="")
{

	global $site_info;

	/**************************************************************************************
		SMS 클래스 사용 예제입니다.
	**************************************************************************************/
	include_once WIZHOME_PATH."/inc/class.sms.php";

	$sms_server	= "211.172.232.124";	## SMS 서버
	$sms_id		= $site_info[sms_id];				## icode 아이디
	$sms_pw		= $site_info[sms_pw];				## icode 패스워드
	//$portcode	= 1;				## 정액제 : 2, 충전식 : 1
	if($site_info[sms_type] == "" || $site_info[sms_type] == "C") $portcode = 1;
	else if($site_info[sms_type] == "J") $portcode = 2;

	$SMS	= new SMS;
	$SMS->SMS_con($sms_server,$sms_id,$sms_pw,$portcode);

	/**************************************************************************************
	1단계: 보낼 메시지를 저장합니다. 쇼핑몰에서 장바구니에 물건을 담는다고 생각하면 됩니다.

		일반 메시지를 보낼 경우 SMS->Add() 를 사용합니다. 인자는 다음과 같습니다.
			1. 받는 사람 핸드폰 번호
			2. 보내는 사람 전화 (회신번호)
			3. 보내는 사람 이름
			4. 보내는 메시지 (80자 이내)
			5. 예약시간 (12자 - 예약발송일 경우에만 입력. 예: 2001년 5월30일 오후2시30분이면 200105301430)

		URL을 보낼 경우 SMS->AddURL() 을 사용합니다. 인자는 다음과 같습니다.
			1. 받는 사람 핸드폰 번호
			2. URL (50자 이내)
			3. 보내는 메시지 (80자 이내)
			4. 예약시간 (12자 - 예약발송일 경우에만 입력. 예: 2001년 5월30일 오후2시30분이면 200105301430)

		잘못된 값이 들어갔을 경우 에러메시지가 리턴됩니다.

		※ .URL 콜백의 경우 건당 50원의 요금이 부과 됩니다.
		※ .SKT(011,017) 번호로 발송하실 경우 사용자 동의를 받지 않아 전송 실패일 경우에도
		    정상적으로 요금이 청구 됩니다.
		※ .KTF(016,018) 번호로 발송하실 경우 회신번호를 반드시 입력하셔야 정상적으로 송신이 됩니다.
	**************************************************************************************/

	$tran_phone	= str_replace("-", "", $re_num);		# 수신번호
	$tran_callback	= str_replace("-", "", $se_num);			# 회신번호
	$tran_msg		= $message;	# 발송 메세지
	$tran_date	= "";				#발송시간
	#즉시 전송일 경우 $tran_date	= "" ;
	#예약 전송일 경우 $tran_date	= "200412312359";	# 2004년 12월 31일 23시 59분

	$result = $SMS->Add($tran_phone,"$tran_callback","$sms_id","$tran_msg","$tran_date");
	//if ($result) echo $result; else echo "일반메시지 입력 성공<BR>";

	//$result = $SMS->AddURL($tran_phone,"$tran_callback","w.yahoo.co.kr","테스트입니다","");
	//if ($result) echo $result; else echo "URL 입력 성공<BR>";
	//echo "<HR>";

	/**************************************************************************************
	2단계: 저장해둔 메시지를 전송합니다. 쇼핑몰에서 결제를 한다고 생각하면 됩니다.

		SMS->Send() 를 실행하면 모아둔 메시지를 모두 발송합니다.
		이때 SMS->Send()가 리턴하는 값은 true, false 입니다.
		이것은 서버와의 접속 상태를 나타냅니다.

		SMS->Send() 를 실행하고 난 후에는 메시지 발송 결과를 조회할 수 있습니다.
		메시지 발송 결과는 SMS->Result 배열에 저장되어 있습니다.
		데이타 형식은 "핸드폰 번호 : 메시지 고유번호" 입니다. 예) 0115511474:13622798
		전송이 제대로 되지 않은 건에 대해서는 에러 표시가 납니다. 예) 0195200107:Error

		만약 같은 클래스를 재사용할 경우, SMS->Init() 명령으로 메시지 발송 결과를 없애주십시오.
	**************************************************************************************/

	$result = $SMS->Send();
	if ($result) {
		//echo "SMS 서버에 접속했습니다.<br>";
		$success = $fail = 0;
		foreach($SMS->Result as $result) {
			list($phone,$code)=explode(":",$result);
			if ($code=="Error") {
				//echo $phone.'로 발송하는데 에러가 발생했습니다.<br>';
				$fail++;
			} else {
				//echo $phone."로 전송했습니다. (메시지번호:".$code.")<br>";
				$success++;
			}
		}
		//echo $success."건을 전송했으며 ".$fail."건을 보내지 못했습니다.<br>";
		$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
	} else {
		//echo "에러: SMS 서버와 통신이 불안정합니다.<br>";
	}

	//echo "<table width='100%'><tr><td align='center'><span onClick='self.close()' style='cursor:pointer'>[닫기]</span></td></tr></table>";

}

// 메일내용 생성
function send_mailsms($type, $re_info)
{

	global $site_info;

	// 관리자 정보 가져오기
	include WIZHOME_PATH."/inc/site_info.php";

	$se_name = $site_info[site_name];
	$se_email = $site_info[site_email];
	$se_tel = $site_info[site_tel];

	// 메일/sms 발송내용 가져오기
	$mail_info = get_table("wiz_mailsms", "code = '$type'");

	$mail_info[email_subj] = info_replace($site_info, $re_info, $mail_info[email_subj]);
	$mail_info[email_msg] = info_replace($site_info, $re_info, $mail_info[email_msg]);
	$mail_info[sms_msg] = info_replace($site_info, $re_info, $mail_info[sms_msg]);

	if($mail_info[email_send] != "N"){
		send_mail($se_name, $se_email, $re_info[name], $re_info[email], $mail_info[email_subj], $mail_info[email_msg]);
	}

	if($mail_info[sms_send] != "N"){

		send_sms($se_tel, $re_info[hphone], $mail_info[sms_msg], $se_name);
	}


}


// 파일 확장자 체크
function file_check( $filename, $file_str = "php|htm|html|inc|htm|shtm|ztx|dot|cgi|pl|phtm|ph" ) {

	$check_str = "";
	$file_arr = explode("|", $file_str);
	for($ii = 0; $ii < count($file_arr); $ii++) {
		if(!empty($check_str)) $check_str .= "|";
		$check_str .= "\.".$file_arr[$ii];
	}

	//업로드 금지 확장자 체크
	if(eregi($check_str, $filename)) {
		error("해당 파일은 업로드할 수 없는 형식입니다.");
		exit;
	}

}


// 보기페이지 이미지 리사이즈
function view_img_resize() {

	global $_ResizeCheck;

	if($_ResizeCheck) {
?>
<!-- 이미지 리사이즈를 위해서 처리하는 부분 -->
<script>
	function wiz_img_check(){
		var wiz_main_table_width = document.wiz_get_table_width.width;
		var wiz_target_resize_num = document.wiz_target_resize.length;
		for(i=0;i<wiz_target_resize_num;i++){
			if(document.wiz_target_resize[i].width > wiz_main_table_width) {
				document.wiz_target_resize[i].width = wiz_main_table_width;
			}
		}
	}
	window.onload = wiz_img_check;
</script>

<?
	}

}

// 자동등록방지코드 생성
function get_spam_check() {

	global $is_norobot;
	global $norobot_img;
	global $norobot_msg;
	global $norobot_key;
	global $spam_check;

	global $form_info;	// 폼메일 자동등록방지 코드 생성 시 필요

	if(!empty($form_info[idx])) $idx = $form_info[idx];

	$is_norobot = false;

	$tmp_str = substr(md5(rand()),0,12); // 임의의 md5 문자열을 생성

	list($usec, $sec) = explode(' ', microtime()); // 난수 발생기
	$seed =  (float)$sec + ((float)$usec * 100000);
	srand($seed);
	$keylen = strlen($tmp_str);
	$div = (int)($keylen / 2);
	while (count($arr) < 6)
	{
	    unset($arr);
	    for ($i=0; $i<$keylen; $i++)
	    {
	        $rnd = rand(1, $keylen);
	        $arr[$rnd] = $rnd;
	        if ($rnd > $div) break;
	    }
	}

	sort($arr);	// 배열에 저장된 숫자를 차례대로 정렬

	$norobot_key = "";
	$norobot_str = "";
	$m = 0;

	for ($i=0; $i<count($arr); $i++)
	{
	    for ($k=$m; $k<$arr[$i]-1; $k++)
	        $norobot_str .= $tmp_str[$k];
	    $norobot_str .= "<font size=3 color=#FF0000><b>{$tmp_str[$k]}</b></font>";
	    $norobot_key .= $tmp_str[$k];
	    $m = $k + 1;

	}

	if ($m < $keylen) {
	    for ($k=$m; $k<$keylen; $k++)
	        $norobot_str .= $tmp_str[$k];
	}

	$norobot_str = "<font color=#999999>$norobot_str</font>";

	$ss_norobot_key = $norobot_key;
	$is_norobot = true;

	if (function_exists("imagecreate")) {	// 이미지 생성이 가능한 경우 자동등록체크코드를 이미지로 생성
	  $norobot_img = "<img src='/admin/bbs/norobot_image.php?ss_norobot_key=$norobot_key' border='0' style='border: 1px solid #343d4f;' align='absmiddle'>";
	  $norobot_msg = "* 왼쪽의 자동등록방지 코드를 입력하세요.";
	}
	else {
	 $norobot_img = $norobot_str;
	 $norobot_msg = "* 왼쪽의 글자중 <FONT COLOR='red'>빨간글자</font>만 순서대로 입력하세요.";
	}
	$spam_check = $norobot_img." <input type='text' name='vcode' id='vcode' class='input' size='15' /> ".$norobot_msg;

	?>
	<script Language="JavaScript" src="/admin/js/md5.js"></script>
	<script language="javascript">
	<!--

	function hex_md5(s) {
		return binl2hex(core_md5(str2binl(s), s.length * chrsz));
	}
	var md5_norobot_key<?=$idx?> = "<?=md5($norobot_key)?>";

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g, "");
	}

	//-->
	</script>
<?php
}

// 디렉토리 삭제
function rm_dir($path) {
	$oDir = @openDir($path);
	while($entry = @readDir($oDir)) {
		if($entry <> '.' && $entry <> '..') {
			if(Is_Dir($path.'/'.$entry)) {
				rm_dir ($path.'/'.$entry);
			} else {
				@UnLink ($path.'/'.$entry);
			}
		}
	}
	@closeDir($oDir);
	@RmDir($path);
}

//SQL 입력값 문자열 필터
//$str = 입력 문자열
function sql_filter($str) {
	//1단계 ? ',",NULL 문자 필터링. 각 문자들에 백슬래쉬(\) 삽입됨. 필수 항목
	//출력시 stripslashes()함수를 이용하여 백슬래쉬(\)를 제거
	if (!get_magic_quotes_gpc()) $str = addslashes($str);

	//3단계 ? 특수 문자 및 문자열 필터링
	//WHERE 구문에서 쓰여지는 데이터만 사용하는 것이 바람직하다.
	$search = array("--","#",";");
	$replace = array("\--","\#","\;");
	$str = str_replace($search, $replace, $str);

	return $str;
}

Function xss_check( $get_String, $get_HTML = true ) {

	// XSS (Cross Site Script) 막기
	$get_String = preg_replace("/(on)([a-z]+)([^a-z]*)(\=)/i", "&#111;&#110;$2$3$4", $get_String);
	$get_String = preg_replace("/(dy)(nsrc)/i", "&#100;&#121;$2", $get_String);
	$get_String = preg_replace("/(lo)(wsrc)/i", "&#108;&#111;$2", $get_String);
	$get_String = preg_replace("/(sc)(ript)/i", "&#115;&#99;$2", $get_String);
	$get_String = preg_replace("/(ex)(pression)/i", "&#101&#120;$2", $get_String);

   if(!$get_HTML) {
	  $get_String = STR_REPLACE( "<", "&lt;", $get_String );
	  $get_String = STR_REPLACE( ">", "&gt;", $get_String );
   }
   return $get_String;

}

// 문자열 변환 in_charset → out_charset
function str_conv($str, $mode) {
	if(!strcmp(strtolower($mode), 'euc-kr')) {
		$in_charset = "utf-8";
		$out_charset = "euc-kr";
	} else if(!strcmp(strtolower($mode), 'utf-8')) {
		$in_charset = "euc-kr";
		$out_charset = "utf-8";
	}

	if(iconv($out_charset,$out_charset,$str)==$str) return $str;
	else return iconv($in_charset,$out_charset,$str);
}

function euc_kr_utf_8($val) {
	$val = iconv("EUC-KR", "UTF-8", $val); 
	return $val;
}

function utf_8euc_kr($val) {
	$val = iconv("UTF-8", "EUC-KR", $val); 
	return $val;
}

function utf8_strcut( $str, $size,$shortening='..' ) {
        $substr = substr( $str, 0, $size * 2 );
        $multi_size = preg_match_all( '/[\\x80-\\xff]/', $substr, $multi_chars );

        if ( $multi_size > 0 )
            $size = $size + intval( $multi_size / 3 ) - 1;

        if ( strlen( $str ) > $size )
        {
            $str = substr( $str, 0, $size );
            $str = preg_replace( '/(([\\x80-\\xff]{3})*?)([\\x80-\\xff]{0,2})$/', '$1', $str );
            
			$str= $str.$shortening;
        }
		//echo $str . "<br />";
		//Local State~RF41428b.TMP
		
		//$str = str_replace('~', '', $str);
        return $str;
}



//***** SQL Injection 방지 코드 ******//
function sql_injection_protect($content) {

		if( is_array($content) ){
				
                $content = array_map('sql_injection_protect', $content);
		}else{

				
				$content = htmlentities($content, null, 'utf-8'); //2019-05-17 hey 추가
				
				$content = preg_replace('/([\x00-\x08\x0b-\x0c\x0e-\x19])/', '',$content);
				$content = preg_replace("/union[^\x21-\x7e]/i", "union&nbsp;", $content);
				$content = preg_replace("/select[^\x21-\x7e]/i", "select&nbsp;", $content);
				$content = preg_replace("/insert[^\x22-\x7e]/i", "insert&nbsp;", $content);
				$content = preg_replace("/drop[^\x21-\x7e]/i", "drop&nbsp;", $content);
				$content = preg_replace("/update[^\x21-\x7e]/i", "update&nbsp;", $content);
				//$content = preg_replace("/and[^\x21-\x7e]/i", "and&nbsp;", $content);
				//$content = preg_replace("/or[^\x21-\x7e]/i", "or&nbsp;", $content);

				$content = preg_replace("/if[^\x21-\x7e]/i", "if&nbsp;", $content);
				$content = preg_replace("/[^\x21-\x7e]union/i", "&nbsp;union", $content);
				$content = preg_replace("/[^\x21-\x7e]select/i", "&nbsp;select", $content);
				$content = preg_replace("/[^\x21-\x7e]insert/i", "&nbsp;insert", $content);
				$content = preg_replace("/[^\x21-\x7e]drop/i", "&nbsp;drop", $content);
				$content = preg_replace("/[^\x21-\x7e]update/i", "&nbsp;update", $content);
				//$content = preg_replace("/[^\x21-\x7e]and/i", "&nbsp;and", $content);
				//$content = preg_replace("/[^\x21-\x7e]or/i", "&nbsp;or", $content);
				$content = preg_replace("/[^\x21-\x7e]if/i", "&nbsp;if", $content);
				
				//$content = preg_replace("/[\+%\\;\^~|\!\?\*$#\[\]\{\}]/i", "", $content);
				
				$content = preg_replace("/</", "&lt;", $content);
				$content = preg_replace("/>/", "&gt;", $content);
				$content = preg_replace("/'/i", "&#39;", $content);
				$content = preg_replace("/`/i", "&#96;", $content);
				$content = preg_replace('/"/', "&quot;", $content);
				$content = preg_replace("/--/i", "&#95;&#95;", $content);
				
				/*-------------------
				$content = preg_replace("/&/", "&amp", $content);
				$content = preg_replace("/&ampamp/", "&amp", $content);
				$content = preg_replace("/&ampnbsp/", "&nbsp", $content);
				$content = preg_replace("/&amplt/", "&lt", $content);
				$content = preg_replace("/&ampgt/", "&gt", $content);
				$content = preg_replace("/&amp#39/", "&#39", $content);
				$content = preg_replace("/&amp#96/", "&#96", $content);
				$content = preg_replace("/&ampquot/", "&quot", $content);
				$content = preg_replace("/&amp#95/", "&#95", $content);
				-------------*/

				if(strpos($content, 'delete') >-1 and strpos($content, 'from') > -1 ) $content = "";
				if(strpos($content, 'union' ) >-1 and strpos($content, 'select') > -1 ) $content = "";
				if(strpos($content, 'insert' ) >-1 and strpos($content, 'into') > -1 ) $content = "";
				if(strpos($content, 'drop' ) >-1 and strpos($content, 'table') > -1 ) $content = "";
				if(strpos($content, 'drop' ) >-1 and strpos($content, 'column') > -1 ) $content = "";
				if(strpos($content, 'alter' ) >-1 and strpos($content, 'table') > -1 ) $content = "";
				if(strpos($content, 'update' ) >-1 and strpos($content, 'from') > -1 ) $content = "";
				if(strpos($content, 'or' ) >-1 and strpos($content, '1=1') > -1 ) $content = "";


				//서버단 어플리케이션 실행 방지
				if( strpos($content, 'require(' ) >-1 
					or strpos($content, 'include(' ) >-1 
					or strpos($content, 'preg_replace(' ) >-1 
					or strpos($content, 'exec(' ) >-1 
					or strpos($content, 'passthru(' ) >-1 
					or strpos($content, 'system(' ) >-1 
					or strpos($content, 'popen(' ) >-1 ) {
						$content = "";
				}

		}
		
		return $content;
}


if( is_array($_POST) ) $_POST = array_map( 'sql_injection_protect', $_POST );
if( is_array($_GET) ) $_GET = array_map( 'sql_injection_protect', $_GET);
if( is_array($_REQUEST) ) $_REQUEST = array_map( 'sql_injection_protect', $_REQUEST);

//***** SQL Injection 방지 코드 ******//




function encrypt($value, $key){
	$result = "";
	for($i=0; $i<strlen($value); $i++){
		$val1 = chr(ord(substr($value, $i, 1))+strlen($value));
		$key1 = substr($key, ($i % strlen($key)), 1);
		$val1 = $val1 ^ $key1;
		$result .= $val1;
	}
	return base64_encode($result);
}


function decrypt($value, $key){
	$result = "";
	$value = base64_decode($value);
	for($i=strlen($value)-1; $i>=0; $i--){
		$val2 = substr($value, $i, 1);
		$key2 = substr($key, ($i % strlen($key)), 1);
		 
		$val2 = $val2 ^ $key2;
		$val2 = chr(ord($val2)-strlen($value));
		$result = $val2.$result;
	}
	return $result;
}



 function unQuotChars( $string ) 
 { 
		$string = str_replace ( '&quot;', '"', $string ); 			  
		return $string; 
 }


 function unhtmlspecialchars( $string ) 
 { 
		$string = str_replace ( '&amp;', '&', $string ); 
		$string = str_replace ( '&#039;', '\'', $string ); 
		$string = str_replace ( '&quot;', '\"', $string ); 
		$string = str_replace ( '&lt;', '<', $string ); 
		$string = str_replace ( '&gt;', '>', $string ); 
	  
		return $string; 
 }

function setDateFormat($date,$format='Y-m-d'){

	$date = preg_replace('/[^0-9]*/s', '', $date);

	if($date=="") return;
	$strDate = date_format(date_create($date),$format);
	return $strDate;
}

//AES 암호화
function aes_256_enc($str){

	global $_encryption_kind;
	global $_AES_KEY_256;
	global $_AES_KEY;
	global $_AES_IV;

	if($str=="") return "";

	if($_encryption_kind=="2"){	//**aes256

		$enc = AES_Rijndael_Encript($str,$_AES_KEY_256,$_AES_IV);

	}else{	//**aes192

		$enc = AES_Rijndael_Encript($str,$_AES_KEY,$_AES_IV);

	}
	return $enc;
}

//AES 복호화
function aes_256_dec($enc){

	global $_encryption_kind;
	global $_db_encription_flag;
	global $_AES_KEY_256;
	global $_AES_KEY;
	global $_AES_IV;

	if($enc=="") return "";

	if($_encryption_kind=="2"){ //**aes256

		$dec = AES_Rijndael_Decript($enc,$_AES_KEY_256,$_AES_IV);

		if($_db_encription_flag=="2"){	//이름,전화번호,이메일 암호화 

			$rtn_dec = $dec;	

		}else{	//전화번호,이메일 암호화

			$aes_256_enc = AES_Rijndael_Encript($dec,$_AES_KEY_256,$_AES_IV);

			if($enc==$aes_256_enc){
				$rtn_dec = $dec;
			}else{	//암호화 안된 경우
				$rtn_dec = $enc;
			}
			
		}
		
		/*
		if($enc == $aes_256_enc){	//aes256 으로 암호화 된 경우

			$rtn_dec = $dec;

		}else{	//aes192로 암호화된 경우

			$dec = AES_Rijndael_Decript($enc,$_AES_KEY,$_AES_IV);	
			$aes_192_enc = AES_Rijndael_Encript($dec,$_AES_KEY,$_AES_IV);	

			if($enc==$aes_192_enc){
				$rtn_dec = $dec;
			}else{	//암호화 안된 경우
				$rtn_dec = $enc;
			}
		}
		*/

	}else {	//**aes192
		
			$dec = AES_Rijndael_Decript($enc,$_AES_KEY,$_AES_IV);	
			$aes_192_enc = AES_Rijndael_Encript($dec,$_AES_KEY,$_AES_IV);	

			if($enc==$aes_192_enc){
				$rtn_dec = $dec;
			}else{	//암호화 안된 경우
				$rtn_dec = $enc;
			}

	}
	return $rtn_dec;
}

// 배열 첫번째 값 반환
function array_first($array) {
	$keys = array_keys($array);
	$key  = array_shift($keys);

	return array($key, $array[$key]);
}

// 메뉴에서 코드, 페이지로 링크 찾기
function getMenuPageLink($code, $page) {
	$_PAGE = $GLOBALS['_PAGE'];
	$_www_server = $GLOBALS['_www_server'];

	foreach($_PAGE as $cate => $arr){
		if ($code == $_PAGE[$cate]["MENU_CODE"]) {
			$link = $_PAGE[$cate]["MENU_LINK"];
			$path = $_PAGE[$cate]["MENU_PATH"];

			if ($page != "all") {
				$link = "{$_www_server}{$path}/{$page}.php";
			}

			return $link;
			break;
		}
	}
}
?>
