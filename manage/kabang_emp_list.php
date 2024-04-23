<?php
header("Pragma: no-cache");
header("Cache-Control: no-cache,must-revalidate");

{
	$page_name = "kabang_emp_list";
	$_server_path = $_SERVER['DOCUMENT_ROOT'];
	$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
	$_apos = stripos($_REQUEST_URI,  "/");
	if($_apos > 0) {
		$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
	}
	$_site_path = $_REQUEST_URI;

	include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
	include_once $_server_path . "/" . $_site_path . "/inc/header.inc";
	include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
}

// 페이지 전송 데이터 처리
{
	$orderby = $_REQUEST["orderby"];	// 정렬순서
	$page    = $_REQUEST["page"];		// 페이지
	$dept_name_path = $_REQUEST["dept_name_path"];	// 검색어
	$fake_name_path = $_REQUEST["fake_name_path"];	// 검색어

	if (empty($paging)) $paging = $_paging;

	$param = "";

	$search_detail_visible = false;

	$search_admin_level = $_REQUEST["search_admin_level"];
	$search_auth_preset = $_REQUEST["search_auth_preset"];
	
	if (!empty($search_admin_level)) $param .= (empty($param) ? "":"&") . "search_admin_level={$search_admin_level}";
	if (!empty($search_auth_preset)) $param .= (empty($param) ? "":"&") . "search_auth_preset={$search_auth_preset}";
	if($dept_name_path!="") $param .= ($param==""? "":"&")."dept_name_path=".$dept_name_path;
	if($fake_name_path!="") $param .= ($param==""? "":"&")."fake_name_path=".$fake_name_path;

	for ($i = 0; $i <= 3; $i++) {
		$idx = ($i == 0) ? "" : $i;

		$nameandor    = "searchandor{$idx}";
		$nameopt      = "searchopt{$idx}";
		$namekey      = "searchkey{$idx}";
		
		${$nameandor} = $_REQUEST[$nameandor];
		${$nameopt}   = $_REQUEST[$nameopt];
		${$namekey}   = $_REQUEST[$namekey];

		if (empty($searchopt)) {
			$searchopt = 'EMP_NAME';
		}
		
		if (!empty(${$nameandor})) $param .= (empty($param) ? "":"&")."{$nameandor}=".${$nameandor};
		if (!empty(${$nameopt}))   $param .= (empty($param) ? "":"&")."{$nameopt}=".${$nameopt};
		if (!empty(${$namekey}))   $param .= (empty($param) ? "":"&")."{$namekey}=".${$namekey};

		if (($i > 0) && !$search_detail_visible && (${$nameopt} && ${$namekey})){
			$search_detail_visible = true;
		}
	}

	//검색 로그 기록
	{
		$proc_name = $_POST['proc_name'];
	
		if ($proc_name != "") {
			$work_log_seq = WriteAdminActLog($proc_name, 'SEARCH');
		}
	}
}

// 매니저 모델 생성
{
	$Model_manage = new Model_manage;
				
	$args = array("use_yn"=>"Y");
	$presets = $Model_manage->getAdminAuthPresetLists($args);
}
?>
<script type="text/javASCript">
	var _menuAuth = {
<?php
	foreach($_CODE['admin_menu_auth'] as $key => $value){
		echo $key.":'".$value."',";
	}
?> };

	$(document).ready(function(){
		changeAdminAuthPresetType();
	});
