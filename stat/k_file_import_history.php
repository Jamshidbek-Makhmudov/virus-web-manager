<?php
$page_name = "file_inspection_details";
// $page_name = "checkin_scan_log";
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

$paging = $_REQUEST[paging];

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

if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

?>

<style>
.btnexcel.loading {
        pointer-events: none; 
        cursor: not-allowed; 
    }

.spinner { width:2px; height:2px; line-height:11px; text-align:center; color:white; background:#397ecc url('../images/viewLoading.png') 5px center no-repeat }
</style>

<!-- bu kerak emas -->
<script language="javascript">
$(function() {
	$("#start_date").datepicker(pickerOpts);
	$("#end_date").datepicker(pickerOpts);
});

$(function() {
	$("#wrapper1").scroll(function() {
		$("#wrapper2").scrollLeft($("#wrapper1").scrollLeft());
	});
	$("#wrapper2").scroll(function() {
		$("#wrapper1").scrollLeft($("#wrapper2").scrollLeft());
	});

	window.onresize = function(event) {
		var w = $("#tblList").width();
		$("#div1").width(w);
	};
});

//loading:

</script>

<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["file_inspection_details"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="GET">
		<input type='hidden' name='proc_name' id='proc_name'>

			<input type="hidden" name="page" value="">
			<table class="search">
				<tr>
					<th style='widht:100px;'><?= $_LANG_TEXT['searchperiod'][$lang_code] ?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input" placeholder="" style="width:100px" value="<?= $start_date ?>" maxlength="10"> ~ 
						<input type="text" name="end_date" id="end_date" class="frm_input" placeholder="" style="width:100px" value="<?= $end_date ?>" maxlength="10">
					</td>
				</tr>
				<tr>
					<th><?= $_LANG_TEXT['usersearchtext'][$lang_code] ?> </th>
					<td style='padding:5px 13px'>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if ($searchopt == "") {
																	echo ' selected="selected"';
																} ?>>
								<?= $_LANG_TEXT['select_search_item'][$lang_code] ?></option>
							<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																					echo ' selected="selected"';
																				} ?>>
								<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
							<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																				echo ' selected="selected"';
																			} ?>>
								<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
							<option value="IP" <?php if ($searchopt == "IP") {
																		echo ' selected="selected"';
																	} ?>>
								<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="50">
						<input type="submit" value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
						<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
						
						<!--상세검색-->
						<div id='search_detail' style='display:none;'>
							<? for($i = 1 ; $i < 4 ; $i++){?>
							<div  style='margin-top:5px;'>
								<select  name="searchopt<? echo $i?>"  id="searchopt<? echo $i?>">
									<option value="" <?php if ($searchopt == "") {
																			echo ' selected="selected"';
																		} ?>>
										<?= $_LANG_TEXT['select_search_item'][$lang_code] ?></option>
									<option value="EMP_NAME" <?php if ($searchopt == "EMP_NAME") {
																							echo ' selected="selected"';
																						} ?>>
										<?= $_LANG_TEXT['empnametext'][$lang_code] ?></option>
									<option value="EMP_NO" <?php if ($searchopt == "EMP_NO") {
																						echo ' selected="selected"';
																					} ?>>
										<?= $_LANG_TEXT['empnotext'][$lang_code] ?></option>
									<option value="IP" <?php if ($searchopt == "IP") {
																				echo ' selected="selected"';
																			} ?>>
										<?= $_LANG_TEXT['ipaddresstext'][$lang_code] ?></option>
								</select>
								<input style="width:50%" type="text" class="frm_input" name="searchkey<? echo $i?>" id="searchkey<? echo $i?>" maxlength="50">
								<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>">
									<option value='AND'>AND</option>
									<option value='OR'>OR</option>
								</select>
							</div>
							<?}?>
						</div>
					</td>
				</tr>
			</table>

			<?php 
// 		$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; 
	
// 			if($start_date != "" && $end_date != ""){
// 		$search_sql .= " AND wvcs_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
// 	}
// if($searchkey != "" && $searchopt != "") {
	
// 	if($searchopt=="v_user_name"){ 

// 		$search_sql .= " and v_user_name like '$searchkey%' "; 

// 	}else if($searchopt=="file_name_org"){ 
// 		$search_sql .= " and file_name_org like '%$searchkey%' ";
	
	
// 	}else if($searchopt=="file_ext"){

// 		$search_sql .= " and file_ext like '%$searchkey%' ";
// 	} else if($searchopt=="file_id"){
// //check this : f1.[file_id]
// 		$search_sql .= " and file_id like '%$searchkey%' ";
// 	} 
	
// }
// 				$data = array("search_sql"=>$search_sql);
// 				$model_inquery= new Model_result();
// 				$total=$model_inquery->getVCSScanListCount($data);
// 				// echo $total;

			

