<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$target = $_GET['target'];

if($target=="DEPT"){
	$str_target = trsLang('부서','depttext');
	$placeholder = trsLang('부서명을 입력하세요.','inputdept');
}else if($target=="EMP"){
	$str_target = trsLang('사용자','usertext');
	$placeholder = trsLang('이름 또는 영문 아이디를 입력하세요.','inputnameorid');
}

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

?>
<div id="mark" >
	<div class="content" style='width:800px;height:600px;margin-top:100px;'>
		<div class='tit'>
			<div class='txt'><? echo $str_target;?> <? echo trsLang('검색','usersearchtext');?></div>
			<div class='right'>
				<div class='close' onClick="closePopSyncEmployee();"></div>
			</div>
		</div>
		<div class="pop_search_box">
			<form name='popSearchForm' id='popSearchForm'>
					<input type='hidden' name='proc_name' id='proc_name'>
			<input type='hidden' name='target' value='<? echo $target;?>'>
			<input type="text" name="popsearchkey" id="popsearchkey" class="frm_input" maxlength='50'  style='width:80%;' placeholder="<? echo $placeholder;?>">
			<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return popSearchFormSubmit();">
			</form>
		</div>
		
		<div class='pop_search_result' >
		<table class="list" style='margin-top:0px;'>
			<tr>
				<th style='max-width:50px;width:50px;'><? echo trsLang('번호','numtext');?></th>
				<?if($target=="EMP"){?>
				<th style='max-width:200px;width:200px;'><? echo trsLang('이름','nametext');?></th>
				<th style='max-width:200px;width:200px'><? echo trsLang('영문ID','engnameid');?></th>
				<?}?>
				<th><? echo trsLang('부서명','deptnametext');?></th>
				<th style='width:100px;'><? echo trsLang('선택','choosetexts');?></th>
			</tr>
			<tr>
				<td colspan='5'><? echo trsLang('검색어를 입력하세요','inputsearchkeyword');?></td>
			</tr>
		</table>
		</div>


	</div><!--<div class="content">-->

</div><!--<div id="mark">-->
