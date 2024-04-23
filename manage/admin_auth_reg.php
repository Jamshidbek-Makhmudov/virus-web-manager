<?php
{
	$page_name = "admin_auth_list";

	$_server_path = $_SERVER['DOCUMENT_ROOT'];
    $_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, (strLen($_SERVER['REQUEST_URI']) - 1));
	$_apos = stripos($_REQUEST_URI,  "/");

	if($_apos > 0) {
		$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
	}

	$_site_path = $_REQUEST_URI;

	include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
	include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
	include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
}

{
    $params     = array();
	$preset_seq = $_REQUEST["preset_seq"];
	$search_opt = $_REQUEST["search_opt"];	// 검색옵션
	$search_key = $_REQUEST["search_key"];	// 검색어
	$page_title = $_LANG_TEXT["menuauthpresetinfo"][$lang_code];

	if($search_opt!="") array_push($params, "search_opt={$search_opt}");
	if($search_key!="") array_push($params, "search_key={$search_key}");

	$param = implode($params, "&");

	function admin_filter($key) {
		return $key != 'SUPER';
	}

	// $apply_level = array_filter($_CODE['admin_level'], "admin_filter", ARRAY_FILTER_USE_KEY);
	// 최고관리자 그룹 생성 가능
	$apply_level = $_CODE['admin_level'];

	$Model_manage = new Model_manage();
}

if (!empty($preset_seq)) {
	$args   = @compact("preset_seq");
	$preset = $Model_manage->getAdminMenuAuthPreset($args);

	@extract($preset);
	
	$preset_menu_auth   = $menu_auth;
	$preset_scan_center = $scan_center;
	$create_emp_no      = $emp_no;
	$create_emp_name    = aes_256_dec($emp_name);
	$create_date = date_format(date_create($create_date), "Y-m-d H:i");
	$page_title  = "[{$preset_title}] {$page_title}";
	
	$args = @compact("preset_seq");
	$used_count = $Model_manage->getAdminMenuAuthPresetUsedCount($args);
	
}
?>
<script type="text/javASCript">
	$("document").ready(function() {
		if ($("#preset_seq").val() != "") {
			$(".preset_used").show();

			changeAdminLevel();
			loadPresetUsedLists();
		} else {
			$(".preset_used").hide();
		}
	});

	//메뉴 권한 설정 저장
	function frmAdminAuthPresetSubmit(exec){
		if (doubleSubmitCheck()) return;
		
		var proc_name = getProcName();

		$("#frmAdminAuthPreset #proc_name").val(proc_name);
		$("#frmAdminAuthPreset #proc_exec").val(exec);

		$.post(
			SITE_NAME + '/manage/admin_auth_reg_process.php',
			$('#frmAdminAuthPreset').serialize(),
			function (data) {
				doubleSubmitFlag = false;
				alert(data.msg);

				if (data.status) {
					if (exec == 'CREATE') {
						location.href = "admin_auth_reg.php?enc=" + ParamEnCoding('preset_seq=' + data.result);
					} else {
						location.reload();
					}
				};
			},
			'json'
		);
	}
	
	//메뉴 권한 설정 삭제
	function frmAdminAuthPresetDelete(exec){
		if (doubleSubmitCheck()) return;

		let proc_name  = getProcName();
		let preset_seq = $("#frmAdminAuthPreset #preset_seq").val();
		let is_delete  = confirm(qdeleteconfirm[lang_code])

		if (is_delete) {
			$.post(
				SITE_NAME + '/manage/admin_auth_reg_process.php',
				{ 
					"proc_exec": exec,
					"proc_name": proc_name,
					"preset_seq": preset_seq
				},
				function (data) {
					doubleSubmitFlag = false;
					alert(data.msg);

					if (data.status) {
						location.replace(SITE_NAME + '/manage/admin_auth_list.php');
					};
				},
				'json'
			);
		} else {
			doubleSubmitFlag = false;
		}
	}

	//메뉴 권한 설정 삭제 불가 안내
	function cannotDeleteUsedPreset() {
		alert(pageauthcannotdelete[lang_code]);
		return false;
	}

	function changeAdminLevel() {
		let admin_level = $("#frmAdminAuthPreset #admin_level").val();

		if (admin_level == "SUPER") {
			$('.admin_super').show();
			$('.admin_other').hide();
			
			$(".admin_other input").prop('disabled', true);
			$("input[name='mng_scan_center[]'").prop('disabled', true);
		} else {
			$('.admin_super').hide();
			$('.admin_other').show();
			$(".admin_other input").prop('disabled', false);
			$("input[name='mng_scan_center[]'").prop('disabled', false);

			if (admin_level == "MANAGER") {
				$("input.mcode_M1000").prop('checked', false);
				$("input.mcode_M1000").prop('disabled', true);
			}
		}
	}

	function loadPresetUsedLists() {
		let preset_seq = $("#preset_seq").val();

		$.post(
			SITE_NAME + '/manage/get_admin_auth_reg_emp.php',
			{ "preset_seq": preset_seq },
			function(data) {
				$('#preset_used_list').html(data);
				controllPageExecAuth();
			},
			'text'
		);
	}
