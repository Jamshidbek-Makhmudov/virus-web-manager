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

$searchopt = $_REQUEST["searchopt"];	// 검색옵션
$searchkey = $_REQUEST["searchkey"];	// 검색어
$page = $_REQUEST[page];				// 페이지
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
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_scan_center"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" id="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
		<input type='hidden' name='proc_name' id='proc_name'>
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?></th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value="cn_name" <?=($searchopt=="cn_name"? "selected='selected'" : "") ?>><?=$_LANG_TEXT['scancentertext'][$lang_code]?></option>
					<option value="org_name" <?=($searchopt=="org_name"? "selected='selected'" : "") ?>><?=$_LANG_TEXT['organtext'][$lang_code]?></option>
				</select>					
				<input type="text" name="searchkey" id="searchkey"  value="<?=$searchkey?>" class="frm_input" style="width:50%" onKeyPress="if(event.keyCode==13){return CenterSearchSubmit(document.searchForm);}"   maxlength="50">
							<input type="submit"  value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit"
							onclick="return SearchSubmit(document.searchForm);">
			</td>
		</tr>
		</table>

		<div class="btn_confirm">
			<a href='javascript:void(0)'  onclick="sendPostForm('./scan_center_reg.php')" class="btn required-create-auth hide"><?=$_LANG_TEXT['btncenterregist'][$lang_code]?></a>
		</div>
		
		</form>
	
		<!--검색결과리스트-->
		<table class="list" >
			<tr>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['organtext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['scancentertext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['centercodetext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;' <? if(count($_CODE_SCAN_CENTER_DIV)==0) echo "class='display-none'";?>><?=$_LANG_TEXT['scancenterdiv'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'>KIOSK</th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['useyntext'][$lang_code]?></th>
				<th class="num_last"><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
			</tr>
			
			<?php

				$order_sql = " ORDER BY sort,scan_center_seq DESC ";

				
				if($searchkey != "" && $searchopt != "") {
					
					if($searchopt=="cn_name"){

						$search_sql .= " and scan_center_name like '%$searchkey%' ";
					
					}else if($searchopt=="org_name"){

						$search_sql .= " and o.org_name like '%$searchkey%' ";
					
					}

				}

				$qry_params = array("search_sql"=>$search_sql);
				$qry_label = QRY_SCAN_CENTER_LIST_COUNT;
				$sql = query($qry_label,$qry_params);

				//echo nl2br($sql);

				
				$result = sqlsrv_query($wvcs_dbcon, $sql);
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
				$qry_label = QRY_SCAN_CENTER_LIST;
				$sql = query($qry_label,$qry_params);
							
				//echo nl2br($sql);

				$result = sqlsrv_query($wvcs_dbcon, $sql);

				$cnt = 20;
				$iK = 0;
				$classStr = "";

				 if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$cnt--;
						$iK++;
						
						$org_name = $row['org_name'];
						$center_div = $row['scan_center_div'];
						$center_name = $row['scan_center_name'];
						$center_code = $row['scan_center_code'];
						$center_seq = $row['scan_center_seq'];
						$kiosk_cnt = $row['kiosk_cnt'];
						$use_yn = $row['use_yn'];

						$usetext = $use_yn =="Y" ? $_LANG_TEXT['useyestext'][$lang_code] : $_LANG_TEXT['usenotext'][$lang_code];

						
						$param_enc = ParamEnCoding("scan_center_seq=".$center_seq.($param ? "&" : "").$param);

					  ?>	
						<tr onclick="sendPostForm('./scan_center_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
							<td><?=$no?></td>
							<td><?=$org_name?></td>
							<td><?=$center_name?></td>
							<td><?=$center_code?></td>
							<td  <? if(count($_CODE_SCAN_CENTER_DIV)==0) echo "class='display-none'";?>><?=$_CODE_SCAN_CENTER_DIV[$center_div]?></td>
							<td><?=$kiosk_cnt?></td>
							<td><?=$usetext?></td>
							<td class="num_last" onclick="event.stopPropagation();" ><span  onclick="ScanCenterDeleteSubmit('<?=$center_seq?>')" style='cursor:pointer;' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
						</tr>
						<?php
						
							$no--;
						}
						
					}

					if($total < 1) {
						
					?>
						<tr>
							<td colspan="8" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
			<div class="right"><a href='javascript:void(0)' onclick="sendPostForm('./scan_center_reg.php')" class="btn required-create-auth hide"><?=$_LANG_TEXT['btncenterregist'][$lang_code]?></a></div>
		</div>
	</div>

</div>
<?php

if($result)	sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>