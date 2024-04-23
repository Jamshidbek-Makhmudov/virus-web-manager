<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$emp_seq = $_POST["emp_seq"];

$Model_manage = new Model_manage();

//관리자정보 가져오기
$args = array("emp_seq"=>$emp_seq);
$Model_manage->SHOW_DEBUG_SQL = false;
$result = $Model_manage->getEmpInfoBySeq($args);
if($result){
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
		$emp_name = aes_256_dec($row['emp_name']);
		$admin_level = $row['admin_level'];
	}
}

//관리자 메뉴세부권한 가져오기
//$Model_manage = new Model_manage();
$args = array("emp_seq"=>$emp_seq);
$result = $Model_manage->getEmpMenuDetailAuth($args);

$emp_menu_auth = array();
if($result){
	while ($row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {

		$emp_menu_auth[$row['menu_code']][$row['page_code']] = $row['exec_auth'];
	}
}

//print_r($emp_menu_auth);
$modal_title = $emp_name." ".trsLang('페이지접근권한설정','setpageaccesstext');
?>
<script>
	$("document").ready(function(){
		$("#modal_admin_auth_detail .modal-title").text('<? echo $modal_title;?>');
		<? if($admin_level=="SUPER"){	//최고관리자는 메뉴 권한을 수정 차단?>
			$("#frmAuthDetails input[type='checkbox']").addClass("disable").removeAttr("onclick").on("click",function(){return false;});
		<?}?>
	})
</script>
<form name='frmAuthDetails' id='frmAuthDetails' method='post'>
	<input type='hidden' name='emp_seq' value='<? echo $emp_seq?>'>
	<input type='hidden' name='proc_name'>
	<input type='hidden' name='proc'>
	<table class='view'  >
		<?
			foreach($_PAGE as $cate => $menu){
				if($cate=="MAIN") continue; 
					$menu_code = $menu['MENU_CODE'];
					$menu_name = $menu['MENU_NAME'];
					$pagelist = $menu['PAGE'];
		?>
				<tr>
					<td style='width:150px'><? echo $menu_name?></td>
					<td><table class='in-view'>
						<?

						$_pagelist = array_merge(array("all"=>array("","","전체")),$pagelist);

						//메뉴선택비활성화
						if(stripos($_CODE['admin_menu_auth'][$admin_level],$menu_code)===false){
							$emp_page_auth_disabled = "disabled";
						}else $emp_page_auth_disabled = "";

						foreach($_pagelist as $page_code=>$pinfo){
							$page_name = $pinfo[2];
							$menu_page_code = $menu_code."_".$page_code;
							$str_emp_exec_auth = $emp_menu_auth[$menu_code][$page_code];
							
							$emp_exec_auth = array();
							$emp_page_auth_checked = "";
							if($str_emp_exec_auth != ""){
								$emp_page_auth_checked = "checked";
								$emp_exec_auth = explode(",",$str_emp_exec_auth);
							}
							
						?>
							<tr >
								<td width='200px'>
									<input type='checkbox' name='page_auth_<? echo $menu_code?>[]' id='page_auth_<? echo $menu_page_code?>' value='<? echo $page_code?>' onclick='setPageExecAuthAll()'  class='mcode_<? echo $menu_code?>' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? echo $emp_page_auth_checked;?> <? echo $emp_page_auth_disabled;?>>
									<label for='page_auth_<? echo $menu_page_code?>'><? echo $page_name ?></label>
								</td>
								<td>
									<input type='hidden' name='exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='R'  >
									<label for='exec_auth_read_<? echo $menu_page_code?>' ><? echo trsLang('보기','btnview')?></label> 

									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_create_<? echo $menu_page_code?>' value='C'  data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("C",$emp_exec_auth)) echo "checked";?> <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_create_<? echo $menu_page_code?>'><? echo trsLang('생성','createtext')?></label> 

									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_update_<? echo $menu_page_code?>' value='U' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("U",$emp_exec_auth)) echo "checked";?> <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_update_<? echo $menu_page_code?>'><? echo trsLang('수정','btnupdate')?></label> 

									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_delete_<? echo $menu_page_code?>' value='D' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("D",$emp_exec_auth)) echo "checked";?>  <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_delete_<? echo $menu_page_code?>'><? echo trsLang('삭제','deletedeletetext')?></label>
 
									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]'  class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_download_<? echo $menu_page_code?>' value='P' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("P",$emp_exec_auth)) echo "checked";?>  <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_download_<? echo $menu_page_code?>'><? echo trsLang('다운로드/인쇄','downloadandprinttext')?></label> 
								</td>
							</tr>
						<?}?>
						</table></td>
				</tr>
		<?
			}
		?>
	</table>
</form>