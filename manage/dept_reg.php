<?php
$page_name = "dept_list";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$dept_seq = $_REQUEST['dept_seq'];
$searchopt = $_REQUEST['searchopt'];
$searchkey = $_REQUEST['searchkey'];
$org_id = $_REQUEST['sel_org2'];
$p_dept_seq = $_REQUEST['p_dept_seq2'];

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($org_id!="") $param .= ($param==""? "":"&")."sel_org2=".$org_id;
if($p_dept_seq!="") $param .= ($param==""? "":"&")."p_dept_seq2=".$p_dept_seq;

if(!empty($dept_seq)){

	if($org_id !=""){
		$search_sql .= " and p.org_id = '$org_id'";
	}

	if($p_dept_seq != "" && $p_dept_seq != "0"){
		$search_sql .= " and p.p_dept_seq = '$p_dept_seq'";
	}

	if($searchkey != "" && $searchopt != "") {
		$search_sql .= " and $searchopt like '%$searchkey%' ";
	}
	

	$qry_params = array("search_sql"=>$search_sql,"dept_seq"=>$dept_seq);
	$qry_label = QRY_DEPT_INFO;
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
			$rnum = $row['rnum'];
	  }
	}
	
	
	$qry_params = array("search_sql"=>$search_sql,"rnum"=>$rnum);
	$qry_label = QRY_DEPT_INFO_PREV;
	$sql = query($qry_label,$qry_params);

	
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$prev_dept_seq = $row['dept_seq'];

	$qry_params = array("search_sql"=>$search_sql,"rnum"=>$rnum);
	$qry_label = QRY_DEPT_INFO_NEXT;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$next_dept_seq = $row['dept_seq'];
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
<script language="javascript">
	$(function(){
		MngDeptSubOrgSet();
	});
</script>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/topmenu.inc";
?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_department"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>
		<!--등록폼-->
		<form name="formDept" id="formDept" method="post">
		<input type="hidden" name="dept_seq" id="dept_seq" value="<?=$dept_seq?>">
		<input type="hidden" name="proc" id="proc">
		<input type="hidden" name="src" id="src" value="dept_list">
		<table class="view">
		<tr>
			<th style='width:150px;'><?=$_LANG_TEXT['belongorgantext'][$lang_code]?></th>
			<td style='width:300px;'>
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
			<th style='width:150px;' class="line"><?=$_LANG_TEXT['superdepttext'][$lang_code]?></th>
			<td style='min-width:300px;'>
				<select name="p_dept_seq" id="p_dept_seq">
					<option value="0" org='' hierarchy=''><?=$_LANG_TEXT['superdeptselecttext'][$lang_code]?></option>
		
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
			<td><input type="text" name="dept_name" id="dept_name" value="<?=$dept_name?>"  class="frm_input" style="width:90%"   maxlength="50"></td>
			<th class="line"><?=$_LANG_TEXT['deptchieftext'][$lang_code]?></th>
			<td>
				<select name="dept_chief" id="dept_chief">
					<option value="" org=""><?=$_LANG_TEXT['deptchiefselecttext'][$lang_code]?></option>
			
					<?php
						$qry_params = array("items"=>"org_id,emp_seq,emp_name");
						$qry_label = QRY_COMMON_EMP;
						$sql = query($qry_label,$qry_params);
						
						$result = sqlsrv_query($wvcs_dbcon, $sql);

						while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){
								$tmp_org_id = $row['org_id'];
								$tmp_emp_seq = $row["emp_seq"];
								$tmp_emp_name = aes_256_dec($row['emp_name']);	

								echo "<option value='$tmp_emp_seq'  org='$tmp_org_id' ".($tmp_emp_seq==$dept_chief_seq? "selected='selected'" : "").">$tmp_emp_name</option>";
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
				<input type="text" name="sort" id="sort" class="frm_input" onkeyup="onlyNumber(this)" size="3" style="width:60px" value="<?=$sort?>"   maxlength="10">
			</td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['deptauthtext'][$lang_code]?></th>
			<td colspan="3">
				<input type="text" name="dept_auth1" id="dept_auth1" class="frm_input" style="width:10%" value="<?=$dept_auth1?>"   maxlength="25">
				<input type="text" name="dept_auth2" id="dept_auth2" class="frm_input" style="width:10%" value="<?=$dept_auth2?>"   maxlength="25">
				<input type="text" name="dept_auth3" id="dept_auth3" class="frm_input" style="width:10%" value="<?=$dept_auth3?>"   maxlength="25">
			</td>
		</tr>
		</table>
		
		<div class="btn_wrap">
<?if($dept_seq !=""){?>
			<div class="left">
				<a href="<?if(empty($prev_dept_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("dept_seq=".$prev_dept_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_dept_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("dept_seq=".$next_dept_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?}?>
			<div class="right">
				<a href="./dept_list.php" class="btn"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?if($dept_seq ==""){?>
				<a href="#" class="btn required-create-auth hide" id='btnDeptReg' onclick="DepartmentSubmit('CREATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
<?}else{?>
				<a href="#" class="btn required-update-auth hide" id='btnDeptEdit' onclick="DepartmentSubmit('UPDATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="#" class="btn required-delete-auth hide" id='btnDeptDel' onclick="DepartmentSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?}?>
				<a href="./dept_reg.php" id='btnClear' class="btn"><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

		

	</div>

</div>

<?php

if($result)	sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>