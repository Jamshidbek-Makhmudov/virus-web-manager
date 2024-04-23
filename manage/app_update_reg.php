<?php
$page_name = "app_update";
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

$app_seq = $_REQUEST['app_seq'];
$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$gubun = $_REQUEST[gubun];	
$file_type = $_REQUEST[file_type];

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($gubun!="") $param .= ($param==""? "":"&")."gubun=".$gubun;
if($file_type!="") $param .= ($param==""? "":"&")."file_type=".$file_type;

if(empty($app_seq)){
		
	$writer = $_ck_user_name."(".$_ck_user_id.")";
	$write_date = date("Y-m-d H:i");
	$patch_dt_div = "fix";


}else{

	if($gubun != ""){

	  $search_sql .= " and gubun ='$gubun' ";
	}

	if($file_type != ""){

	  $search_sql .= " and file_type ='$file_type' ";
	}

	if($searchkey != ""){

	  if($searchopt=="APPFILE"){

		$search_sql .= " and (app_name like '%$searchkey%' OR real_name like '%$searchkey%' ) ";

	  }else if($searchopt == "MEMO"){
		
		$search_sql .= " and memo like '%$searchkey%' ";
	  
	  }
	}

	if($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY app_seq DESC ";
	}
	
	$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql,"app_seq"=>$app_seq);
	$qry_label = QRY_APP_UPDATE_INFO;
	$sql = query($qry_label,$qry_params);

	//  echo nl2br($sql);
	
	$result =@sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
	  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		  $app_seq = $row['app_seq'];
		  $gubun = $row['gubun'];
		  $writer = aes_256_dec($row['create_emp']);
		  $write_date = $row['create_dt'];
		  $editor =  aes_256_dec($row['modify_emp']);
		  $edit_date = $row['modify_dt'];
		  $patch_dt = $row['patch_dt'];
		  $install_path = $row['install_path'];
		  $app_name = $row['app_name'];
		  $use_yn = $row['use_yn'];
		  $ver = $row['ver'];
		  $memo = $row['memo'];
		  $file_name = $row['file_name'];
		  $app_update_kiosk = $row['app_update_kiosk'];
		  $kiosk = explode(",",$app_update_kiosk);
		  $rnum = $row['rnum'];
		  if($row['file_name']){
				$file = "<a href='/".$_site_path."/common/download.php?enc=".ParamEnCoding("file=".$_SERVER['DOCUMENT_ROOT'].$row['server_path']."/".$row['file_name'])."' class='required-print-auth'>".$row['real_name']."</a>";
			}else{
				$file = "";
			}

			if(substr($patch_dt,0,10)=="1900-01-01"){	//매일 특정시간업데이트
				$patch_dt_div="every";
				$patch_time = substr($patch_dt,11,2);
			}else{
				$patch_dt_div="fix";
			}


	  }
	}

	//이전,다음
	
	$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql,"rnum"=>$rnum);
	$qry_label = QRY_APP_UPDATE_INFO_PREV;
	$sql = query($qry_label,$qry_params);


	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$prev_app_seq = $row['app_seq'];

	$qry_params = array("search_sql"=>$search_sql,"order_sql"=>$order_sql,"rnum"=>$rnum);
	$qry_label = QRY_APP_UPDATE_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$next_app_seq = $row['app_seq'];

}
?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?= $_LANG_TEXT["m_manage_appupdate"][$lang_code]; ?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>
		
		<!--등록폼-->
		<?
		$bg = '';
		if (isset ($editor)) {$bg = 'bg';} ?>
		<form name="frmApp" id="frmApp" enctype="multipart/form-data" method="post">
		<input type="hidden" name="app_seq" id="app_seq" value="<?=$app_seq?>">
		<input type="hidden" name="proc" id="proc" value="">
		<input type="hidden" name="proc_name" id="proc_name" value="">
		<table class="view">
		<tr  class=" <?= $bg?>">
			<th style='width:150px'><?=$_LANG_TEXT['registertext'][$lang_code]?></th>
			<td style='width:300px'><?=$writer?> </td>
			<th style='width:150px' class="line"><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<td><?=$write_date?></td>
		</tr>
		<?if(isset($editor)){?>
			<tr>
				<th><?=$_LANG_TEXT['updatertext'][$lang_code]?></th>
				<td><?=$editor?></td>
				<th class="line"><?=$_LANG_TEXT['updatedatetext'][$lang_code]?></th>
				<td><?=$edit_date?></td>
			</tr>
		<?}?>
		<tr class="bg">
			<th><?=$_LANG_TEXT['gubuntext'][$lang_code]?></th>
			<td >
				<select id='gubun' name='gubun'>
				<?
				$option = $_CODE['app_gubun'];
				foreach($option as $value => $name) {
					$selected = ($gubun == $value) ? "selected=true" : "";

					echo "<option value='{$value}' {$selected}>{$name}</option>";
				}
				?>
			</td>
			<th class="line"><?=$_LANG_TEXT['filetypetext'][$lang_code]?></th>
			<td>
				<select id='file_type' name='file_type' onchange="changeAppFileType('app_file')">
					<?
					$option = $_CODE['app_file_type'];
					foreach($option as $value => $name){
						$accept = "*";
						$selected = ($file_type == $value) ? "selected=true" : "";
	
						if ($value == "ZIP") {
							$accept = ".zip";
						} else if ($value == "EXE") {
							$accept = ".exe";
						} else if ($value == "IMAGE") {
							$accept = "image/*";
						}

						echo "<option value='{$value}' {$selected} data-file-accept=\"{$accept}\">{$name}</option>";
					}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['appnametext'][$lang_code]?></th>
			<td>
				<select id='app_name' name='app_name'>
					<?
					$option = $_CODE_UPDATE_APP_NAME;
					foreach($option as $value => $name){
						$file_name = "";
						$selected = ($app_name == $value) ? "selected=true" : "";

						if ($value == "V3") {
							$file_name = "ahnlabengine_setup.zip";
						} else if ($value == "ESET") {
							$file_name = "dll.zip";
						}
						echo "<option value='{$value}' {$selected} data-file-name=\"{$file_name}\">{$name}</option>";
					}
					?>
				</select>	
			</td>
			<th class="line"><?=$_LANG_TEXT['versioninfotext'][$lang_code]?></th>
			<td><input type='text' name='app_ver'id='app_ver' value='<?=$ver?>' class="frm_input" style="width:100px"   maxlength="20"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['attachfiletext'][$lang_code]?></th>
			<td colspan="3"><input name="app_file" id='app_file'  type="file" accept=".zip" onchange="checkAppUpdateFile('app_name')"> <span id='sp_old_file'><?=$file?></span></td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['updatetimetext'][$lang_code]?></th>
			<td colspan="3">
				<select id='patch_dt_div' name='patch_dt_div' onchange="setUpdatePatchDate()">
					<option value='fix' <?if($patch_dt_div=="fix") echo "selected";?>><? echo trsLang('특정일','specificday');?></option>
					<option value='every' <?if($patch_dt_div=="every") echo "selected";?>><? echo trsLang('매일','everyday');?></option>
				</select>
				<span id='set_patch_time' <?if($patch_dt_div=="fix") echo "style='display:none'";?>  >
					<select id='patch_time' name='patch_time'>
						<? for ($i =0 ; $i < 24 ; $i++) {
								$time = sprintf('%02d',$i);
								$time_selected = $patch_time ==$time ? "selected" : "";
							echo "<option value='{$time}' {$time_selected}>{$time}".trsLang('시','hourtimetext')."</option>";
						}?>
					<select>
				</span>
				<span id='set_patch_date' <?if($patch_dt_div=="every") echo "style='display:none'";?> >
					<input name="patch_dt" id='patch_dt'  type="text" class="frm_input" value='<?=$patch_dt?>' style="width:200px"   maxlength="20"> <button type='button' class="sch" onclick="$('#patch_dt').val(new Date().dateformat('yyyy-mm-dd hh:mm:ss'))"><?=$_LANG_TEXT['btncurrenttime'][$lang_code]?></button> (<?=$_LANG_TEXT['updatetimeinputguidetext'][$lang_code]?>)
				</span>
			</td>
		</tr>
		<tr class="bg">
			<th><? echo trsLang('적용키오스크','adaptkiosk');?></th>
			<td colspan="3">
				<?
					//파일반입(VCS)메뉴가 있는 키오스크정보만 불러온다.
					$args = array("search_sql"=>" and k.kiosk_menu like '%VCS%'");
					$Model_manage = new Model_manage();
					$Model_manage->SHOW_DEBUG_SQL= false;
					$result = $Model_manage->getScanCenterKiosk($args);
					if($result){
						while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

							$kiosk_id = $row['kiosk_id'];
							$kiosk_name = $row['kiosk_name'];
							
							if(is_array($kiosk)){
								$checked = in_array($kiosk_id,$kiosk) ? "checked" : "";
							}else $checked = "";
					?>
						<input type='checkbox' name='kiosk[]' id='kiosk_<?=$kiosk_id?>'
							value='<?=$kiosk_id?>'  <? echo $checked;?>> <label
							for='kiosk_<?=$kiosk_id?>'><?=$kiosk_name?></label>
						<?php
						}
					}
				?>
			</td>
		</tr>
		<tr class="bg display-none">
			<th><?=$_LANG_TEXT['installpathtext'][$lang_code]?></th>
			<td colspan="3"><input name="install_path" id='install_path' type="text"  class="frm_input" style="min-width:750px" value='<?=$install_path?>'   maxlength="150"></td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
			<td colspan="3"><input name="memo" id='memo' type="text"  size="60" value='<?=$memo?>' class="frm_input" style="min-width:750px"   maxlength="500"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<td colspan="3">
				<select id='use_yn' name='use_yn'>
					<option value='Y' <?if($use_yn=="Y"){echo "selected";}?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
					<option value='N'  <?if($use_yn=="N"){echo "selected";}?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
				</select>
			</td>
		</tr>
		
		</table>
		
		
		<div class="btn_wrap">
<?php
		if ($app_seq != "") {
?>
			<div class="left display-none">
				<a href="<?if(empty($prev_app_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("app_seq=".$prev_app_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_app_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("app_seq=".$next_app_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?php	}?>
			<div class="right">
					<a href="./app_update.php" class="btn" id="btnList"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?php
					if ($app_seq == "") {
?>
						<a href="javascript:void(0)"   onclick="AppUpdateSubmit('CREATE')" class="btn required-create-auth hide"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
<?php
					}else{
?>	
						<a href="javascript:void(0)"   onclick="AppUpdateSubmit('UPDATE')" class="btn required-update-auth hide"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
						<a href="javascript:void(0)"   onclick="AppUpdateSubmit('DELETE')" class="btn required-delete-auth hide"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
							

<?php
					}
?>
					<a href="./app_update_reg.php" class="btn"  id='btnClear'><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

		

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>