</script>
<div id="oper_list">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["staffinfo"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
			<input type="hidden" name="page" value="">	
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<colgroup>
					<col width="100">
					<col width="230">
					<col width="100">
					<col>
				</colgroup>
				<tr>
					<th><?= $_LANG_TEXT['adminleveltext'][$lang_code] ?> </th>
					<td style="padding: 5px 13px;">
						<select name='search_admin_level' id='search_admin_level' style="min-width:150px" onchange="changeAdminLevel()">
							<option value=""><?php echo $_LANG_TEXT["alltext"][$lang_code]; ?></option>
							<option disabled>─────────</option>
							<?
							foreach ($_CODE['admin_level'] as $value => $name) {
								$selected = ($value == $search_admin_level) ? "selected=true" : "";
								echo "<option value='{$value}' $selected>{$name}</option>\n";
							}
							?>
						</select>
					</td>
					<th><?= $_LANG_TEXT['menupresetinfo'][$lang_code] ?> </th>
					<td style="padding: 5px 13px;">
						<select name="search_auth_preset" id="search_auth_preset"  style='margin-top: 1px; height: 31px; min-width:300px'>
							<option value=""><?php echo $_LANG_TEXT["alltext"][$lang_code]; ?></option>
							<option disabled>──────────────────────</option>
							<?php 
							foreach ($presets as $idx => $preset) {
								@extract($preset);
								$selected = ($search_auth_preset==$preset_seq) ? "selected=\"selected\"" : "";
								echo "<option value=\"{$preset_seq}\" {$selected}>{$preset_title}</option>";
							}
							?>
							<option value="CUSTOMIZE" <?php if($searchkey4 == "CUSTOMIZE") { echo ' selected="selected"'; } ?>><?php echo $_LANG_TEXT["pageauthbycustomize"][$lang_code]; ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<th><?= $_LANG_TEXT['finddepartment'][$lang_code] ?> </th>
					<td colspan="3" style="padding: 5px 13px;">
						<input type="hidden" name="dept_name_path" id="dept_name_path" value="<?=$dept_name_path?>">
						<input type="text" name="fake_name_path" id="fake_name_path" value="<?=$fake_name_path?>" class="frm_input" style="width:calc(50% + 5px)" readonly onclick="searchKabankDepartment()" >
						<button class="btn" onclick="resetDeptNapePath(); return false;" style="margin-top: 0; margin-left:0;"><?php echo $_LANG_TEXT["departmentstepreset"][$lang_code];?></button>
					</td>
				</tr>
				<?
				//검색키워드목록
				$searchopt_list = array( 
					"NULL"=>$_LANG_TEXT["select_search_item"][$lang_code]
					,"EMP_NAME"=>$_LANG_TEXT["empnametext"][$lang_code]
					,"EMP_ID"=>$_LANG_TEXT["empnotext"][$lang_code]
					,"DEPT"=>$_LANG_TEXT["deptnametext"][$lang_code]
				);
				?>
				<tr>
					<th><?=$_LANG_TEXT["usersearchtext"][$lang_code];?></th>
					<td colspan="3" style="padding: 5px 13px;">
						<select name="searchopt" class="select_bg" id="searchopt" style="min-width: 150px; margin-top: 1px; height: 31px;">
							<?
							foreach($searchopt_list as $key=>$name){
								$val = ($key == "NULL") ? "" : $key;
								$selected = ($searchopt==$val) ? "selected='selected'" : "";
								echo "<option value='{$val}' {$selected}>{$name}</option>";
							}
							?>
						</select>
						<input type="text" name="searchkey" id="searchkey"  value="<?=$searchkey?>" class="frm_input" style="width:calc(50% - 150px)"   maxlength="50">
						<input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" onclick="javascript:SearchSubmit(document.searchForm);">
						<input type="button" value="<?=$_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>"class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
						<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/manage/kabang_emp_list.php'">
						<!--상세검색-->
						<div id='search_detail' style='<? if($search_detail_visible==false) echo "display:none";?>'>
							<? for($i = 1 ; $i < 4 ; $i++){?>
							<div  style='margin-top:5px;'>
								<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>" style="margin-top: 1px; height: 31px;">
									<option value='AND' <? if(${"searchandor".$i}=="AND") echo "selected";?>>AND</option>
									<option value='OR' <? if(${"searchandor".$i}=="OR") echo "selected";?>>OR</option>
								</select>
								<select name="searchopt<? echo $i?>" id="searchopt<? echo $i?>"  style='width: 150px; margin-top: 1px; height: 31px; max-width:150px;'>
									<?
									foreach($searchopt_list as $key=>$name){
										$val = ($key == "NULL") ? "" : $key;
										$selected = (${"searchopt".$i}==$val) ? "selected='selected'" : "";
										echo "<option value='{$val}' {$selected}>{$name}</option>";
									}
									?>
								</select>
								<input style="width:calc(50% - 212px)" type="text" class="frm_input" name="searchkey<? echo $i?>" id="searchkey<? echo $i?>" maxlength="50" value='<? echo ${'searchkey'.$i}?>'>
							</div>
							<?}?>
						</div>
					</td>
				</tr>
			</table>
		</form>
		
		<div class="btn_confirm right">
			<a href="<? echo $_www_server?>/manage/kabang_emp_reg.php"  class="btn2 required-create-auth hide"><?=trsLang('vcs계정생성','accountregisttext');?></a>
			<a href="javascript:void(0)" onclick="setAuthKabangEmp()" class="btn2 required-update-auth hide"><?=trsLang('접근권한설정','accessauthsettingtext');?></a>
			<a href="javascript:void(0)"  id='btn_sync' class="btn2 bg-green required-update-auth hide" onclick="execKabangEmpSync()">인사 DB <?=trsLang('동기화','synchronization');?></a>
		</div>
			
		<!--검색결과리스트-->
		<div id='wrapper1' class="wrapper">
			<div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
			<table id='tblList' class="list" style="margin-top:10px;margin:0px auto; white-space: nowrap;">
				<tr>
					<th style="width:50px;min-width:50px"><input type='checkbox'  onclick="$('.clsid_cbx_emp').prop('checked',this.checked)"></th>
					<th class="num" style='width:90px;min-width:90px'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
					<th style='min-width:100px'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
					<th style='min-width:150px'><?=$_LANG_TEXT["empnotext"][$lang_code];?></th>
					<th style='width:120px;min-width:120px'><?=$_LANG_TEXT["adminleveltext"][$lang_code];?></th>
					<th style='min-width:200px'><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
					<th style='min-width:200px'><?=$_LANG_TEXT["menupresetinfo"][$lang_code];?></th>
					<th style='width:100px;min-width:100px'><?=$_LANG_TEXT["useyntext"][$lang_code];?></th>
				</tr>
				<?php
			
				$search_sql = "";

				// 사용자 등급 검색
				if (!empty($search_admin_level)) {
					$search_admin = " AND m.admin_level = '{?}'";

					$search_sql .= str_replace('{?}', $search_admin_level, $search_admin);
				}

				// 사용자 권한 검색
				if (!empty($search_auth_preset)) {
					if ($search_auth_preset == "CUSTOMIZE") {
						$search_auth = " AND a.auth_type = '{?}'";
					} else {
						$search_auth = " AND a.preset_seq = '{?}'";
					}

					$search_sql .= str_replace('{?}', $search_auth_preset, $search_auth);
				}

				// 사용자 권한 검색
				if (!empty($dept_name_path)) {
					$search_sql .= " AND ( k.dept_name_path LIKE N'{$dept_name_path}%' OR  k.dept_name LIKE N'{$dept_name_path}%' )";
				}

				
				{	//키워드검색
					$searchkey_query = "";
					$searchkey_sql = array(
						"EMP_NAME" => " k.emp_name = '{?}' "
						, "EMP_NAME_DEC" => " dbo.fn_DecryptString(k.emp_name) = '{?}' "
						, "EMP_ID" => " k.emp_id  like '%{?}%' "
						, "DEPT" => " k.dept_name like '%{?}%' "
					);

					for($i = 0 ; $i <= 3 ;$i++){
						$idx = ($i == 0) ? "" : $i;

						$_search_opt   = ${"searchopt".$idx};	
						$_search_key   = ${"searchkey".$idx};	
						$_search_andor = ${"searchandor".$idx};
						
						if (!empty($_search_opt) && !empty($_search_key)) {
							if ($_search_opt=="EMP_NAME") {
								if ($_encryption_kind=="1") {
									$_search_opt = "EMP_NAME_DEC";
								} else {
									$_search_key = aes_256_enc($_search_key);
								}
							}

							if (empty($searchkey_query)) {
								$_search_andor = "";
							}

							$_searchsql = $searchkey_sql[$_search_opt];
							$searchkey_query .= " {$_search_andor} " . str_replace('{?}', $_search_key, $_searchsql);
						}
					}

					if ($searchkey_query != ""){
						$search_sql .= " AND ( {$searchkey_query} )";
					}
				}
				
				$Model_manage->SHOW_DEBUG_SQL = false;
				$args = array("search_sql"=>$search_sql);
				$total = $Model_manage->getKabangEmpListCount($args);

				$rows = $paging;			// 페이지당 출력갯수
				$lists = $_list;			// 목록수
				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no = $total-$start;
				$end = $start + $rows;
		
				if($orderby != "") {
					$order_sql = " ORDER BY $orderby ";
				} else {
					$order_sql = " ORDER BY k.dept_name, k.emp_name ";
				}

				//echo $sql;
				
				$args = array("end"=>$end,"order_sql"=>$order_sql,"search_sql"=>$search_sql,"start"=>$start);
				$result =$Model_manage->getKabangEmpList($args);
				
				if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
			
				if($result){
					while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
						$rnum = $row['rnum'];
						$emp_seq = $row['emp_seq'];
						$emp_id = $row['emp_id'];
						$emp_name = aes_256_dec($row['emp_name']);
						$dept_name = $row['dept_name'];
						$dept_name_path = str_replace(';', ' > ', $row['dept_name_path']);
						$admin_level = $row['admin_level'];
						$work_yn = $row['work_yn'];
						$auth_type = $row['auth_type'];
						$preset_seq = $row['preset_seq'];
						$auth_group = ($auth_type == "CUSTOMIZE") ? $_LANG_TEXT["pageauthbycustomize"][$lang_code] : $row['preset_title'];
						$str_work_yn = $work_yn=="Y" ? trsLang('사용함','useyestext') : trsLang('사용안함','usenotext');
						$param_enc = ParamEnCoding("emp_seq=".$emp_seq.($param? "&" : "").$param);
				?>	
					<tr onclick="sendPostForm('<? echo $_www_server?>/manage/kabang_emp_info.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
						<td onclick="event.stopPropagation();";><input type='checkbox' class='clsid_cbx_emp' name='emp_seq[]' data-name="<?php echo $emp_name; ?>" data-dept="<?php echo $dept_name; ?>" data-admin-level="<?php echo $admin_level; ?>" data-admin-level-text="<?php echo $_CODE["admin_level"][$admin_level]; ?>" data-auth-type="<?php echo $auth_type; ?>" data-preset-seq="<?php echo $preset_seq; ?>" data-auth-name="<?php echo $auth_group; ?>" data-enc="<?=$param_enc?>" value='<? echo $emp_seq?>'></td>
						<td><?php echo $no; ?></td>
						<td><?=$emp_name?></td>
						<td><?=$emp_id?></td>
						<td><?=$_CODE["admin_level"][$admin_level]?></td>
						<td style="text-align:left; padding-left: 10px;"><?=$dept_name?></td>
						<td><?=$auth_group?></td>
						<td><?=$str_work_yn?></td>
					</tr>
					<?php
						$no--;
					}
					
				}
			
				if($total < 1) {
					
				?>
					<tr>
						<td colspan="8"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
					</tr>
				<?php
				}
				?>		
			</table>
		</div>

		<!--페이징-->
