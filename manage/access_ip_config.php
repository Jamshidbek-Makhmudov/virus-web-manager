<?php
$page_name = "access_ip_config";

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

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}
?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["m_manage_accessip"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" id="searchForm" method="POST">
			<input type='hidden' name='proc_name' id='proc_name'>
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
							<option value="memo" <?php if($searchopt == "memo") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['memotext'][$lang_code]?></option>
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
				<a href="./access_ip_reg_multiple.php"
					class="btn required-create-auth hide"><?=$_LANG_TEXT['btnipregist'][$lang_code]?></a>
			</div>



		</form>

		<!--검색결과리스트-->
		<table class="list">
			<tr>
				<th class="num" style='width:100px;min-width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['accesspermitid'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['memotext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['registertext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['registdatetext'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
			</tr>




			<?php

$order_sql = "ORDER BY login_ip_mgt_seq DESC ";


				
if($searchkey != "" && $searchopt != "") {
	
	if($searchopt=="ip"){ 


		$search_sql .= " and a.ip_addr like '$searchkey%' "; 

	}else if($searchopt=="idorname"){ 

		if(trim($searchkey)==trim($_LANG_TEXT['alltext'][$lang_code])){

			$search_sql .= "and a.allow_id ='ALL' ";  
		}else{


			$search_sql .= " and (a.allow_id like '%$searchkey%' OR b.emp_name = '".aes_256_enc($searchkey)."') ";    
		}
	
	}else if($searchopt=="memo"){


		$search_sql .= " and a.memo like '%$searchkey%' "; 
	}
}


$qry_params = array("search_sql"=>$search_sql);
$qry_label = QRY_LOGIN_IP_LIMIT_COUNT;  

$sql = query($qry_label,$qry_params);


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
$qry_label = QRY_LOGIN_IP_LIMIT_LIST;  
$sql = query($qry_label,$qry_params);



$result = @sqlsrv_query($wvcs_dbcon, $sql); 
$cnt = 20;
$iK = 0;
$classStr = "";





 if($result){
	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;
		
		$login_ip_mgt_seq = $row['login_ip_mgt_seq'];  
		$ip_addr = $row['ip_addr'];
		if(trim($row['allow_id'])=="ALL"){
			$str_allow_user = $_LANG_TEXT['alltext'][$lang_code];
		}else{

			$str_allow_user = $row['allow_id']." (".aes_256_dec($row['allow_name']).")"; 
		}
		
		$memo = $row['memo'];

		$admin_name = aes_256_dec($row['emp_name']);	
		
		$param_enc = paramEncoding("login_ip_mgt_seq=".$login_ip_mgt_seq.($param ? "&" : "").$param);

		$create_date = $row['create_date'];

					  ?>





			<tr onclick="sendPostForm('./access_ip_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
				<td><?=$no?></td>
				<td><?=$ip_addr?></td>
				<td><?=$str_allow_user?></td>
				<td><?=$memo?></td>
				<td><?=$admin_name?></td>
				<td><?=$create_date?></td>
				<td onclick="event.stopPropagation();"><span onclick="AccessIPDeleteSubmit('<?=$login_ip_mgt_seq?>')"
						style='cursor:pointer;' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span>
				</td>
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
		<div class="btn_confirm">
			<div class="right"><a href="./access_ip_reg_multiple.php"
					class="btn required-create-auth hide"><?=$_LANG_TEXT['btnipregist'][$lang_code]?></a></div>
		</div>
	</div>

</div>
<?php

if($result) sqlsrv_free_stmt($result);  
if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>