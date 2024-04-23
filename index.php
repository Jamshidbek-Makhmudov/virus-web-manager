<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = $_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;
include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<link rel="shortcut icon" href="<?php echo $_www_server; ?>/vcs.ico" type="image/ico">
<link rel="icon"href="<?php echo $_www_server; ?>/vcs.ico" type="image/ico">
<title><?php echo $_main_title; ?></title>
</head>
 	<frameset cols='0,*' border='0' frameborder='0' framespacing='0' id='dpt_frameset'>
				<frame name='leftBlank' marginwidth='0' marginheight='0' scrolling='no' noresize></frame>
				<frameset rows='0,0,*' border='0' frameborder='0' framespacing='0' >
					<frame id="dummy" name='dummy' 	src='./blank.php' 	scrolling='no' noresize ></frame>
					<frame id="fra_msg" name='fra_msg' 	src='./msg.php' 	scrolling='no' noresize ></frame>
					<frame id="fra_index" name='fra_index' src='./main.php' allowfullscreen></frame>
				</frameset>
			</frameset>
		<noframes>
<body bgcolor='#FFFFFF'>
</body>
</noframes>

