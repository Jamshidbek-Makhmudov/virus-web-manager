<?php
$page_name = "access_control";

$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI,  "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";

$tab = $_REQUEST["tab"];
$_v_user_seq = intVal($_REQUEST["v_user_seq"]);
$_v_user_list_seq = intVal($_REQUEST["v_user_list_seq"]);

$Model_User=new Model_User();

$args = array("v_user_seq" => $_v_user_seq);

$Model_User->SHOW_DEBUG_SQL = false;
$result = $Model_User->getUserVisitStatus($args);

$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$last_v_user_list_seq = $row['last_v_user_list_seq'];
$v_user_name =  aes_256_dec($row['v_user_name']);
$v_user_name_en = $row['v_user_name_en'];
$v_user_belong = $row['v_user_belong'];

if($_encryption_kind=="1"){

	$email = $row['v_email_decript'];
	$phone_no = $row['v_phone_decript'];
	
}else if($_encryption_kind=="2"){
	
	if($row['v_email'] != ""){
		$email = aes_256_dec($row['v_email']);
	}

	$v_phone_enc = $row['v_phone'];

	if($row['v_phone'] != ""){
		$phone_no = aes_256_dec($row['v_phone']);
	}
}

$in_cnt = $row['in_cnt'];
$file_import_cnt = $row['file_import_cnt'];
$first_in_time = setDateFormat($row['first_in_time'],'Y-m-d H:i');
$last_in_time = setDateFormat($row['last_in_time'],'Y-m-d H:i');

$v_user_seq = $_v_user_seq;

if($_v_user_list_seq==""){
	$v_user_list_seq = $last_v_user_list_seq;	//마지막 방문 seq
}else{	
	$v_user_list_seq = $_v_user_list_seq;	//parameter로 넘어온 방문정보값
}

$param_enc = ParamEnCoding("src=USER_VISIT_STATUS&tab=".$tab."&v_user_seq=".$v_user_seq."&v_user_list_seq=" . $v_user_list_seq . ($param ? "&" : "") . $param);
?>
<script language="javascript">
	$(function() {
		var param_enc = "enc=<?=$param_enc?>";
		LoadPageDataList('user_visit_list',SITE_NAME+'/user/get_user_visit_list.php',param_enc);
	});
</script>
<div id="result_view">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'>[<?=$v_user_name?>]  <?=$_LANG_TEXT["ent_exit_status_theme"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<div class="page_right">
			<? $tab_url = $tab=="" ? "access_control.php" : $tab.".php";?>
			<span style='cursor:pointer' onclick="location.href='<? echo $_www_server?>/user/<? echo $tab_url?>'"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span>
		</div>

		<ul class='tab'>
			<li>
				<a  href='javascript:void(0)' onclick="sendPostForm('<? echo $_www_server?>/user/access_info.php?enc=<? echo $param_enc ?>')"><?= $_LANG_TEXT['access_info_theme'][$lang_code] ?></a>
			</li>
			<li  class="on " >
				<a  href='javascript:void(0)' onclick="sendPostForm('<? echo $_www_server?>/user/access_status_user.php?enc=<? echo $param_enc ?>')"><? echo trsLang('출입현황','inoutstatus');?></a>
			</li>
		</ul>
		
		<div>
			<div>
					<table class="view">
						<tr>
							<th style='width:150px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>
							<td style='width:350px'><?=$v_user_name?><?if($v_user_name_en != "") echo " (".$v_user_name_en.")";?></td>
							<th class="line" style='width:150px'><?= $_LANG_TEXT['belongtext'][$lang_code] ?></th>
							<td><?=$v_user_belong?></td>
						</tr>
						<tr class="bg">
							<th><?= $_LANG_TEXT['contactphonetext'][$lang_code] ?></th>
							<td><input type="text" name="v_phone" id="v_phone" value="<?php echo replaceHiddenChar($phone_no,0,3); ?>" >
									<a href="javascript:void(0)" onclick="showHiddenInfo('v_phone','<? echo $v_phone_enc?>')"><i class="fa fa-eye-slash"></i></a></td>
							<th class="line"><?= $_LANG_TEXT['emailtext'][$lang_code] ?></th>
							<td><?=$email?></td>
						</tr>

						<tr>
							<th><?= $_LANG_TEXT['dateFirstVisit'][$lang_code] ?></th>
							<td><?=$first_in_time?></td>
							<th class="line"><?= $_LANG_TEXT['finalVisit'][$lang_code] ?></th>
							<td><?=$last_in_time?></td>
						</tr>
						<tr class="bg">
							<th><?= $_LANG_TEXT['totalNumberVisit'][$lang_code] ?></th>
							<td><?=$in_cnt?></td>
							<th class="line"><?= $_LANG_TEXT['fileimporttimes'][$lang_code] ?></th>
							<td><?=$file_import_cnt?></td>

						</tr>
					</table>
				
			</div><BR>
			
			<!--출입내역-->
			<div>
				 <div class="sub_tit" style='line-height:30px;'> >  <?= $_LANG_TEXT['entryExitHistory'][$lang_code] ?></div>
				<div id='user_visit_list'></div>
			</div>

		</div>


	</div>
</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>