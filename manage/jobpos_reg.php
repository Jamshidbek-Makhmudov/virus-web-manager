<?php
$page_name = "jobpos_list";
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

$jb_gb = $_REQUEST['jb_gb'];
$jb_seq = $_REQUEST['jb_seq'];

if(!empty($jb_gb) && !empty($jb_seq)){

	if($jb_gb=="P"){

		$select_columns = "jpos_seq as jb_seq, jpos_name as jb_name, sort, use_yn";
		$seq_column = "jpos_seq";
		$tbl_name = "tb_jobpos";

	}else if($jb_gb=="D"){
		
		$select_columns = "jduty_seq as jb_seq, jduty_name as jb_name, sort, use_yn";
		$seq_column = "jduty_seq";
		$tbl_name = "tb_jobduty";

	}else if($jb_gb=="G"){

		$select_columns = "jgrade_seq as jb_seq, jgrade_name as jb_name, sort, use_yn";
		$seq_column = "jgrade_seq";
		$tbl_name = "tb_jobgrade";

	}


	$qry_params = array("tbl_name"=>$tbl_name,"select_columns"=>$select_columns,"seq_column"=>$seq_column,"jb_seq"=>$jb_seq);
	$qry_label = QRY_JOBCODE_INFO;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

	$jb_name = $row['jb_name'];
	$sort = $row['sort'];
	$use_yn = $row['use_yn'];
	$rnum = $row['rnum'];

	$jb_grd = ($jb_gb=="S")? $row['jb_grd'] : "";

	//이전,다음
	$qry_params = array("tbl_name"=>$tbl_name,"seq_column"=>$seq_column,"rnum"=>$rnum);
	$qry_label = QRY_JOBCODE_INFO_PREV;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$prev_jb_seq  = $row['jb_seq'];

	$qry_params = array("tbl_name"=>$tbl_name,"seq_column"=>$seq_column,"rnum"=>$rnum);
	$qry_label = QRY_JOBCODE_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$next_jb_seq  = $row['jb_seq'];

}//if(!empty($jb_gb)){


?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_position"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<!--등록폼-->
		<form name='frmCode' id='frmCode' method="POST">
		<input type='hidden' name='proc' id='proc'>
		<input type='hidden' name='val_seq' id='val_seq' value="<?=$jb_seq?>">
		<table class="view">
		<tr>
			<th style='width:150px;'><?=$_LANG_TEXT['gubuntext'][$lang_code]?></th>
			<td  style='width:300px;'>
			<?if($jb_gb==""){?>
				<select id='job_gubun' name='job_gubun'>
					<option value=''><?=$_LANG_TEXT['choosetext'][$lang_code]?></option>
					<option value='P' <?if ($jb_gb == "P") { echo "selected"; }?>><?=$_LANG_TEXT['jobpostext'][$lang_code]?></option>
					<option value='D' <?if ($jb_gb == "D") { echo "selected"; }?>><?=$_LANG_TEXT['jobdutytext'][$lang_code]?></option>
					<option value='G' <?if ($jb_gb == "G") { echo "selected"; }?>><?=$_LANG_TEXT['jobgradetext'][$lang_code]?></option>
				</select>
			<?}else{?>
				<input type="hidden" name="job_gubun" id="job_gubun" value="<?=$jb_gb?>">
			<?
				if ($jb_gb == "P"){
					echo $_LANG_TEXT['jobpostext'][$lang_code];
				}else if($jb_gb == "D"){
					echo $_LANG_TEXT['jobdutytext'][$lang_code];
				}else if($jb_gb == "G"){
					echo $_LANG_TEXT['jobgradetext'][$lang_code];
				}
			}?>
			</td>
			<th  style='width:150px;' class="line"><?=$_LANG_TEXT['codenametext'][$lang_code]?></th>
			<td  style='min-width:300px;'>
				<input type="text" name='code_name' id='code_name' class="frm_input" style="width:280px" value="<?=$jb_name?>"   maxlength="50">
			</td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
			<td>
				<input type='text' name='sort' id='sort' class="frm_input"  style="width:20%"  onkeyup="onlyNumber(this)" value="<?=$sort?>"   maxlength="5">
			</td>
			<th  class="line"><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<td colspan="3">
				<select name='useyn' id='useyn'>
					<option value='Y'  <?if ($use_yn == "Y") { echo "selected"; }?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
					<option value='N'  <?if ($use_yn == "N") { echo "selected"; }?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
				</select>
			</td>
		</tr>
		</table>
		
		
		<div class="btn_wrap">
<?if($jb_seq!=""){?>
			<div class="left">
				<a href="<?if(empty($prev_jb_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("jb_gb=".$jb_gb."&jb_seq=".$prev_jb_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_jb_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("jb_gb=".$jb_gb."&jb_seq=".$next_jb_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?}?>
			<div class="right">
				<a href="./jobpos_list.php" class="btn"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?if($jb_seq==""){?>
				<a href="#" id="btnReg" class="btn required-create-auth hide" onclick="return JobCodeSubmit('CREATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
<?}else{?>
				<a href="#" id="btnEdit" class="btn required-update-auth hide" onclick="return JobCodeSubmit('UPDATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="#" id='btnDelete' class="btn required-delete-auth hide" onclick="return JobCodeSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?}?>
				<a href="./jobpos_reg.php" id="btnClear" class="btn" ><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

		

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>