// 				$rows = $paging;			// 페이지당 출력갯수
// 				$lists = $_list;			// 목록수
// 				$page_count = ceil($total/$rows);
// 				if(!$page || $page > $page_count) $page = 1;
// 				$start = ($page-1)*$rows;
// 				$no = $total-$start;
// 				$end = $start + $rows;

// 				if($orderby != "") {
// 					$order_sql = " ORDER BY $orderby";
// 				} else {
// 					$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; //new added
		
// 				}	

// 				$data = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
// 				$VCSScanList= new Model_result();	
				
// 				$result = $VCSScanList->getVCSScanList($data);

// 				$cnt = 20;
// 				$iK = 0;
// 				$classStr = "";
		?>
			<div class="btn_wrap right">

			</div>
	
			<div class="btn_wrap right">
				<? $excel_down_url = $_www_server."/stat/file_import_history_excel.php?enc=".ParamEnCoding($param);?>
				<div class="right">
					<a href="#" id="james2" class="btnexcel required-print-auth hide"
						onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
				</div>
			</div>
			<div style='line-height:30px; margin-top:12px; margin-right:10px;' class="right">
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
		<div id='wrapper1' class="wrapper">
			<div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
			<table id='tblList' class="list" style="margin-top:10px">
				<tr>

					<th style='min-width:60px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
					<th style='min-width:120px'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
					<th style='min-width:120px'><?=$_LANG_TEXT['inspection_date'][$lang_code]?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT['filepathtext'][$lang_code]?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT["filenametext"][$lang_code];?></th>
					<th style='min-width:90px'><?=$_LANG_TEXT["filesizetext"][$lang_code];?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT["confirmed_person"][$lang_code];?></th>
					<th style='min-width:120px'><?=$_LANG_TEXT["filesignature"][$lang_code];?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT['m_result'][$lang_code]?></th>
					<th style='min-width:130px'><?=$_LANG_TEXT['carry_in_status'][$lang_code]?></th>
					<th style='min-width:60px'><?=$_LANG_TEXT['server_transfer_status_text'][$lang_code]?></th>
					<th style='min-width:100px' class='num_last'><?=$_LANG_TEXT['fileidnntext'][$lang_code]?></th>
					<th style='min-width:100px' class='num_last'><?=$_LANG_TEXT['deleteyntext'][$lang_code]?></th>
				</tr>
				<?php


				// if($result){
				//   while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

				// 		$cnt--;
				// 		$iK++;
						
				// 		$v_user_name = $row['v_user_name'];

				// 		$wvcs_dt = $row['wvcs_dt'];
						
				// 		$formatted_date = $wvcs_dt->format('Y-m-d H:i');

				// 		$file_path = $row['file_path'];
				// 		$file_name_org = $row['file_name_org'];
				// 		$file_size = $row['file_size'];
				// 		$file_ext = $row['file_ext'];

				// 		$file_signature = $row['file_signature'];
				// 		$file_scan_result  = $row['file_scan_result'];

				// 		//seq id
				// 		$v_wvcs_file_seq  = $row['v_wvcs_file_seq'];
				// 		//반입여부
				// 		$v_wvcs_file_in_seq  = $row['v_wvcs_file_in_seq'];
				// 		if($v_wvcs_file_in_seq>0){
				// 	    $bring_in = "<font >".$_LANG_TEXT['intext'][$lang_code]."<font>";
				//     }else{
				//      	$bring_in = $_LANG_TEXT['nointext'][$lang_code];
				//     }
				// 		//서버전송여부
				// 		$file_send_status  = $row['file_send_status'];
				// 		if($file_send_status==1){
				// 	    $send_server = "<font >".$_LANG_TEXT['send_server'][$lang_code]."<font>";
				//     }else{
				//      	$send_server = $_LANG_TEXT['notsend_server'][$lang_code];
				//     }
				// 		$file_id  = $row['file_id'];
				// 		//삭제여부
				// 		$file_delete_flag  = $row['file_delete_flag'];
				// 		if($file_delete_flag==1){
				// 	    $delete_flag = "<font >o<font>";
				//     }else{
				//      	$delete_flag = "X";

				//     }

				  ?>
				<tr>
	<td>1</td>
	<td>이적</td>
	<td>2023</td>
	<td>w:user</td>
	<td><a class='text_link' href="#">Abc.txt</a></td>
	<td>2kb</td>
	<td>txt</td>
	<td>7bit</td>
	<td>정상</td>
	<td>반입</td>
	<td>전송</td>
	<td>1002</td>
	<td>x</td>

				</tr>
				<?php
					
					
				// 		 $no--;
				// 	}
						
				// }



				// if($result) sqlsrv_free_stmt($result);  
				// if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
				// if($total < 1) {
			
					
				?>
				<!-- <tr>
					<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
				</tr> -->
				<?php
				//}
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
<!-- check -->
<!-- <div id='popContent' style='display:none'></div> -->
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>