<?php
	
		if($total > 0) {
			$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
			print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
		}
?>

	</div>

</div>
<!--계정등록 Modal-->
<div id="modal_account" class="modal" >
  <div class="modal-content" style='width:600px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title" id='pop_page_title'><!--title--></strong>
			<span class="close">&times;</span> 
		</div>
		<form id="frmEmpAccount" name="frmEmpAccount"  method="POST">
			<input type='hidden' name='proc' id='proc' value='CREATE'>
			<input type='hidden' name='proc_name' >
			<input type='hidden' name='emp_seq' id='emp_seq' >
			<input type='hidden' id='enc_param' >
			<div class="form-group">
				<!--정보등록폼-->
				<table class='view' id='tbl_create_account'>
					<tr>
						<th style='text-align:left;width:100px'><label for='emp_id'><? echo trsLang('아이디','idtext');?></label></th>
						<td>
							<input style="width:60%" class="frm_input required_auth" type="text" id="emp_id" name="emp_id" maxlength="50">
							<a href="javascript:void(0)" onclick="searchKabangEmp()" id='btnEmpsearch' class='btn-white' style='width:80px'><? echo trsLang('조회','searchtext');?></a>
						</td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='emp_name'><? echo trsLang('임직원명','empnametext');?></label></th>
						<td><input style="width:90%" class="frm_input readonly required_auth" readonly type="text" id="emp_name" name="emp_name" maxlength="30"></td>
					</tr>
					<tr>
						<th style='text-align:left'><label for='dept_name'><? echo trsLang('부서','depttext');?></label></th>
						<td><input style="width:90%" class="frm_input readonly required_auth" type="dept_name" id="dept_name" name="g_doc_no" maxlength="50" readonly></td>
					</tr>
				</table>
				<!--접근권한설정 대상자 선택폼-->
				<table class='view' style='display:none' id='tbl_auth_account'>
					<tr>
						<th style='padding-left:10px;text-align:left;width:100px;line-height:35px;'><label ><? echo trsLang('임직원','staff');?></label></th>
						<td>
							<input type='hidden' name='emp_seq_list' id='emp_seq_list'>
							<span class='clsid_emp_count'><? echo trsLang('전체','alltext')?></span> 
						</td>
					</tr>
				</table>
				<!--접근권한설정 대상자가 1명일 경우-->
				<table class='view' style='display:none' id='tbl_auth_account_one'>
					<tr>
						<th style='padding-left:10px;text-align:left;width:100px;line-height:35px;'><?=$_LANG_TEXT["empnametext"][$lang_code];?></th>
						<td><span id="txt_name"></span></td>
					</tr>
					<tr>
						<th style='padding-left:10px;text-align:left;width:100px;line-height:35px;'><?=$_LANG_TEXT["depttext"][$lang_code];?></th>
						<td><span id="txt_dept"></span></td>
					</tr>
					<tr>
						<th style='padding-left:10px;text-align:left;width:100px;line-height:35px;'><?=$_LANG_TEXT["userleveltext"][$lang_code];?></th>
						<td><span id="txt_admin_level"></span></td>
					</tr>
					<tr>
						<th style='padding-left:10px;text-align:left;width:100px;line-height:35px;'><?=$_LANG_TEXT["menupresetinfo"][$lang_code];?></th>
						<td><span id="txt_auth_name"></span></td>
					</tr>
				</table>
				<!--접근권한-->
				<table class='view' >
					<tr>
						<th style='padding-left:10px;text-align:left;line-height:35px;width:100px'><?=$_LANG_TEXT["userleveltext"][$lang_code];?></th>
						<td>
							<select name='admin_level' id='admin_level' style="width:200px" onchange="SetAdminAuth();">
								<option value=''><?=$_LANG_TEXT["choosetext"][$lang_code];?></option>
								<?
								$option = $_CODE['admin_level'];
								foreach($option as $value => $name){

									echo "<option value='$value' >$name</option>";
								}
								?>
							</select>
						</td>
					</tr>
					<?
						$result = $Model_manage->getOrganList();
						$org_count = sqlsrv_num_rows($result);
					?>
					<tr class="<? if($org_count==1) echo "display-none";?>">
						<th style='padding-left:10px;text-align:left;line-height:35px;width:100px'><?=$_LANG_TEXT["manageorgantext"][$lang_code];?></th>
						<td>
							<div id='admin_mng_org'>
								<div class="radio" style="display: inline;">
								<?
								if($result){
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

										$mng_org_id = $row['org_id'];
										$mng_org_name = $row['org_name'];
										if($org_count==1) $checked = "checked";
								?>
									<div class='checkbox'><input type='checkbox'name='mng_org[]' id='mng_org_<?=$mng_org_id?>' value='<?=$mng_org_id?>'
										<? echo $checked;?>> <label for='mng_org_<?=$mng_org_id?>'><?=$mng_org_name?></label></div>
								<?php
										}
									}
								?>							
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th style='padding-left:10px;text-align:left;line-height:35px;width:100px'><?=$_LANG_TEXT["pageauthbypreset"][$lang_code];?></th>
						<td>
							<div id='admin_mng_menu' style="margin: 0;" >
								<div style="margin: 0; display: inline-block;line-height: 30px;">
									<select name="admin_auth_type" id="admin_auth_type" data-selected-auth-type="<?php echo $admin_menu_auth_type; ?>" data-selected-preset-seq="<?php echo $admin_menu_auth_preset_seq; ?>" style="min-width:250px;" onchange="changeAdminAuthPresetType()">
										<option value="" data-target-level="NONE"><?php echo $_LANG_TEXT["choosetext"][$lang_code]; ?></option>
										<option disabled data-target-level="NONE">──────────────</option>
										<?php 
										foreach ($presets as $idx => $preset) {
											@extract($preset);
											$selected = ($admin_menu_auth_preset_seq == $preset_seq) ? "selected" : "";
											echo "<option value=\"PRESET\" data-preset-seq=\"{$preset_seq}\" data-target-level=\"{$target_level}\" {$selected}>{$preset_title}</option>";
										}
										?>
										<option value="CUSTOMIZE" data-preset-seq="" data-target-level="NONE" <?php echo ($admin_menu_auth_type == "CUSTOMIZE") ? "selected":"";?>><?php echo $_LANG_TEXT["pageauthbycustomize"][$lang_code]; ?></option>
									</select>
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th style='padding-left:10px;text-align:left;line-height:35px;width:100px'><?=$_LANG_TEXT["managescancentertext"][$lang_code];?></th>
						<td>
							<div id='admin_mng_scan_center'>
								<div class="radio" style="display: inline;">
								<?
								$result = $Model_manage->getCenterList();

								if($result){
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
										$scan_center_code = $row['scan_center_code'];
										
										if($org_count > 1){
											$scan_center_name = $row['org_name']." ".$row['scan_center_name'];
										}else{
											$scan_center_name = $row['scan_center_name'];
										}

										echo "<div class='checkbox'><input type='checkbox' name='mng_scan_center[]' id='mng_scan_center_{$scan_center_code}' value='{$scan_center_code}'><label for='mng_scan_center_{$scan_center_code}'>{$scan_center_name}</label></div>";
									}
								}
								?>
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<th style='padding-left:10px;text-align:left;line-height:35px;width:100px'><?=$_LANG_TEXT["pageaccessauth"][$lang_code];?></th>
						<td>
							<div id='admin_mng_page_auth' style="display:flex;margin:5px 10px 4px 0;">
								<div class="radio">
									<?php
									$result = $Model_manage->getMenuList();
									if($result){
										while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
											$menu_code = $row['menu_code'];
											$menu_name = $_CODE['menu'][$menu_code];

											if ($admin_menu_auth_type == "CUSTOMIZE") {
												$checked = @in_array($menu_code, $emp_menu) ? "checked":"";
											}

											echo "<div class='checkbox'><input type='checkbox' disabled='disabled' class='{$checked}' name='menu[]' id='menu_{$menu_code}' value='{$menu_code}'><label for='menu_{$menu_code}'>{$menu_name}</label></div>";
										}
									}
									?>
									<input type="hidden" id="admin_auth_preset_seq" name="admin_auth_preset_seq" value="<?php echo $admin_menu_auth_preset_seq; ?>" />
								</div>
							</div>
						</td>
					</tr>

				</table>
				<div class="btn_wrap right">
					<div id="page_auth_preview" style="display:inline-flex;"><a href="javascript:void(0)"class="btn  required-update-auth hide bg-blue" id='btn_kabang_page_auth' onclick='popAdminPageAuthDetail();'><?= $_LANG_TEXT["pageauthpreview"][$lang_code]; ?></a></div>
					<div id='page_auth_detail' style="display:inline-flex;"><a href='javascript:void(0)' class="btn required-update-auth hide bg-blue" id='btnConfigAuth' onclick='popAdminPageAuth();' data-emp-seq=''><? echo $_LANG_TEXT["setpageaccesstext"][$lang_code]; ?></a></div>
					<div style="display:inline-flex;"><a href="javascript:void(0)"class="btn  required-update-auth hide " id='btnEmpListSave' ><?= $_LANG_TEXT["save_file"][$lang_code]; ?></a></div>
				</div>
		  </div>
		</form>
  </div>
