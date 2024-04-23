<?php
$page_name = "checkin_scan_log";
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
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_checkinscanlog"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
		<input type='hidden' name='proc_name' id='proc_name'>
		<input type="hidden" name="page" value="">	
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT['scanperiodtext'][$lang_code]?> </th>
			<td>
				<input type="text" name="start_date" id="start_date" class="frm_input"  placeholder="" style="width:100px" value="<?=$start_date?>"  maxlength="10"> ~ <input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px"  value="<?=$end_date?>"  maxlength="10">
			</td>
		</tr>
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['searchkeywordselecttext'][$lang_code]?></option>
					<option value="BARCODE" <?php if($searchopt == "BARCODE") { echo ' selected="selected"'; } ?>>Barcode</option>
					<option value="USER" <?php if($searchopt == "USER") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['visitortext'][$lang_code]?></option>
					<option value="EMP_NAME" <?php if($searchopt == "EMP_NAME") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['barcodescanemptext'][$lang_code]?></option>
				</select>

				<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"  value="<?=$searchkey?>"  maxlength="50">

				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">

			</td>
		</tr>
		</table>

		<div class="btn_wrap">
			<? $excel_down_url = $_www_server."/result/checkin_scan_log_excel.php?enc=".ParamEnCoding($param);?>
			<div class="right">
				<a href="#" id='btnexcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnexcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>
		</div>

		
		</form>

		<!--검색결과리스트-->
		<div id='wrapper1' class="wrapper">
		  <div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
			<table id='tblList' class="list"  style="margin-top:10px">
				<tr>
					<th style='min-width:60px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
					<th style='min-width:120px'>Barcode</th>
					<th style='min-width:120px' ><?=$_LANG_TEXT['visitortext'][$lang_code]?></th>
					<th style='min-width:80px' ><?=$_LANG_TEXT['checkdatetext'][$lang_code]?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
					<th style='min-width:90px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
					<th style='min-width:80px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
					<th style='min-width:120px'><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT['barcodescanemptext'][$lang_code]?></th>
					<th style='min-width:130px'><?=$_LANG_TEXT['barcodescandatetext'][$lang_code]?></th>
					<th style='min-width:60px'><?=$_LANG_TEXT['checkresulttext'][$lang_code]?></th>
					<th style='min-width:220px' class='num_last'><?=$_LANG_TEXT['scanresulttext'][$lang_code]?></th>
				</tr>
		<?php

			if($start_date != "" && $end_date != ""){
				$search_sql .= " AND lg.create_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
			}
			  
			if($searchkey != ""){

				if($searchopt=="EMP_NAME"){

					$search_sql .= " and emp_name = '".aes_256_enc($searchkey)."' ";

				}else if($searchopt == "BARCODE"){

					$search_sql .= " and lg.barcode like '%$searchkey%' ";

				}else if($searchopt == "USER"){

					
					if($_cfg_user_identity_name=="phone"){
						
						$searchkey = preg_replace("/[^0-9-]*/s", "", $searchkey); 

					  if($_encryption_kind=="1"){

						 $search_sql .= "and dbo.fn_DecryptString(us.v_phone) like '%$searchkey%' ";

					  }else if($_encryption_kind=="2"){

						 $search_sql .= " and us.v_phone = '".aes_256_enc($searchkey)."' ";
					  }

					}else if($_cfg_user_identity_name=="email"){
						
							if($_encryption_kind=="1"){

								$search_sql .= "and dbo.fn_DecryptString(us.v_email) like '%$searchkey%' ";

							}else if($_encryption_kind=="2"){

								$search_sql .= " and us.v_email = '".aes_256_enc($searchkey)."' ";
							}

					}else{

						$search_sql .= " and us.v_user_name = '".aes_256_enc($searchkey)."' ";
					}

				}
			}


				$qry_params = array("search_sql"=>$search_sql);
				$qry_label = QRY_VCS_SCANLOG_LIST_COUNT;
				$sql = query($qry_label,$qry_params);

				//echo nl2br($sql);

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
					$order_sql = " ORDER BY scan_log_seq DESC ";
				}

											
				$qry_params = $qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
				$qry_label = QRY_VCS_SCANLOG_LIST;
				$sql = query($qry_label,$qry_params);

				$result =@sqlsrv_query($wvcs_dbcon, $sql);

				//echo nl2br($sql);
				
				$cnt = 20;
				$iK = 0;
				$classStr = "";

				if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$cnt--;
						$iK++;
						
						$emp_name = aes_256_dec($row['emp_name']);
						$barcode = $row['barcode'];
						$scan_log_seq = $row['scan_log_seq'];
						$create_dt = $row['create_dt'];
						$scan_result_msg = $row['scan_result_msg'];
						$v_wvcs_seq = $row['v_wvcs_seq'];

						$check_date = $row['check_date'];
						$in_available_date  = $row['checkin_available_dt'];
						if($in_available_date){
							$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
						}
						$in_date	= $row['in_date'];
						
						$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
						$v_asset_type = $row['v_asset_type'];
						$sys_sn = $row['v_sys_sn'];
						$hdd_sn = $row['v_hdd_sn'];
						$board_sn = $row['v_board_sn'];
						$v_notebook_key = $row['v_notebook_key'];
						$os = $row['os_ver_name'];
						$maker = $row['v_manufacturer'];
						$mngr_dept = $row['mngr_department'];
						$mngr_name = aes_256_dec($row['mngr_name']);
						$vv_user_name = aes_256_dec($row['v_user_name']);
						$v_com_name = $row['v_com_name'];
						$vv_user_sq = $row['v_user_seq'];
						$weak_cnt = $row['weak_cnt'];
						$virus_cnt = $row['virus_cnt'];
						$wvcs_authorize_yn = $row['wvcs_authorize_yn'];

						$check_type = $row['wvcs_type'];

						if($_encryption_kind=="1"){

							$phone_no = $row['v_phone_decript'];
							$email = $row['v_email_decript'];

						}else if($_encryption_kind=="2"){

							$phone_no = aes_256_dec($row['v_phone']);
							$email = aes_256_dec($row['v_email']);
						}

						if($_cfg_user_identity_name=="phone"){
							$user_name_com = $phone_no;
							$vv_user_name= $phone_no;
						}else if($_cfg_user_identity_name=="email"){
							$user_name_com =$email;
							$vv_user_name= $email;
						}else{
							if($v_com_name=="-") $v_com_name="";
							$user_name_com = $vv_user_name.($v_com_name? "/" : "").$v_com_name;
						}

						$mngr = aes_256_dec($row['mngr_name']).($row['mngr_department']? " / " :"").$row['mngr_department'];


						if($wvcs_authorize_yn=="Y"){
							$vcs_status = $_LANG_TEXT["incompletetext"][$lang_code];
						}else{
							$vcs_status = $_LANG_TEXT["needchecktext"][$lang_code];
						}
						

				  ?>	
					<tr>
						<td><?php echo $no; ?></td>
						<td><?=$barcode?></td>
						<td><?=$user_name_com?></td>
						<td><?=$check_date?></td>
						<td><?=$v_scan_center_name?></td>
						<td><?=$check_type?></td>
						<td><?=$_CODE['asset_type'][$v_asset_type]?></td>
						<td><?=$os?></td>
						<td><?=$emp_name?></td>
						<td><?=$create_dt?></td>
						<td><?if($v_wvcs_seq){?><a href="javascript:" onClick="return popUserVcsView('<?=$v_wvcs_seq?>');" class='btn20 gray'><?=$_LANG_TEXT["btnview"][$lang_code]?></a><?}?></td>
						<td class='num_last'><?=$scan_result_msg?></td>
					</tr>
					<?php
					
						$no--;
					}
						
				}

				if($result) sqlsrv_free_stmt($result);  
				if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
				if($total < 1) {
					
				?>
					<tr>
						<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
					</tr>
				<?php
				}
				?>						
			</table>

		</div>

			<!--페이징-->
			<?php
			if($total > 0) {
				$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
				print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
			}
			?>
		

	</div>

</div>

<div id='popContent' style='display:none'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>