<?php
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
if($paging == "") $paging = $_paging;

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;

?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["attemptlogin"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

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
								<?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></option>
							<option value="idorname" <?php if($searchopt == "idorname") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['idornametext'][$lang_code]?></option>

						</select>

						<input type="text" name="searchkey" id="searchkey" value="<?=$searchkey?>" class="frm_input"
							style="width:60%" onKeyPress="if(event.keyCode==13){return SearchSubmit(document.searchForm);}"
							maxlength="50">
						<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit"
							onclick="SearchSubmit(document.searchForm);">

					</td>
				</tr>
			</table>

		</form>

		<!--검색결과리스트-->
		<table class="list">
			<tr>
				<th class="num" style='width:100px;min-width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['idtext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['registdatetext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['reset_yn'][$lang_code]?></th>


			</tr>

			<?php

$order_sql = "ORDER BY admin_attempt_log_seq DESC "; 

if($searchkey != "" && $searchopt != "") {
	
	if($searchopt=="ip"){ 

		$search_sql .= " and a.ip_addr like '$searchkey%' "; 

	}else if($searchopt=="idorname"){ 

		if(trim($searchkey)==trim($_LANG_TEXT['alltext'][$lang_code])){

			$search_sql .= "and b.emp_name ='ALL' "; 
		}else{

			$search_sql .= " and (b.emp_no like '%$searchkey%' OR b.emp_name = '".aes_256_enc($searchkey)."' ) "; 
		}
	
	}
}

$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_USER_LOGINATTEMPT_LIST_COUNT;
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
$qry_label = QRY_USER_LOGINATTEMPT_LIST;  
$sql = query($qry_label,$qry_params);

//echo nl2br($sql);
	
//echo $sql;
$result = @sqlsrv_query($wvcs_dbcon, $sql);  
$cnt = 20;
$iK = 0;
$classStr = "";

//echo nl2br($sql);

 if($result){
	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;
		
		$admin_attempt_log_seq = $row['admin_attempt_log_seq'];  //shu yerdan boshlab farq
		$ip_addr = $row['ip_addr'];
		if(trim($row['reset_yn'])=="ALL"){
			$str_allow_user = $_LANG_TEXT['alltext'][$lang_code];
		}else{
		
			$str_allow_user = $row['reset_yn']; 
		}
		//
			$emp_no = $row['emp_no'];
			$emp_name = aes_256_dec($row['emp_name']);	
		$param_enc = ParamEnCoding("admin_attempt_log_seq=".$admin_attempt_log_seq.($param ? "&" : "").$param);
		$create_date = $row['create_date'];
					  ?>


			<tr onclick="javascript:location.href='#?enc=<?=$param_enc?>'" style='cursor:pointer'>
				<td><?=$no?></td>
				<td><?=$emp_no?></td>
				<td><?=$emp_name?></td>
				<td><?=$ip_addr?></td>
				<td><?=$create_date?></td>
				<td><?=$str_allow_user?></td>

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
<?php

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>