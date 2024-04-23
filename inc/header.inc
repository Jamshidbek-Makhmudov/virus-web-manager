<?
$rand = rand();
?>
<!doctype html>
<html lang="ko">
<head>
<meta http-equiv="Content-Type" charset="utf-8">
<meta http-equiv="imagetoolbar" content="no">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<title><?php echo $_main_title; ?></title>
<link href="<?php echo $_css_server ?>/default.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_css_server ?>/layout.css?rnd=<? echo $rand?>" rel="stylesheet" type="text/css" />
<link href="<?php echo $_css_server ?>/select2.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_css_server ?>/main_dashboard.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_css_server ?>/jqueryui/smoothness/jquery-ui-1.11.4.themes.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_css_server ?>/lightview/lightview.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_css_server ?>/wickedpicker.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo $_css_server ?>/fontawesome/all.css">
<link rel="stylesheet" href="<?php echo $_css_server ?>/fontawesome/font-awesome.css">
<link rel="stylesheet" href="<?php echo $_css_server ?>/fontawesome/brands.css">
<link rel="stylesheet" href="<?php echo $_css_server ?>/fontawesome/solid.css">
<?php
/*
* 정책설정에 따라서 달라지는 css
* code.inc 참조 
*/
echo $custom_css;
?>
<link rel="shortcut icon" href="<?php echo $_www_server; ?>/vcs.ico" type="image/ico">
<link rel="icon" href="<?php echo $_www_server; ?>/vcs.ico" type="image/ico">
<?php
	//javascript SITE_NAME,lang_code 변수 선언
	include_once $_server_path . "/" . $_site_path . "/inc/language_setting.inc";
?>
<script type="text/javascript" src="<?php echo $_js_server ?>/common.js?rnd=<? echo $rand?>"></script>
<!-- <script type="text/javascript" src="<?php echo $_js_server ?>/jquery.min.js"></script> -->
<script type="text/javascript" src="<?php echo $_js_server ?>/jquery-1.8.1.min.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/jquery.cookie.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/jquery.fullscreen-0.4.1.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/jquery.wickedpicker.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.core.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.widget.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.mouse.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.draggable.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.position.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.resizable.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.dialog.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.core.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.blind.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.bounce.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.clip.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.drop.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.fold.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.slide.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.explode.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.scale.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.shake.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.transfer.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.effects.pulsate.js"></script>
<script language="JavaScript" src="<?php echo $_js_server ?>/ui/jquery.ui.datepicker.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/jquery.toggleOption.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/swfobject.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/site.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/Chart.bundle.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/chartjs-plugin-labels.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/utils.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/wvcs.js?rnd=<? echo $rand?>"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/wvcs2.js?rnd=<? echo $rand?>"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/wvcs3.js?rnd=<? echo $rand?>"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/select2.min.js"></script>
<script type="text/javascript" src="<?php echo $_js_server ?>/htmlspecialchars_decode.js"></script>

<script language="javascript">
	<?/*authcheck.inc에서 셋팅*/?>
	var __page_exec_auth = "<? echo $comm_current_user_page_exec_auth; ?>";
	var __user_edit_auth = "<? echo $_user_edit_auth;?>"
    $.datepicker.setDefaults({
        dateFormat: 'yy-mm-dd',
        prevText: '이전 달',
        nextText: '다음 달',
        monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
        dayNames: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
        dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
        showMonthAfterYear: true,
        yearSuffix: '년',
		changeMonth: true,
		changeYear: true
    });
</script>
</head>
<body>


