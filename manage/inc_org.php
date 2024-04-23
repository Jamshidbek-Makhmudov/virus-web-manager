<?

$org_id = substr($id,3,strlen($id));

if($org_id!=""){
	
	$qry_params = array("org_id"=>$org_id);
	$qry_label = QRY_TREE_ORGAN_INFO;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){

		while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
			
			$org_name = $row['org_name'];
			$com_id = $row['com_id'];
			$memo = $row['memo'];
			$use_yn = $row['use_yn'];

			$_com_id = $com_id;
		}  
	}
}

/*코드가져오기*/
$qry_params = array();
$qry_label = QRY_COMMON_POLICY_CODE;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);

if($result){
	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){
		$code[$row['code_key']][$row['code_name']] = $row['code_seq'];
	}  
}
?>
<div id="tree_input">
	<div class="container2">

		<input type="hidden" name='inc_com_id'  id="com_id"  value="<?=COMPANY_CODE?>">
		<input type="hidden" name='inc_sel_org' id="sel_org" org_name="<?=$org_name?>"  value="<?=$org_id?>">
		
		<!--기관정보-->
		<form name='frmOrg' id='frmOrg' method='post'>
		<input type="hidden" name="com_id" id="org_com_id"  value="<?=COMPANY_CODE?>">
		<input type="hidden" name="proc" id="proc"  value="<?=$proc?>">
		<input type="hidden" name="proc_name" id="proc_name"  >
		
		<div>
			<div class="right">
				<div id="nopolicymsg" class="policymsg"></div> 
				<div id="nodevpolicymsg" class="policymsg"></div>
			</div>
			<div class="tit">
				<?=$_LANG_TEXT['organinfotext'][$lang_code]?>
			</div>
		</div>

		<table class="view">
			<tr>
				<th style='min-width:100px;width:100px;' ><?=$_LANG_TEXT['organcodetext'][$lang_code]?></th>
				<td style='width:220px;'>
					<input type="text" name='org_id' id='org_id' class="frm_input" style="ime-mode:inactive;width:80px;<?if($org_id !="") echo "background-color:#e7e7e7";?>" value="<?=$org_id?>" <?if($org_id !="") echo "readonly";?> maxlength="20">
				</td>
				<th style='min-width:100px;width:100px;'  class="line"><?=$_LANG_TEXT['organnametext'][$lang_code]?></th>
				<td>
					<input type="text" name='org_name' id='org_name' class="frm_input" style="width:180px" value="<?=$org_name?>"  maxlength="100">
				</td>
			</tr>
			<tr class="bg">
				<th><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
				<td colspan="3">
					<input type="text" name='memo' id='memo' class="frm_input" style="min-width:500px;width:70%;" value="<?=$memo?>"  maxlength="500">
				</td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
				<td colspan="3">
					<select name="use_yn" id="use_yn">
						<option value="Y" <?if($use_yn=="Y") echo "selected";?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
						<option value="N" <?if($use_yn=="N") echo "selected";?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
					</select>
				</td>
			</tr>
		</table>
		</form>
		<div class="btn_wrap">
			<div class="right">
<?php
	if($org_id==""){
		$create_event_title = trsLang('기관정보','organinfotext')." ".$_LANG_TEXT['btnregist'][$lang_code];
?>
				<a href="javascript:void(0)" title="<? echo $create_event_title;?>" onclick="return OrgSubmit('CREATE');" class="btn"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
<?
	}else{
		$update_event_title = trsLang('기관정보','organinfotext')." ".$_LANG_TEXT['btnsave'][$lang_code];
		$delete_event_title = trsLang('기관정보','organinfotext')." ".$_LANG_TEXT['btndelete'][$lang_code];
?>
				<a href="javascript:void(0)" title="<? echo $update_event_title;?>" onclick="return OrgSubmit('UPDATE');" class="btn required-update-auth hide"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="javascript:void(0)" title="<? echo $delete_event_title;?>" onclick="return OrgSubmit('DELETE');" class="btn required-delete-auth hide"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?
	}
?>
			</div>
		</div>

	</div>
</div>