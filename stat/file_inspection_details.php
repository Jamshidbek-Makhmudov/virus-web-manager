<?php
$page_name = "file_inspection_details";

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
$scan_center_code = $_REQUEST[scan_center_code];
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

<script language="javascript">
	$("document").ready(function(){
		var w = $("#tblList").width();
		$("#div1").width(w);
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
				 <h1><span id='page_title'><?=$_LANG_TEXT["file_inspection_details"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
			<input type='hidden' name='proc_name' id='proc_name'>

			<input type="hidden" name="page" value="">
			<table class="search">
				<!--  -->
				<tr>
					<th><?=$_LANG_TEXT['file_inspection_period'][$lang_code]?> </th>
					<td>
						<input type="text" name="start_date" id="start_date" class="frm_input datepicker" placeholder="" style="width:100px"
							value="<?=$start_date?>" maxlength="10"> ~ <input type="text" name="end_date" id="end_date"
							class="frm_input datepicker" placeholder="" style="width:100px" value="<?=$end_date?>" maxlength="10">
						<div class="col head">
							<? echo trsLang('검사장','scancentertext');?>
						</div>
						<div class="col">
							<select name='scan_center_code' id='scan_center_code'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?php
								$Model_manage = new Model_manage;
								$result = $Model_manage->getCenterList();
								
								if($result){
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

										$_scan_center_code = $row['scan_center_code'];
										$_scan_center_name = $row['scan_center_name'];

										if($_scan_center_code==$scan_center_code){
											$selected = "selected";
											$search_scan_center_name = $_scan_center_name;
										}else{
											$selected = "";
										}
								?>
								<option value='<?=$_scan_center_code?>' <? echo $selected ;?>
									><?=$_scan_center_name?></option>
								<?php
									}
								}
								?>
							</select>
						</div>
					</td>
				</tr>
				<!--  -->
				<tr>
					<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
					<td>
						<?
							//검색키워드목록
							$searchopt_list = array(
								"v_wvcs_seq"=>trsLang("점검번호","scannumber")
								,"v_user_name"=>trsLang("이름","nametext")
								,"v_user_belong"=>trsLang("소속","belongtext")
								,"v_purpose"=>trsLang("방문목적","purpose_visit")
								,"file_name_org"=>trsLang("파일명","filenametext")
								,"file_ext"=>trsLang("확장자","file_ext")
								,"file_id"=>trsLang("파일ID","fileidnntext")
								,"md5"=>trsLang("파일해시","filehash")."(MD5)"
							);
						?>
						<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
							<?
							foreach($searchopt_list as $key=>$name){
								$selected = $searchopt==$key ? "selected" : "";
								echo "<option value='{$key}' {$selected} >{$name}</option>";
							}
							?>
						</select>

						<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"
							value="<?=$searchkey?>" maxlength="50">

						<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit"
							onclick="return SearchSubmit(document.searchForm);">

					</td>
				</tr>
			</table>

			<?php 
			$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; 

			if($start_date != "" && $end_date != ""){
					$search_sql .= " AND wvcs_dt between '$start_date 00:00:00.000' and '$end_date 23:59:59.999' ";
			}

			if($scan_center_code != ""){
				$search_sql .= " AND cn.scan_center_code = '{$scan_center_code}' ";
			}
		
			if($searchkey != "" && $searchopt != "") {
				
				if($searchopt=="v_user_name"){ 

					$search_sql .= " and v4.v_user_name = '".aes_256_enc($searchkey)."'"; 

				}else if($searchopt=="v_user_belong"){ 

					$search_sql .= " and v4.v_user_belong like '{$searchkey}%' ";

				}else if($searchopt=="v_purpose"){ 

					$search_sql .= " and v4.v_purpose like '{$searchkey}%' ";

				}else if($searchopt=="v_wvcs_seq"){ 

					$search_sql .= " and v1.v_wvcs_seq = '{$searchkey}' ";

				}else if($searchopt=="file_name_org"){ 

					$search_sql .= " and file_name_org like N'$searchkey%' ";
				
				}else if($searchopt=="file_ext"){

					$search_sql .= " and file_ext like '$searchkey%' ";
				} else if($searchopt=="file_id"){
					$search_sql .= " and file_id like '$searchkey%' ";
				} else if($searchopt=="md5"){
					$search_sql .= " and md5 = '$searchkey' ";
				}
				
			}
				$args = array("search_sql"=>$search_sql);
				$model_inquery= new Model_result();
				 
				$total=$model_inquery->getVCSScanListCount($args);
				// echo $total;

			

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
					$order_sql = " ORDER BY v1.v_wvcs_seq DESC "; //new added
		
				}	

				$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
				$VCSScanList= new Model_result();	
				// $VCSScanList->SHOW_DEBUG_SQL = true;
				$result = $VCSScanList->getVCSScanList($args);

				$cnt = 20;
				$iK = 0;
				$classStr = "";
		?>
				<?php 
		$excel_name=$_LANG_TEXT['file_inspection_details'][$lang_code];

		?>
	
		<div class="btn_wrap right " style=''>
				<? $excel_down_url = $_www_server."/stat/file_inspection_details_excel.php?enc=".ParamEnCoding($param);?>
				<div class="right">
<a href="#" id="rental_details_excel" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
				</div>
				<div style='margin-right:10px; line-height:40px; ' class="right">
					Results : <span style='color:blue'><?=number_format($total)?></span> /
					Records : <select  name='paging' onchange="searchForm.submit();">
						<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
						<option value='40' <?if($paging=='40' ) echo "selected" ;?>>40</option>
						<option value='60' <?if($paging=='60' ) echo "selected" ;?>>60</option>
						<option value='80' <?if($paging=='80' ) echo "selected" ;?>>80</option>
						<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
					</select>
				</div>

			</div>


		</form>

		<!--검색결과리스트-->
		<div id='wrapper1' class="wrapper">
			<div id='div1' style='height:1px;'></div>
		</div>
		<div id='wrapper2' class="wrapper">
			<table id='tblList' class="list" style="margin-top:0px;margin:0px;auto; white-space: nowrap;" >
				<tr>
					<th style='min-width:60px'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
					<th style='min-width:60px'><? echo trsLang('점검번호','scannumber');?></th>
					<th style='min-width:100px'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
					<th style='min-width:150px'><? echo trsLang('소속','belongtext');?></th>
					<th style='min-width:150px'><? echo trsLang('방문목적','purpose_visit');?></th>
					<th style='min-width:100px'><? echo trsLang('검사장','scancentertext');?></th>
					<th style='min-width:120px'><?=$_LANG_TEXT['inspection_date'][$lang_code]?></th>
					<th style='min-width:250px'><?=$_LANG_TEXT['filepathtext'][$lang_code]?></th>
					<th style='min-width:200px'><?=$_LANG_TEXT["filenametext"][$lang_code];?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT["filesizetext"][$lang_code];?></th>
					<th style='min-width:80px'><? echo trsLang('확장자','file_ext');?></th>
					<th style='min-width:80px'><? echo trsLang('파일해시','filehash');?>(md5)</th>
					<th style='min-width:250px'><?=$_LANG_TEXT["filesignature"][$lang_code];?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT['m_result'][$lang_code]?></th>
					<th style='min-width:60px'><?=$_LANG_TEXT['carry_in_status'][$lang_code]?></th>
					<th style='min-width:80px'><?=$_LANG_TEXT['server_transfer_status'][$lang_code]?></th>
					<th style='min-width:60px;' ><?=$_LANG_TEXT['deleteyntext'][$lang_code]?></th>
				</tr>
				<?php


				if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$cnt--;
						$iK++;
						
						$v_user_name = aes_256_dec($row['v_user_name']);

						$wvcs_dt = $row['wvcs_dt'];
						
						$formatted_date = $wvcs_dt->format('Y-m-d H:i');

						$file_path = $row['file_path'];
						$file_name_org = $row['file_name_org'];
						$file_size = $row['file_size'];
						$file_ext = $row['file_ext'];

						$file_signature = $row['file_signature'];
						$file_scan_result  = $row['file_scan_result'];

						$scan_center_name = $row['scan_center_name'];
						$v_user_belong = $row['v_user_belong'];
						$v_wvcs_seq = $row['v_wvcs_seq'];
						$v_purpose = $row['v_purpose'];


						//점검결과
						if ($file_scan_result == "BAD_EXT") {
							$m_result = "<font >" . $_LANG_TEXT['suspectforgerytext'][$lang_code] . "<font>";
						} else if ($file_scan_result == "VIRUS") {
							$m_result = "<font >" . $_LANG_TEXT['virustext'][$lang_code] . "<font>";
						} else {
							$m_result = "<font >" . $_LANG_TEXT['cleantext'][$lang_code] . "<font>";
						}

						$md5 = $row['md5'];

						//seq id
						$v_wvcs_file_seq  = $row['v_wvcs_file_seq'];
						//반입여부
						$v_wvcs_file_in_seq  = $row['v_wvcs_file_in_seq'];
						if($v_wvcs_file_in_seq>0){
					    $bring_in = "<font >".$_LANG_TEXT['intext'][$lang_code]."<font>";
				    }else{
				     	$bring_in = $_LANG_TEXT['nointext'][$lang_code];
				    }
						//서버전송여부
						$file_send_status  = $row['file_send_status'];
						$file_send_date = $row['file_send_date'];
						if ($file_send_status == "1" && $file_send_date != "") {
					    $send_server = "<font >".$_LANG_TEXT['send_server'][$lang_code]."<font>";
				    }else{
				     	$send_server = $_LANG_TEXT['notsend_server'][$lang_code];
				    }
						$file_id  = $row['file_id'];
						//삭제여부
						$file_delete_flag  = $row['file_delete_flag'];
						if($file_delete_flag==1){
					    $delete_flag = "<font >o<font>";
				    }else{
				     	$delete_flag = "X";

				    }

				  ?>
				<tr>
					<td><?php echo $no; ?></td>
					<td><?=$v_wvcs_seq?></td>
					<td><?=$v_user_name?></td>
					<td><?=$v_user_belong?></td>
					<td><?=$v_purpose?></td>
					<td><?=$scan_center_name?></td>
					<td><?=$formatted_date?></td>
					<td style='text-align:left'><?=$file_path?></td>
					<td style='text-align:left'><?=$file_name_org?></td>
					<td><?=formatBytes($file_size)?></td>

					<td><?=$file_ext?></td>
					<td><?=$md5?></td>
					<td><?=$file_signature?></td>
					<td><?=$m_result?></td>
					<!-- seq -->
					<!-- <td><? //=$v_wvcs_file_seq?></td> -->
					<td><?=$bring_in?></td>

					<td><?=$send_server?></td>
					<!--<td><?=$file_id?></td>-->
					<td><?=$delete_flag?></td>

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
<!-- check -->
<!-- <div id='popContent' style='display:none'></div> -->
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>