</script>
<style>
	.page_auth_list td { padding: 5px 5px 8px 20px !important;}
	.page_auth_list input[type=checkbox] { width:14px !important;height:14px !important;margin-top:3px !important;margin-right:3px !important; }
	.page_auth_list label { height:18px !important;line-height:18px !important; }
</style>
<div id="oper_input">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?php echo $page_title; ?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right">
			<span style='cursor:pointer' onclick="location.href='./admin_auth_list.php'">
				<?= $_LANG_TEXT["btngobeforepage"][$lang_code]; ?>
			</span>
		</div>

		<!--등록폼-->
		<form name="frmAdminAuthPreset" id="frmAdminAuthPreset" method="post">
			<input type='hidden' name='proc_exec' id='proc_exec'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="preset_seq" id="preset_seq" value="<?php echo $preset_seq; ?>">
			<div class="tit" style="width:160px">
				<?php echo $_LANG_TEXT["menupresetinfo"][$lang_code]; ?>
			</div>
			<table class="view" style="table-layout:fixed;">
				<col width="150">
				<col>
				<col width="150">
				<col>
				<tr>
					<th><span style='color:red'>*</span> <?php echo $_LANG_TEXT["groupnametext"][$lang_code]; ?></th>
					<td colspan="3"><input type="text" name="preset_title" id="preset_title" class="frm_input" value="<?php echo $preset_title; ?>" style="width:90%" maxlength="120"></td>
				</tr>
				<tr class="bg">
					<th ><span style='color:red'></span> <?php echo $_LANG_TEXT["menuauthapplylevel"][$lang_code]; ?></th>
					<td >
						<select name='admin_level' id='admin_level' style="width:150px" onchange="changeAdminLevel()">
							<option value=""><?php echo $_LANG_TEXT["alltext"][$lang_code]; ?></option>
							<option disabled>─────────</option>
							<?
							foreach ($apply_level as $value => $name) {
								if ($value == "SUPER") {
									if ((!empty($preset_seq)) && ($admin_level != "SUPER")) {
										continue;
									}
								}

								$selected = ($value == $admin_level) ? "selected=true" : "";
								echo "<option value='{$value}' $selected>{$name}</option>\n";
							}
							?>
						</select>
					</td>
					<th class="line"><?php echo $_LANG_TEXT["useyesnonntext"][$lang_code]; ?></th>
					<td>
						<select name="use_yn" id="use_yn" style="width:100px">
							<option value="Y" <?php if($use_yn=="Y") echo "selected"; ?>><?php echo $_LANG_TEXT["useyestext"][$lang_code]; ?></option>
							<option value="N" <?php if($use_yn=="N") echo "selected"; ?>><?php echo $_LANG_TEXT["usenotext"][$lang_code]; ?></option>
						</select>
					</td>
				</tr>
				<?php if (!empty($preset_seq)) { ?>
				<tr>
					<th style='width:150px'><span style='color:red'></span> <?php echo $_LANG_TEXT["registertext"][$lang_code]; ?></th>
					<td><?php echo $create_emp_name; ?><?php echo (!empty($create_emp_no)) ? "({$create_emp_no})":""; ?></td>
					<th style='width:150px' class="line"><?php echo $_LANG_TEXT["registdatetext"][$lang_code]; ?></th>
					<td><?php echo $create_date; ?></td>
				</tr>
				<?php } ?>
			</table>
			<div class="btn_wrap">
				<div class="right">
					<a href="./admin_auth_list.php" class="btn" id="btnList"><?php echo $_LANG_TEXT["btnlist"][$lang_code]; ?></a>
					<?php if (empty($preset_seq)) { ?>
					<a href="javascript:" onclick="frmAdminAuthPresetSubmit('CREATE')" class="btn required-create-auth hide"><?php echo $_LANG_TEXT["btnregist"][$lang_code]; ?></a>
					<?php } else { ?>
					<a href="javascript:" onclick="frmAdminAuthPresetSubmit('UPDATE')" class="btn required-update-auth hide"><?php echo $_LANG_TEXT["btnsave"][$lang_code]; ?></a>
					<a href="javascript:" onclick="<?php if ($used_count > 0) { ?>cannotDeleteUsedPreset()<?php } else { ?>frmAdminAuthPresetDelete('DELETE')<?php } ?>" class="btn required-delete-auth hide"><?php echo $_LANG_TEXT["btndelete"][$lang_code]; ?></a>
					<?php } ?>
					<a href="./admin_auth_reg.php" class="btn required-update-auth hide" id='btnClear'><?php echo $_LANG_TEXT["btnclear"][$lang_code]; ?></a>
				</div>
			</div>


			<div class="tit" style="margin-top:30px;width:160px"><?php echo $_LANG_TEXT["authscancenter"][$lang_code]; ?></div>
			<table class="view">
				<tr>
					<th style='width:150px;'><?=$_LANG_TEXT["managescancentertext"][$lang_code];?></th>
					<td colspan='3'>
						<div id='admin_mng_scan_center'>
							<div class="radio admin_super" style="display:none;">
								<div class='checkbox'><input type='checkbox' id='mng_scan_center_SUPER_ALL' value='' checked="true" disabled><label for='mng_scan_center_SUPER_ALL'>전체</label></div>
							</div>
							<div class="radio admin_other" style="display:inline">
							<?php
							$org_result = $Model_manage->getOrganList();
							$org_count = sqlsrv_num_rows($org_result);

							$args = array("use_yn"=>"Y");
							$centers = $Model_manage->getCenterList($args);
							
							if ($centers) {
								while($row=sqlsrv_fetch_array($centers, SQLSRV_FETCH_ASSOC)){
									$scan_center_code = $row['scan_center_code'];

									if ($org_count > 1) {
										$scan_center_name = $row['org_name']." ".$row['scan_center_name'];
									} else {
										$scan_center_name = $row['scan_center_name'];
									}

									if (is_array($preset_scan_center)) {
										$checked = in_array($scan_center_code, $preset_scan_center)? "checked=true" : "";
									} else {
										$checked = "";
									}
								
									//echo "<input type='hidden' name='{$scan_center_code}' value='{$scan_center_code}' />";
									echo "<div class='checkbox'><input type='checkbox' name='mng_scan_center[]' id='mng_scan_center_{$scan_center_code}' value='{$scan_center_code}' {$checked}><label for='mng_scan_center_{$scan_center_code}'>{$scan_center_name}</label></div>";
								}
							}
							?>
							</div>
						</div>
					</td>
				</tr>
			</table>


			<div class="tit" style="margin-top:30px;width:160px"><?php echo $_LANG_TEXT["setpageaccesstext"][$lang_code]; ?></div>
			<table class="view">
				<?
				$idx = 0;
				$all = array("all"=>array("","","전체"));
				foreach($_PAGE as $cate => $menu) {
					if ($cate == "MAIN") {
						continue;
					}
					$menu_code = $menu['MENU_CODE'];
					$menu_name = $menu['MENU_NAME'];
					$pagelist  = $menu['PAGE'];
				?>
				<tr class="<?php echo ($idx % 2) ? "bg":""; ?>" style="">
					<th style='width:150px; border-bottom: 1px solid #737296;'><? echo $menu_name?></th>
					<td style="padding: 0; border-bottom: 1px solid #737296;">
						<table class='in-view page_auth_list'>
							<?
							$pagelist = array_merge($all, $pagelist);

							foreach($pagelist as $page_code => $pinfo){
								$page_name = $pinfo[2];
								$menu_page_code = $menu_code."_".$page_code;
								$str_emp_exec_auth = $preset_menu_auth[$menu_code][$page_code];
								
								$emp_exec_auth = array();
								$emp_page_auth_checked = "";

								if($str_emp_exec_auth != ""){
									$emp_page_auth_checked = "checked";
									$emp_exec_auth = explode(",",$str_emp_exec_auth);
								}

								if ($page_code == "all") {
							?>
							<tr class="admin_super" style="display: none;">
								<td style="width:200px;text-align:left;">
									<input type='hidden' name='super_page_auth_<? echo $menu_code?>[]' value='<? echo $page_code?>' >
									<input type='hidden' name='super_exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='R'  >
									<input type='hidden' name='super_exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='C'  >
									<input type='hidden' name='super_exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='U'  >
									<input type='hidden' name='super_exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='D'  >
									<input type='hidden' name='super_exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='P'  >
									<input type='checkbox' checked="checked" disabled="disabled">
									<label><? echo $page_name ?></label>
								</td>
								<td style="width:50px; padding: 0 0 0 15px;">
									<input type='checkbox' checked="checked" disabled="disabled">
									<label><? echo trsLang('보기','btnview')?></label> 
								</td>
								<td style="width:50px; ">
									<input type='checkbox' checked="checked" disabled="disabled">
									<label><? echo trsLang('생성','createtext')?></label> 
								</td>
								<td style="width:50px; ">
									<input type='checkbox' checked="checked" disabled="disabled">
									<label><? echo trsLang('수정','btnupdate')?></label> 
								</td>
								<td style="width:50px; ">
									<input type='checkbox' checked="checked" disabled="disabled">
									<label><? echo trsLang('삭제','deletedeletetext')?></label>
								</td>
								<td style="">
									<input type='checkbox' checked="checked" disabled="disabled">
									<label><? echo trsLang('다운로드/인쇄','downloadandprinttext')?></label> 
								</td>
							</tr>
							<?php 
								}
							?>
							<tr class="admin_other">
								<td style="width:200px;text-align:left;">
									<input type='checkbox' name='page_auth_<? echo $menu_code?>[]' id='page_auth_<? echo $menu_page_code?>' value='<? echo $page_code?>' onclick='setPageExecAuthAll()'  class='mcode_<? echo $menu_code?>' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? echo $emp_page_auth_checked;?> <? echo $emp_page_auth_disabled;?>>
									<label for='page_auth_<? echo $menu_page_code?>' style="font-weight: bold;"><? echo $page_name ?></label>
								</td>
								<td style="width:50px; padding: 0 0 0 15px;">
									<input type='hidden' name='exec_auth_<? echo $menu_page_code?>[]' id='exec_auth_read_<? echo $menu_page_code?>' value='R'>
									<label for='fake_auth_read_<? echo $menu_page_code?>'><? echo trsLang('보기','btnview')?></label> 
								</td>
								<td style="width:50px; ">
									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' id='exec_auth_create_<? echo $menu_page_code?>' value='C'  data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("C",$emp_exec_auth)) echo "checked";?> <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_create_<? echo $menu_page_code?>'><? echo trsLang('생성','createtext')?></label> 
								</td>
								<td style="width:50px; ">
									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' id='exec_auth_update_<? echo $menu_page_code?>' value='U' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("U",$emp_exec_auth)) echo "checked";?> <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_update_<? echo $menu_page_code?>'><? echo trsLang('수정','btnupdate')?></label> 
								</td>
								<td style="width:50px; ">
									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]' class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' id='exec_auth_delete_<? echo $menu_page_code?>' value='D' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("D",$emp_exec_auth)) echo "checked";?>  <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_delete_<? echo $menu_page_code?>'><? echo trsLang('삭제','deletedeletetext')?></label>
								</td>
								<td style="">
									<input type='checkbox' name='exec_auth_<? echo $menu_page_code?>[]'  class='mcode_<? echo $menu_code?> crud_<? echo $menu_page_code?>' id='exec_auth_download_<? echo $menu_page_code?>' value='P' data-menu-code='<? echo $menu_code?>' data-page-code='<? echo $page_code?>' <? if(in_array("P",$emp_exec_auth)) echo "checked";?>  <? echo $emp_page_auth_disabled;?> onclick="setPageExecAuth()">
									<label for='exec_auth_download_<? echo $menu_page_code?>'><? echo trsLang('다운로드/인쇄','downloadandprinttext')?></label> 
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

			<div class="btn_wrap">
				<div class="right">
					<a href="./admin_auth_list.php" class="btn" id="btnList"><?php echo $_LANG_TEXT["btnlist"][$lang_code]; ?></a>
					<?php if (empty($preset_seq)) { ?>
					<a href="javascript:" onclick="frmAdminAuthPresetSubmit('CREATE')" class="btn required-create-auth hide"><?php echo $_LANG_TEXT["btnregist"][$lang_code]; ?></a>
					<?php } else { ?>
					<a href="javascript:" onclick="frmAdminAuthPresetSubmit('UPDATE')" class="btn required-update-auth hide"><?php echo $_LANG_TEXT["btnsave"][$lang_code]; ?></a>
					<a href="javascript:" onclick="<?php if ($used_count > 0) { ?>cannotDeleteUsedPreset()<?php } else { ?>frmAdminAuthPresetDelete('DELETE')<?php } ?>" class="btn required-delete-auth hide"><?php echo $_LANG_TEXT["btndelete"][$lang_code]; ?></a>
					<?php } ?>
					<a href="./admin_auth_reg.php" class="btn required-update-auth hide" id='btnClear'><?php echo $_LANG_TEXT["btnclear"][$lang_code]; ?></a>
				</div>
			</div>
			
			<div class="preset_used">
				<div class="tit" style="margin-top:30px;width:160px"><?php echo $_LANG_TEXT["pageaccessauthusedlist"][$lang_code]; ?> (<?php echo $used_count; ?>)</div>
				<div id='preset_used_list' style="margin-top: -1px;">
					<table class="view">
						<tr><td class='text-center bg' >Loading..</td></tr>
					</table>
				</div>
			</div>
		</form>
	</div>
</div>

<?php
	include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>