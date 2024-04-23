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

$tab =$_REQUEST['tab'];
$manager_name = $_REQUEST['manager_name'];
$manager_name_en = $_REQUEST['manager_name_en'];

$Model_User=new Model_User();

$args = array("manager_name" => $manager_name,"manager_name_en"=>$manager_name_en);

$result = $Model_User->getUserVisitStatus_Manager($args);
$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
$manager_dept = $row['manager_dept'];
$in_cnt = $row['in_cnt'];
$file_import_cnt = $row['file_import_cnt'];

$param_enc = ParamEnCoding("src=USER_VISIT_MGR_STATUS&manager_name=".$manager_name."&manager_name_en=" . $manager_name_en);
?>
<script language="javascript">
	$(function() {
		var param_enc = "enc=<?=$param_enc?>";
		LoadPageDataList('user_visit_list_statis',SITE_NAME+'/user/get_user_visit_list_statis.php',param_enc);
		LoadPageDataList('user_visit_list',SITE_NAME+'/user/get_user_visit_list.php',param_enc);
	});
</script>
<div id="result_view">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">

				 <h1><span id='page_title'>[<?=$manager_name?>]  <?=$_LANG_TEXT["personInCharge"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right">
			<? $tab_url = $tab=="" ? "access_control.php" : $tab.".php";?>
			<span style='cursor:pointer' onclick="sendPostForm('<? echo $_www_server?>/user/<? echo $tab_url?>?enc=<? echo $param_enc?>')"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span>
		</div>
		<!--  -->
		<div style="margin-top:50px">
			<div>
				<table class="view">
					<tr>
						<th style='width:150px'><?= $_LANG_TEXT['executives'][$lang_code] ?></th>
						<td style='width:350px'><?=$manager_name?>(<?=$manager_name_en?>)</td>
						<th class="line" style='width:150px'><?= $_LANG_TEXT['departmentText'][$lang_code] ?></th>
						<td><?=$manager_dept?></td>
					</tr>
					<tr class="bg">
						<th><?= $_LANG_TEXT['totalNumberVisit'][$lang_code] ?></th>
						<td><?=number_format($in_cnt)?></td>
						<th class="line"><?= $_LANG_TEXT['fileimporttimes'][$lang_code] ?></th>
						<td><?=number_format($file_import_cnt)?></td>
					</tr>

				</table>
			</div><BR>
			
			<!-- 출입자별 통계-->
			<div>
				<div class="sub_tit"> >  <? echo trsLang('출입자별통계','statisticsVisitor'); ?></div>
				<div id='user_visit_list_statis'></div>
			</div>
			
			<!-- 출입내역-->
			<div>
				<div class="sub_tit"> >  <? echo trsLang('출입내역','entryExitHistory'); ?></div>
				<div id='user_visit_list'></div>
			</div>

		</div>

	</div>
</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>
