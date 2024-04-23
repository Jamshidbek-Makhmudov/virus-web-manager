<?php
$page_name = "vaccine_list";
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

$v_seq = $_REQUEST['v_seq'];
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST['orderby'];		// 정렬순서

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if(empty($v_seq)){
		
	$writer = $_ck_user_name."(".$_ck_user_id.")"; 
	$write_date = date("Y-m-d H:i");

}else{

	if($searchkey != ""){

		  if($searchopt=="V_NAME"){

			$search_sql .= " and vacc_name like '%$searchkey%' ";

		  }else if($searchopt == "V_VER"){
			
			$search_sql .= " and vacc_ver like '%$searchkey%' ";
		  
		  }else if($searchopt == "V_DESC"){
			
			$search_sql .= " and vacc_desc like '%$searchkey%' ";
		  
		  }else if($searchopt == "P_NAME"){
			
			$search_sql .= " and process_name like '%$searchkey%' ";
		  
		  }
	  }

	if($orderby != "") {
		$order_sql = " ORDER BY $orderby";
	} else {
		$order_sql = " ORDER BY sort, vacc_seq DESC ";
	}

	

	$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"vacc_seq"=>$v_seq);
	$qry_label = QRY_VACCINE_INFO;
	$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);

	$result =@sqlsrv_query($wvcs_dbcon, $sql);

	if($result){
	  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		  $vacc_name = $row['vacc_name'];
		  $vacc_ver = $row['vacc_ver'];
		  $use_yn = $row['use_yn'];
		  $sort = $row['sort'];
		  $vacc_desc = $row['vacc_desc'];
		  $gubun = $row['gubun'];
		  $process_name = $row['process_name'];
		  $link = $row['link'];
		  $create_dt = $row['create_dt'];
		  $modify_dt = $row['modify_dt'];
		  $create_emp = $row['create_emp'];
		  $modify_emp = $row['modify_emp'];
		  $rnum = $row['rnum'];

	  }
	}

	//이전,다음
	$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"rnum"=>$rnum);
	$qry_label = QRY_VACCINE_INFO_PREV;
	$sql = query($qry_label,$qry_params);
	
	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$prev_v_seq = $row['vacc_seq'];

	$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"rnum"=>$rnum);
	$qry_label = QRY_VACCINE_INFO_NEXT;
	$sql = query($qry_label,$qry_params);

	$result = sqlsrv_query($wvcs_dbcon, $sql);
	$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	$next_v_seq = $row['vacc_seq'];

	if($result) sqlsrv_free_stmt($result);  
	sqlsrv_close($wvcs_dbcon);

}

?>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_vaccine"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>

		<!--등록폼-->
		<form name="FrmVacc" id="FrmVacc" method="post">
		<input type="hidden" name="v_seq" id="v_seq" value="<?=$v_seq?>">
		<input type="hidden" name="proc" id="proc" >
		<table class="view">
<?
	if($v_seq != ""){
?>
		<tr>
			<th style='width:150px'><?=$_LANG_TEXT['registertext'][$lang_code]?></th>
			<td style='width:300px'><?=$create_emp?></td>
			<th style='width:150px' class="line"><?=$_LANG_TEXT['registerdatetext'][$lang_code]?></th>
			<td style='min-width:300px'><?=$create_dt?></td>
		</tr>
<?
		if($modify_emp != ""){
?>
		<tr >
			<th style='width:150px'><?=$_LANG_TEXT['updatertext'][$lang_code]?></th>
			<td style='width:300px'><?=$modify_emp?></td>
			<th style='width:150px' class="line"><?=$_LANG_TEXT['updatedatetext'][$lang_code]?></th>
			<td style='min-width:300px'><?=$modify_dt?></td>
		</tr>
<?
		}
	}
?>
		<tr class="bg">
			<th style='width:150px'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
			<td style='width:300px'><input type="text" name="v_name" id="v_name" class="frm_input" style="width:90%" value="<?=$vacc_name?>" maxlength="100"></td>
			<th style='width:150px' class="line"><?=$_LANG_TEXT['versiontext'][$lang_code]?></th>
			<td style='min-width:300px'><input type="text" name="v_ver" id="v_ver" class="frm_input" style="width:100px	" value="<?=$vacc_ver?>"   maxlength="20"></td>
		</tr>
		<tr >
			<th><?=$_LANG_TEXT['descriptiontext'][$lang_code]?></th>
			<td colspan="3"><input type="text" name="v_desc" id="v_desc" class="frm_input" style="min-width:750px" value="<?=$vacc_desc?>" maxlength="200"></td>
		</tr>
		<tr class="bg">
			<th><?=$_LANG_TEXT['filenametext'][$lang_code]?></th>
			<td colspan="3"><input type="text" name="p_name" id="p_name" class="frm_input" style="min-width:750px" value="<?=$process_name?>"  maxlength="50"></td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['downloadlinktext'][$lang_code]?></th>
			<td colspan="3"><input type="text" name="link" id="link" class="frm_input" style="min-width:750px" value="<?=$link?>"  maxlength="250"></td>
		</tr>
		<tr >
			<th><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
			<td>
				<Select id='use_yn' name='use_yn'>
					<option value='Y' <?if($use_yn=="Y") echo "selected";?>><?=$_LANG_TEXT['useyestext'][$lang_code]?></option>
					<option value='N' <?if($use_yn=="N") echo "selected";?>><?=$_LANG_TEXT['usenotext'][$lang_code]?></option>
				</Select>
			</td>
			<th class="line"><?=$_LANG_TEXT['sortordertext'][$lang_code]?></th>
			<td><input type="text" name="sort" id="sort" class="frm_input" style="width:100px" value="<?=$sort?>" onkeyup="onlyNumber(this)"   maxlength="5"></td>
		</tr>
		</table>
		
		
		<div class="btn_wrap">
<?if($v_seq != ""){?>
			<div class="left">
				<a href="<?if(empty($prev_v_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("v_seq=".$prev_v_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_v_seq)){?>javASCript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("v_seq=".$next_v_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?}?>
			<div class="right">
				<a href="./vaccine_list.php" class="btn"><?=$_LANG_TEXT['btnlist'][$lang_code]?></a>
<?if($v_seq == ""){?>
				<a href="javascript:void(0)" class="btn required-create-auth hide"  onclick="return VaccSubmit('CREATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
<?}else{?>
				<a href="javascript:void(0)" class="btn required-update-auth hide"  onclick="return VaccSubmit('UPDATE')"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
				<a href="javascript:void(0)" class="btn required-delete-auth hide" onclick="VaccSubmit('DELETE')"><?=$_LANG_TEXT['btndelete'][$lang_code]?></a>
<?}?>
				<a href="./vaccine_reg.php" class="btn"><?=$_LANG_TEXT['btnclear'][$lang_code]?></a>
			</div>
		</div>

		</form>

		

	</div>

</div>


<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>