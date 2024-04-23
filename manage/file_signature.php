<?php
$page_name = "file_signature";

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


$searchopt = $_REQUEST["searchopt"];	// 검색옵션
$searchkey = $_REQUEST["searchkey"];	// 검색어
$page = intval($_REQUEST[page]);	

$paging = $_REQUEST[paging];

if($paging == "") $paging = $_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;


?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["filesignature"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<!-- new feature -->
		<!-- <div style="border:1px solid black;"> -->

		<ul class="tab">
			<li class="on "><a href="javascript:"
					onclick="location.href='./file_signature.php'"><?=$_LANG_TEXT['filesignature'][$lang_code]?></a></li>
			<li class=""><a href="javascript:"
					onclick="location.href='./signature_mapping.php'"><?=$_LANG_TEXT['signaturemapping'][$lang_code]?></a></li>

		</ul>
		<!-- </div> -->
		<!-- new feature -->

		<!--검색폼-->
		<form name="searchForm" id="searchForm" method="POST">
			<table class="search">
				<tr>
					<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?></th>
					<td>

						<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
							<option value="ip" <?php if($searchopt == "ip") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['fileidnntext'][$lang_code]?></option>
							<option value="idorname" <?php if($searchopt == "idorname") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['filesignature'][$lang_code]?></option>
							<option value="Y" <?php if($searchopt == "Y") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['useyesnntext'][$lang_code]?></option>
							<option value="N" <?php if($searchopt == "N") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['usenonntext'][$lang_code]?></option>

						</select>

						<input type="text" name="searchkey" id="searchkey" value="<?=$searchkey?>" class="frm_input"
							style="width:60%" onKeyPress="if(event.keyCode==13){return SearchSubmit(document.searchForm);}"
							maxlength="50">
						<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit"
							onclick="SearchSubmit(document.searchForm);">

					</td>
				</tr>
			</table>
			<div class="btn_confirm">
				<a href="./file_signature_reg.php" class="btn required-create-auth hide"><?=$_LANG_TEXT['register'][$lang_code]?></a>
			</div>

			<!--  sql code-->
			<?php
			$order_sql = "ORDER BY sign_id_seq DESC "; 

			
if($searchkey != "" && $searchopt != "") {
	
	if($searchopt=="ip"){ 

		$search_sql .= " and file_id like '$searchkey%' "; 

	}else if($searchopt=="idorname"){ 

		if(trim($searchkey)==trim($_LANG_TEXT['alltext'][$lang_code])){
			$search_sql .= "and str_name ='ALL' ";  //org
		}else{

			$search_sql .= " and str_name like '%$searchkey%' "; 
		}
	
	}else if($searchopt=="Y"){

		$search_sql .= " and use_yn like '%$searchkey%' ";
	} else if ($searchopt == "N") {

					$search_sql .= " and use_yn like '%$searchkey%' ";
				}
	
}

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_FILE_SIGNATURE_COUNT;  

$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);

$result = @sqlsrv_query($wvcs_dbcon, $sql); 

$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC); 
$total = $row['cnt'];

$rows = $paging;			// 페이지당 출력갯수
$lists = $_list;			// 목록수
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
$end = $start + $rows;

$qry_params = array("end"=>$end,"order_sql"=>$order_sql,"search_sql"=>$search_sql,"start"=>$start); 
$qry_label = QRY_FILE_SIGNATURE_LIST; 
$sql = query($qry_label,$qry_params);

// echo date("YmdHis");
//echo $sql;
$result = @sqlsrv_query($wvcs_dbcon, $sql); 

$cnt = 20;
$iK = 0;
$classStr = "";
				
			?>
			<div style='line-height:30px;'>
				Results : <span style='color:blue'><?=number_format($total)?></span> /
				Records : <select name='paging' onchange="searchForm.submit();">
					<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
					<option value='40' <?if($paging=='40' ) echo "selected" ;?>>40</option>
					<option value='60' <?if($paging=='60' ) echo "selected" ;?>>60</option>
					<option value='80' <?if($paging=='80' ) echo "selected" ;?>>80</option>
					<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
				</select>
			</div>

		</form>

		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px">
			<tr>
				<th class="num" style='width:100px;min-width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['fileidnntext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['filesignature'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['useyesnonntext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['registdatetext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
			</tr>
			<?php


 if($result){
	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;
		
		$sign_id_seq = $row['sign_id_seq']; 
		$file_id = $row['file_id'];
		if(trim($row['str_name'])=="ALL"){
			$str_allow_user = $_LANG_TEXT['alltext'][$lang_code];
		}else{
	
			$str_allow_user = $row['str_name']; 
		}
		
		$use_yn = $row['use_yn'];
							if($use_yn == "Y") {
						//$use_str = "사용";
						$use_str = $_LANG_TEXT['useyesnntext'][$lang_code];
					} else if(($use_yn == "N")) {
						//$use_str = "사용안함";
						$use_str = $_LANG_TEXT['usenonntext'][$lang_code];
					}
	
		$param_enc = ParamEnCoding("sign_id_seq=".$sign_id_seq.($param ? "&" : "").$param);

		$create_date = $row['create_date'];
		$formatted_date = date("Y-m-d H:i", strtotime($create_date));


					  ?>

			<tr onclick="sendPostForm('./file_signature_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
				<td><?=$no?></td>
				<td><?=$file_id?></td>
				<td><?=$str_allow_user?></td>
				<td><?=$use_str?></td>

				<td><?=$formatted_date?></td>
				<td onclick="event.stopPropagation();"><span onclick="FileSignatureDelete('<?=$sign_id_seq?>')"
						style='cursor:pointer;' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
			</tr>

			<?php
						
						$no--;
					}
					
				}

				if($total < 1) {
						
					?>
			<tr>
				<td colspan="8" align="center"><?php echo $_LANG_TEXT["noneresult"][$lang_code]; ?></td>
			</tr>
			<?php
					}
					?>

		</table>

		<!--페이징-->
		<?php
			if($total > 0) {
				$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
				print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
			}
	?>

	</div>

</div>



<script>
document.addEventListener('DOMContentLoaded', function() {
	const selectElement = document.getElementById('searchopt');
	const inputElement = document.getElementById('searchkey');

	selectElement.addEventListener('change', function() {
		// Get the selected option's value
		const selectedOptionValue = selectElement.value;

		if (selectedOptionValue === 'Y' || selectedOptionValue === 'N') {
			// Autofill the input for Option 2 and Option 3
			inputElement.value = selectedOptionValue;
		} else {
			// Clear the input for other options (e.g., Option 1)
			inputElement.value = '';
		}
	});
});
</script>


<?php

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>