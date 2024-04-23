<?php
$page_name = "scan_center_list";
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

$scan_center_seq = $_REQUEST["scan_center_seq"];
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST["orderby"];		//정렬

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if($scan_center_seq <> "") {

		if($searchkey != "" && $searchopt != "") {
					
			if($searchopt=="cn_name"){
 
				$search_sql .= " and scan_center_name like '%$searchkey%' ";
			
			}else if($searchopt=="org_name"){

				$search_sql .= " and o.org_name like '%$searchkey%' ";
			
			}

		}

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby ";
		} else {
			$order_sql = " ORDER BY scan_center_seq DESC ";
		}


		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"scan_center_seq"=>$scan_center_seq);
		$qry_label = QRY_SCAN_CENTER_INFO;
		$sql = query($qry_label,$qry_params);

		//echo nl2br($sql);
			
		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		
		$org_id = $row['org_id'];
		$scan_center_seq = $row['scan_center_seq'];
		$scan_center_name = $row['scan_center_name'];
		$scan_center_code = $row['scan_center_code'];
		$scan_center_div = $row['scan_center_div'];
		$use_yn = $row['use_yn'];
		$sort = $row['sort'];
		$rnum = $row['rnum'];

		
		//이전,다음
		$prev_sql = " AND rnum > '$rnum' ORDER BY rnum asc";
		$qry_label = QRY_SCAN_CENTER_INFO_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$prev_sql);
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$prev_scan_center_seq = $row['scan_center_seq'];
		
		$next_sql = " AND  rnum < '$rnum' ORDER BY rnum desc ";
		$qry_label = QRY_SCAN_CENTER_INFO_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$next_sql);
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$next_scan_center_seq = $row['scan_center_seq'];

}
?>
<script>
$(function(){
	$("#wrapper1").scroll(function(){
		$("#wrapper2").scrollLeft($("#wrapper1").scrollLeft());
	});
	$("#wrapper2").scroll(function(){
		$("#wrapper1").scrollLeft($("#wrapper2").scrollLeft());
	});

	window.onresize = function(event) {
		var w = $("#tblList").width();
		$("#div1").width(w);
	};
});
</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_scan_center"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="frmCenter" id="frmCenter" method="post">
		<input type='hidden' name='proc' id='proc'>
		<input type='hidden' name='proc_name'>
		<input type="hidden" name="scan_center_seq" id="scan_center_seq" value="<?php echo $scan_center_seq; ?>">
		<table class="view">
		<tr >
			<th><?=$_LANG_TEXT["organtext"][$lang_code];?></th>
			<td colspan='3'>
				<SELECT id='org_id' name='org_id'>
					<option value=""><?=$_LANG_TEXT['organselecttext'][$lang_code]?></option>
		<?
			/*소속기관*/
			$qry_params = array();
			$qry_label = QRY_COMMON_ORG;
			$sql = query($qry_label,$qry_params);

			$result = sqlsrv_query($wvcs_dbcon, $sql);
			if($result){
				while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

					$_org_id = $row['org_id'];
					$_org_name = $row['org_name'];
		?>
					<option value='<?=$_org_id?>' <? if($_org_id==$org_id) echo "selected";?>><?=$_org_name?></option>
		<?
				}
			}
		?>
			</td>
		</tr>
		<tr class="bg">
			<th style='width:150px;'><?=$_LANG_TEXT["centercodetext"][$lang_code];?></th>
			<td style='width:300px;'>
				<input type="text" name="scan_center_code" id="scan_center_code" class="frm_input"  value="<?php echo $scan_center_code; ?>"   <?=($scan_center_seq==""? "" : "disabled")?>   style="width:90%" maxlength="25">
			</td>
			<th style='width:150px;' class='line'><?=$_LANG_TEXT["centernametext"][$lang_code];?></th>
			<td style='min-width:300px;'>
				<input type="text" name="scan_center_name" id="scan_center_name" class="frm_input" value="<?php echo $scan_center_name; ?>" style="width:300px" maxlength="50">
				<?if(count($_CODE_SCAN_CENTER_DIV) > 0){?>
				<div class='col head'><? echo trsLang('검사장구분','scancenterdiv');?></div>
				<div class='col'>
					<select id='scan_center_div' name='scan_center_div'>
						<option value=''><? echo trsLang('선택','choosetexts');?></option>
						<? foreach($_CODE_SCAN_CENTER_DIV as $code=>$name){
							$selected = $code ==$scan_center_div ? "selected" : "";
						?>
						<option value='<? echo $code;?>' <? echo $selected;?>><? echo $name;?></option>
						<?}?>
					</select>
				</div>
				<?}?>
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
		<tr >
		</tr>
		</table>

		

	<div class="btn_wrap">
	<?php
		if ($scan_center_seq != "") {
	?>
			<div class="left display-none">
				<a href="<?if(empty($prev_scan_center_seq)){?>javascript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("center_seq=".$prev_scan_center_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
				<a href="<?if(empty($next_scan_center_seq)){?>javascript:alert(nodatatext[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."?enc=".ParamEnCoding("center_seq=".$next_scan_center_seq.($param ? "&" : "").$param); }?>"  class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
			</div>
<?php	}?>
			<div class="right">
					<a href="./scan_center_list.php" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
<?php
					if ($scan_center_seq == "") {
?>
							<a href="javascript:void(0)"   onclick="ScanCenterSubmit('CREATE')" class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
<?php
					}else{
?>	
							<a href="javascript:void(0)"   onclick="ScanCenterSubmit('UPDATE')" class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
							<a href="javascript:void(0)"   onclick="ScanCenterSubmit('DELETE')" class="btn required-delete-auth hide"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a>
							

<?php
					}
?>
					<a href="./scan_center_reg.php" class="btn"  id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>
			</div>
		</div>

		</form>
		
		<BR>
		<BR>
		<!--키오스크설정-->
		<?if(COMPANY_CODE=="600"){	//카카오뱅크
			include $_server_path . "/" . $_site_path . "/manage/scan_center_kiosk.php";
		}?>


	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>