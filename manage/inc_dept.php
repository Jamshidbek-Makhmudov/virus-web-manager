<?

$dept_seq = str_replace("DEPT","",$id);

if($dept_seq != ""){

	$qry_params = array("dept_seq"=>$dept_seq);
	$qry_label = QRY_TREE_DEPT_INFO;
	$sql = query($qry_label,$qry_params);
		
	$result = sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
	  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

			$org_id = $row['org_id'];
			$dept_seq = $row['dept_seq'];
			$dept_name = $row['dept_name'];
			$p_dept_seq = $row['p_dept_seq'];
			$sort = $row['sort'];
			$dept_chief_seq = $row['chief_emp_seq'];
			$use_yn = $row['use_yn'];
			$dept_auth1 = $row['dept_auth1'];
			$dept_auth2 = $row['dept_auth2'];
			$dept_auth3 = $row['dept_auth3'];
	  }
	}
}


/*소속기관*/
$qry_params = array();
$qry_label = QRY_COMMON_ORG;
$sql = query($qry_label,$qry_params);	

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
			$arr_org[$row['org_id']] = $row['org_name'];
	}
}

/*부서*/
$qry_params = array();
$qry_label = QRY_COMMON_DEPT;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query($wvcs_dbcon, $sql);
if($result){
	while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
		
		$arr_dept[$row['dept_seq']] = array($row['org_id'],($row['lvl']==""?$row['org_name']."-":$row['lvl']).$row['dept_name'],$row['hierarchy']);
	}
}

?>
<div id="tree_input">
	<div class="container2">
		
		<form name="formDept" id="formDept" method="post">
		<input type="hidden" name="dept_seq" id="dept_seq" value="<?=$dept_seq?>">
		<input type="hidden" name="proc" id="proc">
		<input type="hidden" name="proc_name" id="proc_name"  >
		<input type="hidden" name="src" id="src" value="tree_list">
		<table class="view">
		<tr>
			<th style='min-width:100px;width:100px;'><?=$_LANG_TEXT['belongorgantext'][$lang_code]?></th>
			<td style='width:220px;'>
				<select name="sel_org" id="sel_org" onchange="MngDeptSubOrgSet()">
					<option value=""><?=$_LANG_TEXT['organselecttext'][$lang_code]?></option>
				
				<?php
					foreach($arr_org as $key => $name){
						$tmp_org_id = $key;
						$tmp_org_name = $name;
						echo "<option value='$tmp_org_id' ".($tmp_org_id==$org_id? "selected='selected'" : "").">$tmp_org_name</option>";
					}
				?>
				</select>
			</td>
			<th style='min-width:100px;width:100px;' class="line"><?=$_LANG_TEXT['superdepttext'][$lang_code]?></th>
			<td>
				<select name="p_dept_seq" id="p_dept_seq">
					<option value="0" org=''  hierarchy=''><?=$_LANG_TEXT['superdeptselecttext'][$lang_code]?></option>
		
					<?php
						foreach($arr_dept as $key => $value){

								$tmp_dept_seq = $key;
								list($tmp_org_id,$tmp_dept_name,$hierarchy) = $value;

								echo "<option value='$tmp_dept_seq' org='$tmp_org_id' hierarchy ='$hierarchy' ".($tmp_dept_seq==$p_dept_seq? "selected='selected'" : "").">$tmp_dept_name</option>";
						}
					?>
				</select>
			</td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['deptnametext'][$lang_code]?></th>
			<td><input type="text" name="dept_name" id="dept_name" value="<?=$dept_name?>"  class="frm_input" style="width:90%"  maxlength="50"></td>
			<th class="line"><?=$_LANG_TEXT['deptchieftext'][$lang_code]?></th>
			<td>
				<select name="dept_chief" id="dept_chief">
					<option value="" org=""><?=$_LANG_TEXT['deptchiefselecttext'][$lang_code]?></option>
			
					<?php
						$sqlb = "SELECT org_id,emp_seq,emp_name FROM tb_employee WHERE ".getCheckOrgAuthQuery('org_id')." AND work_yn='Y' ";
						
						$result = sqlsrv_query($wvcs_dbcon, $sqlb);
						
						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
									$tmp_org_id = $row['org_id'];
									$tmp_emp_seq = $row["emp_seq"];
									$tmp_emp_name = aes_256_dec($row["emp_name"]);

									echo "<option value='$tmp_emp_seq'  org='$tmp_org_id' ".($tmp_emp_seq==$dept_chief_seq? "selected='selected'" : "").">$tmp_emp_name</option>";
							}
						}
					?>
				</select>
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<td>
				<select name="use_yn" id="use_yn">
					<option value="Y" <?if($use_yn=="Y") echo "selected='selected'"?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
					<option value="N" <?if($use_yn=="N") echo "selected='selected'"?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
				</select>
			</td>
			<th class="line"><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
			<td>
				<input type="text" name="sort" id="sort" class="frm_input" onkeyup="onlyNumber(this)" size="3" style="width:20%" value="<?=$sort?>"  maxlength="10">
			</td>
		</tr>
		<tr class="bg display-none">
			<th><?=$_LANG_TEXT['deptauthtext'][$lang_code]?></th>
			<td colspan="3">
				<input type="text" name="dept_auth1" id="dept_auth1" class="frm_input" style="width:10%" value="<?=$dept_auth1?>"  maxlength="25">
				<input type="text" name="dept_auth2" id="dept_auth2" class="frm_input" style="width:10%" value="<?=$dept_auth2?>"  maxlength="25">
				<input type="text" name="dept_auth3" id="dept_auth3" class="frm_input" style="width:10%" value="<?=$dept_auth3?>"  maxlength="25">
			</td>
		</tr>
		</table>
		</form>
		
		<div class="btn_wrap">
			<div class="right">
<?if($dept_seq ==""){
	$create_event_title = trsLang('부서','depttext')." ".$_LANG_TEXT['btnregist'][$lang_code];
?>
				<a href="javascript:void(0)" class="btn required-create-auth hide" id='btnDeptReg' title='<? echo $create_event_title;?>' onclick="DepartmentSubmit('CREATE')"><?=$_LANG_TEXT['btnregist'][$lang_code]?></a>
<?}else{
	$update_event_title = trsLang('부서','depttext')." ".$_LANG_TEXT['btnsave'][$lang_code];
	$delete_event_title = trsLang('부서','depttext')." ".$_LANG_TEXT['btndelete'][$lang_code];
?>
				<a href="javascript:void(0)" class="btn  required-update-auth hide" id='btnDeptEdit' title='<? echo $update_event_title;?>'  onclick="DepartmentSubmit('UPDATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="javascript:void(0)" class="btn  required-delete-auth hide" id='btnDeptDel' title='<? echo $delete_event_title;?>'  onclick="DepartmentSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?}?>
			</div>
		</div>


	</div>
</div>