</div>

<!--조직도 검색 모달-->
<div id="modal_department_search" class="modal" >
  	<div class="modal-content" style='width:800px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title"><?= $_LANG_TEXT["finddepartment"][$lang_code]; ?></strong>
			<span class="close">&times;</span> 
		</div>
		<div id='department_tree' style='padding:15px;max-height:600px;min-height:400px;overflow-y:auto'></div>
		<div class="btn_wrap ">
			<a href="javascript:void(0)" class="btn-white" style="padding:6px 20px;" onclick="openAllDetails()"><?= $_LANG_TEXT["opendepttree"][$lang_code]; ?></a>
			<a href="javascript:void(0)" class="btn-white" style="padding:6px 20px;" onclick="closeAllDetails()"><?= $_LANG_TEXT["closedepttree"][$lang_code]; ?></a>
			<a href="javascript:void(0)" class="btn-white" style="padding:6px 20px;background: #999;color:#fff;" onclick="closeModalWindow('modal_department_search')"><?= $_LANG_TEXT["canceltext"][$lang_code]; ?></a>
		</div>
	</div>
</div>
<!--페이지 접근권한 설정 모달-->
<div id="modal_admin_auth_detail" class="modal" >
  <div class="modal-content" style='width:1000px;'>
		<div class="" style="display:flex; align-items: center; justify-content:space-between; width:100%">
			<strong class="modal-title" id='pop_page_title2'><? echo trsLang('페이지접근권한설정','setpageaccesstext')?></strong>
			<span class="close">&times;</span> 
		</div>
		<div id='menu_auth_detail' style='padding:0px 15px;max-height:500px;overflow-y:auto'></div>
		<div class="btn_wrap ">
			<a href="javascript:void(0)"class="btn  required-update-auth hide" id='btnSaveDetailAuth'  onclick="frmAdminAuthDetailsSubmit()"><?= $_LANG_TEXT["save_file"][$lang_code]; ?></a>
		</div>
</div>

</div>
<?php


if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>