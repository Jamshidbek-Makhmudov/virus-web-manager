<?php
$page_name = "group_list";
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

$group_seq = $_REQUEST["group_seq"];

if($group_seq <> "") {

	$qry_params = array("group_seq"=>$group_seq);
	$qry_label = QRY_GROUP_INFO;
	$sql = query($qry_label,$qry_params);
		
	//echo $sql;
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$group_seq = $row['group_seq'];
	$group_name = $row['group_name'];
	$memo = $row['memo'];
	$rnum = $row['rnum'];

	$qry_params = array("rnum"=>$rnum);
	$qry_label = QRY_GROUP_INFO_PREV;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$prev_group_seq  = $row['group_seq'];

	$qry_params = array("rnum"=>$rnum);
	$qry_label = QRY_GROUP_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$next_group_seq  = $row['group_seq'];
}

?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_group"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>

		<!--등록폼-->
		<form name="frmGroup" id="frmGroup">
		<input type="hidden" name="group_seq" id="group_seq"  value="<?php echo $group_seq; ?>">
		<input type="hidden" name="proc" id="proc"  value="">	
		<table class="view">
		<tr>
			<th style='width:150px'><?=$_LANG_TEXT['groupnametext'][$lang_code]?></th>
			<td><input type="text" name="group_name" id="group_name" value="<?php echo $group_name; ?>" class="frm_input" style="width:750px"   maxlength="50"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['groupdescriptiontext'][$lang_code]?></th>
			<td><input type="text" class="frm_input" style="width:750px"  name="memo" id="memo" value="<?php echo $memo; ?>"   maxlength="100"></td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['organselecttext'][$lang_code]?></th>
			<td>
				<div class="radio">
					<?php
					if($group_seq <> "") {
						$subSql = " AND group_seq = '{$group_seq}' ";
						$subsubSql = " AND ((ISNULL(B.group_seq, '') = '') OR B.group_seq = '{$group_seq}') ";
								
					}

					if($group_seq == "") {
						$subsubSql = " AND (ISNULL(B.group_seq, '') = '')  ";
						
					}
					
					$qry_params = array("subSql"=>$subSql,"subsubSql"=>$subsubSql);
					$qry_label = QRY_GROUP_INFO_ORG;
					$sql = query($qry_label,$qry_params);
					//echo $sql;
					$result = sqlsrv_query($wvcs_dbcon, $sql);
					$cnt_frm = 0;
					if($result){
						while( $row2 = sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)) {
							$cnt_frm++;
							if($row2['CHECK_DIV'] > 0) {
								$check_div = "checked";
							} else {
								$check_div = "";
							}
							$org_name_str = $row2['org_name'];
							
							
							$leftStr = '<input type="checkbox" name="org_id[]" id="org_id_'.$row2['org_id'].'"  value="' . $row2['org_id'] .'"  ' . $check_div . ' /><label for="org_id_'.$row2['org_id'].'">' . $org_name_str . '</label>' ;
							echo $leftStr;
						}
					}

					if($cnt_frm == 0) { 
						echo "<script language='javascript'>alert(qq134[lang_code]);history.back();</script>";
						exit;
					}
					?>
				</div>
			</td>
		</tr>

		</table>
		
		
		<div class="btn_wrap">
<?if($group_seq != ""){?>
			<div class="left">
				<a href="<?if(empty($prev_group_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("group_seq=".$prev_group_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_group_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("group_seq=".$next_group_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?}?>
			<div class="right">
				<a href="./group_list.php" class="btn" id='btnList'><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?if($group_seq == ""){?>
				<a href="#" class="btn required-create-auth hide"  id='btnReg' onclick="javascript:GroupAddSubmit('CREATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
<?}else{?>
				<a href="#" class="btn required-update-auth hide"  id='btnEdit' onclick="javascript:GroupAddSubmit('UPDATE')"><?=$_LANG_TEXT['btnupdate'][$lang_code]?></a>
				<a href="#" class="btn required-delete-auth hide"  id='btnDelete' onclick="javascript:GroupAddSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?}?>
				<a href="./group_reg.php" id='btnClear' class="btn"><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

	</div>

</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>