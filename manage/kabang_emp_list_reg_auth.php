<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$proc = $_POST['proc'];
$emp_seq_list = $_POST["emp_seq_list"];
$admin_level = $_POST["admin_level"];

$checked_menu = explode(",",$menu_list);

$proc_name = $_REQUEST[proc_name];
$work_log_seq = WriteAdminActLog($proc_name,'UPDATE');

$Model_manage = new Model_manage;

if($emp_seq_list==""){	//전체
	$emp_seq_list = array();
	$result = $Model_manage->getKabangEmpListAll($args);
	if($result){
		while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$emp_seq_list[] = $row['emp_seq'];
		}
	}
	$emp_seq_list = implode(",",$emp_seq_list);
}

?>
<script>
	$("document").ready(function(){
		<? if($admin_level=="SUPER"){	//최고관리자는 메뉴 권한을 수정 차단?>
			$("#frmAuthDetails input[type='checkbox']").addClass("disable").removeAttr("onclick").on("click",function(){return false;});
		<?}?>
	});
</script>
<form name='frmAuthDetails' id='frmAuthDetails' method='post'>
	<input type='hidden' name='emp_seq' value='<? echo $emp_seq_list?>'>
	<input type='proc_name' name='proc_name' >
	<input type='proc' name='proc' >
	<table class='view' >
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

						foreach($_pagelist as $page_code=>$pinfo){
							$page_name = $pinfo[2];
							$menu_page_code = $menu_code."_".$page_code;

							//메뉴선택비활성화
							if(stripos($_CODE['admin_menu_auth'][$admin_level],$menu_code)===false){
								$page_auth_disabled = "disabled";
							}else $page_auth_disabled = "";

							if($page_auth_disabled==""){
								$page_auth_checked = $page_code=="all" ? " checked " : "";
							}else{
								$page_auth_checked = "";
							}
							
						?>
							<tr >
								<td width='200px'>
									<input type='checkbox' name='page_auth_<? echo $menu_code?>[]' id='page_auth_<? echo $menu_page_code?>' value='<? echo $page_code?>' onclick='setPageExecAuthAll()'  class='mcode_<? echo $menu_code?>' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>'  <? echo $page_auth_disabled;?> <? echo $page_auth_checked;?>>
									<label for='page_auth_<? echo $menu_page_code?>'><? echo $page_name ?></label>
								</td>
								<td>
									<input type='hidden' name='exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='R'  >
									<label for='exec_auth_read_<? echo $menu_page_code?>' ><? echo trsLang('보기','btnview')?></label> 

									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_create_<? echo $menu_page_code?>' value='C'  data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>'  <? echo $page_auth_disabled;?>  <? echo $page_auth_checked;?> onclick="setPageExecAuth()">
									<label for='exec_auth_create_<? echo $menu_page_code?>'><? echo trsLang('생성','createtext')?></label> 

									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_update_<? echo $menu_page_code?>' value='U' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>'  <? echo $page_auth_disabled;?>  <? echo $page_auth_checked;?> onclick="setPageExecAuth()">
									<label for='exec_auth_update_<? echo $menu_page_code?>'><? echo trsLang('수정','btnupdate')?></label> 

									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_delete_<? echo $menu_page_code?>' value='D' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>'   <? echo $page_auth_disabled;?>  <? echo $page_auth_checked;?> onclick="setPageExecAuth()">
									<label for='exec_auth_delete_<? echo $menu_page_code?>'><? echo trsLang('삭제','deletedeletetext')?></label>
 
									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]'  class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' name='exec_auth_<? echo $menu_page_code?>' id='exec_auth_download_<? echo $menu_page_code?>' value='P' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>'   <? echo $page_auth_disabled;?>  <? echo $page_auth_checked;?> onclick="setPageExecAuth()">
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