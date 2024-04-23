<?php
$page_name = "login_log";

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

$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];
$paging = $_REQUEST[paging];		// 페이지
if($paging == "") $paging = $_paging;

if($start_date=="") $start_date = date( "Y-m-d", strtotime( date("Y-m-d")." -1 month" ) );
if($end_date=="") $end_date = date("Y-m-d");

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($start_date!="") $param .= ($param==""? "":"&")."start_date=".$start_date;
if($end_date!="") $param .= ($param==""? "":"&")."end_date=".$end_date;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

?>
<script language="javascript">
$(function() {
	$("#start_date").datepicker(pickerOpts);
	$("#end_date").datepicker(pickerOpts);
});
</script>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_adminloginlog"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
			<input type="hidden" name="page" value="">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">
				<tr>
					<th><?=$_LANG_TEXT['loginperiodtext'][$lang_code]?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px"
							value="<?=$start_date?>" maxlength="10"> ~ <input type="text" name="end_date" id="end_date"
							class="frm_input" placeholder="" style="width:100px" value="<?=$end_date?>" maxlength="10">
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
					<td>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
							<option value="EMP_NAME" <?php if($searchopt == "EMP_NAME") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['empnametext'][$lang_code]?></option>
							<option value="EMP_NO" <?php if($searchopt == "EMP_NO") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['empnotext'][$lang_code]?></option>
							<option value="IP" <?php if($searchopt == "IP") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></option>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"
							value="<?=$searchkey?>" maxlength="50">

						<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit"
							onclick="return SearchSubmit(document.searchForm);">

					</td>
				</tr>
			</table>
			<?php 
			//**유지보수 관리자아이디(dptadmin) 숨김처리
	$search_sql .= " and emp_no != 'dptadmin' ";

	if($start_date != "" && $end_date != ""){
		$search_sql .= " AND login_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
	}
	  
	if($searchkey != ""){

		if($searchopt=="EMP_NAME"){

		$search_sql .= " and emp_name = '".aes_256_enc($searchkey)."' ";

		}else if($searchopt == "EMP_NO"){

		$search_sql .= " and emp_no like '%$searchkey%' ";

		}else if($searchopt == "IP"){

		$search_sql .= " and ip_addr like '%$searchkey%' ";

		}
	}


		$qry_params = array("search_sql"=>$search_sql);
		$qry_label = QRY_USER_LOGINLOG_LIST_COUNT;
		$sql = query($qry_label,$qry_params);
		// echo nl2br($sql);

		$result = sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$total = $row['CNT'];

		$rows = $paging;			// 페이지당 출력갯수
		$lists = $_list;			// 목록수
		$page_count = ceil($total/$rows);
		if(!$page || $page > $page_count) $page = 1;
		$start = ($page-1)*$rows;
		$no = $total-$start;
		$end = $start + $rows;

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql = " ORDER BY login_seq DESC ";
		}

									
		$qry_params = $qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
		$qry_label = QRY_USER_LOGINLOG_LIST;
		$sql = query($qry_label,$qry_params);

		$result =@sqlsrv_query($wvcs_dbcon, $sql);

		// echo nl2br($sql);
		
		$cnt = 20;
		$iK = 0;
		$classStr = "";
			
			?>





			<div class="btn_wrap">
				<? $excel_down_url = $_www_server."/stat/login_log_excel.php?enc=".ParamEnCoding($param);?>
				<div class="right">
					<a href="#" id='btnexcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnexcelDown')"
						class="btnexcel required-print-auth hide"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
				</div>
					<div style='margin-right:10px; line-height:30px; ' class="right">
					Results : <span style='color:blue'><?= number_format($total) ?></span> /
					Records : <select name='paging' onchange="searchForm.submit();">
						<option value='20' <? if ($paging == '20') echo "selected"; ?>>20</option>
						<option value='40' <? if ($paging == '40') echo "selected"; ?>>40</option>
						<option value='60' <? if ($paging == '60') echo "selected"; ?>>60</option>
						<option value='80' <? if ($paging == '80') echo "selected"; ?>>80</option>
						<option value='100' <? if ($paging == '100') echo "selected"; ?>>100</option>
					</select>
				</div>
			</div>


		</form>

		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px">
			<tr>
				<th class="num"><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['logintimetext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['logouttimetext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['empnametext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['empnotext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['loginstatustext'][$lang_code]?></th>
				<th style='width:200px'><?=$_LANG_TEXT['loginidstatus'][$lang_code]?></th>
			</tr>
			<?php

	

		if($result){
		  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				$cnt--;
				$iK++;
				
				$emp_name = aes_256_dec($row['emp_name']);
				$emp_no = $row['emp_no'];
				$ip_addr = $row['ip_addr'];
				$login_dt = $row['login_dt'];
				$logout_dt = $row['logout_dt'];
				
				$login_yn = $row['LOGIN_YN'];
				$login_fail_cnt = $row['LOGIN_FAIL_CNT'];
				$login_lock_yn = $row['LOGIN_LOCK_YN'];
				$login_lock_type = $row['LOGIN_LOCK_TYPE'];
								
				if($login_yn=="N"){
					if($login_fail_cnt=="") $login_fail_cnt = "1";
					$str_login_yn = $_LANG_TEXT['procfail'][$lang_code]."(".$login_fail_cnt.trim($_LANG_TEXT['timestext'][$lang_code]).")";
				}else{
					$str_login_yn = $_LANG_TEXT['procsuccess'][$lang_code];
				}
				
				if($login_lock_type=="LOGIN_ATTEMPT_OVER"){
					$str_login_lock_type = $_LANG_TEXT['loginattemptexceed'][$lang_code];
				}else{
					$str_login_lock_type = "";
				}

				if($login_lock_yn=="Y"){
					$str_lock_yn = "<font color='blue'>".$_LANG_TEXT['loginlock'][$lang_code]."<font>";
				}else{
					$str_lock_yn = $_LANG_TEXT['normal'][$lang_code];
				}
				
				

				
		  ?>

			<tr>
				<td><?php echo $no; ?></td>
				<td><?=$login_dt?></td>
				<td><?=$logout_dt?></td>
				<td><?=$emp_name?></td>
				<td><?=$emp_no?></td>
				<td><?=$ip_addr?></td>
				<td><?=$str_login_yn?></td>
				<td>
					<span onmouseover="javascript:viewlayer(true, 'moverlayerLock_<? echo $no?>');"
						onmouseout="javascript:viewlayer(false, 'moverlayerLock_<? echo $no?>');"><?=$str_lock_yn?></span>
					<? if($str_login_lock_type > ""){?>
					<div id="moverlayerLock_<? echo $no?>" class="viewlayer" style="display: none;">
						<? echo $str_login_lock_type;?>
					</div>
					<?}?>
				</td>




			</tr>
			<?php
			
				$no--;
			}
				
		}

		if($result) sqlsrv_free_stmt($result);  
		sqlsrv_close($wvcs_dbcon);
		if($total < 1) {
			
		?>
			<tr>
				<td colspan="10" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
		</table>


	</div>

</div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>