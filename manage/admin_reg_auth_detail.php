<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$preset_seq = $_POST["preset_seq"];

if (!empty($preset_seq)) {
	$Model_manage = new Model_manage();
	$args   = @compact("preset_seq");
	$preset = $Model_manage->getAdminMenuAuthPreset($args);

	@extract($preset);

	$create_emp_no   = $emp_no;
	$create_emp_name = aes_256_dec($emp_name);
	$create_date = date_format(date_create($create_date), "Y-m-d H:i");
} else {
	exit;
}

$modal_title = "[{$preset_title}] ".trsLang('메뉴 권한 상세보기','pageauthdetail');
?>
<script>
	$("document").ready(function(){
		$("#modal_admin_auth_detail .modal-title").text('<? echo $modal_title;?>');
	})
</script>
<style>
	.page_auth_list td { padding: 5px 5px 8px 20px !important;}
	.page_auth_list input[type=checkbox] { width:14px !important;height:14px !important;margin-top:3px !important;margin-right:3px !important; }
	.page_auth_list label { height:18px !important;line-height:18px !important; }
</style>
<form name='frmAuthDetails' id='frmAuthDetails' method='post'>
	<table class='view'  >
		<?
		$idx = 0;
		foreach($_PAGE as $cate => $menu){
			if($cate=="MAIN") continue; 
				$menu_code = $menu['MENU_CODE'];
				$menu_name = $menu['MENU_NAME'];
				$pagelist = $menu['PAGE'];
		?>
		<tr class="<?php echo ($idx % 2) ? "bg":"";?>">
			<td style='width:150px; border-bottom: 1px solid #737296;'><? echo $menu_name?></td>
			<td style="padding: 0; border-bottom: 1px solid #737296;">
				<table class='in-view page_auth_list'>
				<?
				$_pagelist = array_merge(array("all"=>array("","","전체")),$pagelist);

				//메뉴선택비활성화
				if(stripos($_CODE['admin_menu_auth'][$admin_level],$menu_code)===false){
					$emp_page_auth_disabled = "disabled";
				}else $emp_page_auth_disabled = "";

				foreach($_pagelist as $page_code=>$pinfo){
					$page_name = $pinfo[2];
					$menu_page_code = $menu_code."_".$page_code;
					$menu_exec_auth = $menu_auth[$menu_code][$page_code];
					
					$page_exec_auth = array();
					$page_auth_checked = "";
					$page_auth_display = "";

					if($menu_exec_auth != ""){
						$page_auth_checked = "checked='checked'";
						$page_exec_auth = explode(",",$menu_exec_auth);
						$page_auth_display = "style='display:inline;'";
					} else {
						$page_auth_checked = "disabled='disabled'";

						if ($page_code == 'all') {
							$page_auth_display = "style='display:inline;'";
						} else {
							$page_auth_display = "style='display:none;'";
						}
					}
					
				?>
				<tr <?php echo $page_auth_display; ?>>
					<td width='200px'>
						<input type='checkbox'<? echo $page_auth_checked;?> onClick="return false">
						<label><? echo $page_name ?></label>
					</td>
					<td>
						<label><? echo trsLang('보기','btnview')?></label> 

						<input type='checkbox' <? if(in_array("C", $page_exec_auth)) { echo "checked='checked'"; } else { echo "disabled='disabled'"; } ?> onClick="return false" style="margin-left: 15px !important;">
						<label><? echo trsLang('생성','createtext')?></label> 

						<input type='checkbox' <? if(in_array("U",$page_exec_auth)) { echo "checked='checked'"; } else { echo "disabled='disabled'"; }?> onClick="return false" style="margin-left: 15px !important;">
						<label><? echo trsLang('수정','btnupdate')?></label> 

						<input type='checkbox' <? if(in_array("D",$page_exec_auth)) { echo "checked='checked'"; } else { echo "disabled='disabled'"; }?>  onClick="return false" style="margin-left: 15px !important;">
						<label><? echo trsLang('삭제','deletedeletetext')?></label>

						<input type='checkbox' <? if(in_array("P",$page_exec_auth)) { echo "checked='checked'"; } else { echo "disabled='disabled'"; }?>  onClick="return false" style="margin-left: 15px !important;">
						<label><? echo trsLang('다운로드/인쇄','downloadandprinttext')?></label> 
					</td>
				</tr>
				<?}?>
				</table>
			</td>
		</tr>
		<?
			$idx++;
		}
		?>
	</table>
</form>