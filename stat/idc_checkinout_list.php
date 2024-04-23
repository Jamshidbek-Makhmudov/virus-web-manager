<?php
$page_name = "idc_checkinout_list";
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
$page = intval($_REQUEST[page]);			// 페이지
$paging = $_REQUEST[paging];		// 페이지
$start_date = $_REQUEST[start_date];	
$end_date = $_REQUEST[end_date];

$scan_center_code = $_REQUEST[scan_center_code];
$v_user_type =  $_REQUEST[v_user_type];
$idc_center =  $_REQUEST[idc_center];

if($paging == "") $paging = $_paging;

if($start_date=="") $start_date = date( "Y-m-d", strtotime( date("Y-m-d")." -1 month" ) );
if($end_date=="") $end_date = date("Y-m-d");

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
if($start_date!="") $param .= ($param==""? "":"&")."start_date=".$start_date;
if($end_date!="") $param .= ($param==""? "":"&")."end_date=".$end_date;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;
if ($v_user_type != "") $param .= ($param == "" ? "" : "&") . "v_user_type=" . $v_user_type;
if ($idc_center != "") $param .= ($param == "" ? "" : "&") . "idc_center=" . $idc_center;

//검색 로그 기록

$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_Stat = new Model_Stat();
$Model_manage = new Model_manage;
?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT['idccheckinoutdetails'][$lang_code]?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
		<input type="hidden" name="page" value="<?=$page?>">
		<input type='hidden' name='proc_name' id='proc_name'>	
		<table class="search">
			<tr>
			<th><?=$_LANG_TEXT['visitdatetext'][$lang_code]?> </th>
			<td>
				<input type="text" name="start_date" id="start_date" class="frm_input datepicker"  placeholder="" style="width:100px" value="<?=$start_date?>"  maxlength="10"> ~ <input type="text" name="end_date datepicker" id="end_date" class="frm_input" placeholder="" style="width:100px"  value="<?=$end_date?>"  maxlength="10">
				<!--  -->
					<div class='col head'><? echo trsLang('소속구분','belongdivtext');?></div>
						<div class='col'>
							<select name='v_user_type' id='v_user_type' style='max-width:150px;'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?
								foreach($_CODE_V_USER_TYPE as $key=>$name){
									echo "<option value='$key' ".($key==$v_user_type ? "selected" : "").">".$name."</option>";
								}
								?>
							<select>
						</div>

				<div class='col header'><? echo trsLang('검사장','scancentertext');?></div>
				<div class='col'>
						<select name='scan_center_code' id='scan_center_code'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<?php
							
							$result = $Model_manage->getCenterList();
							
							if($result){
								while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

									$_scan_center_code = $row['scan_center_code'];
									$_scan_center_name = $row['scan_center_name'];
						?>
							<option value='<?=$_scan_center_code?>' <?if($_scan_center_code==$scan_center_code) echo "selected" ;?>
								><?=$_scan_center_name?></option>
							<?php
								}
							}
						?>
						</select>
				</div>
					<div class='col head'><? echo trsLang('센터위치','centerpositiontext');?></div>
						<div class='col'>
							<select name='idc_center' id='idc_center'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?php
								$args = array("code_key"=>"IDC_CENTER", "use_yn"=>"Y", "search_sql"=>" and depth > 1 ");
								$result = $Model_manage->getCodeList($args);
								
								if($result){
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

										$_code_name = $row['code_name'];
							?>
								<option value='<?=$_code_name?>' <?if($_code_name==$idc_center) echo "selected" ;?>
									><?=$_code_name?></option>
								<?php
									}
								}
							?>
							</select>
						</div>
			</td>
		</tr>
		<?
		//검색키워드목록
	$searchopt_list = array(
					"v_user_name"=>trsLang("이름","nametext")
					,"v_user_belong"=>trsLang("소속","belongtext")
					,"elec_doc_number"=>trsLang("작업번호","worknumbertext")
					,"work_number"=>trsLang("작업번호","worknumbertext")."(".trsLang('인증','certify_text').")"
					,"confirmer"=>trsLang("확인자","confirmertext")
					// ,"confirmer"=>trsLang("확인자","confirmertext")."(".trsLang('이름','nametext').")"
				);
		?>
		<tr>
			<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?> </th>
			<td>
				<select name="searchopt" id="searchopt" style='max-width:150px;'>
					<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
					<?
					foreach($searchopt_list as $key=>$name){
						$selected = $searchopt==$key ? "selected" : "";
						echo "<option value='{$key}' {$selected} >{$name}</option>";
					}
					?>
				</select>

				<input type="text" class="frm_input" style="width:50%" name="searchkey" id="searchkey"  value="<?=$searchkey?>"  maxlength="50">

				<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit" onclick="return WorkLogSearchSubmit(document.searchForm);">
					<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/idc_checkinout_list.php'">

			</td>
		</tr>

		</table>

	
		<?php 	
		//검색항목
		//  $search_sql = "";
		 $search_sql = " and v2.v_type ='VISIT_IDC' ";
		if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

				if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
			}

			if($v_user_type != ""){
				$search_sql .= " and v2.v_user_type like '{$v_user_type}%'  ";
			}
			
			if($idc_center !=""){
				$search_sql .= " and CHARINDEX('{$idc_center}',v3.visit_center_desc) > 0  ";
			}
		  
		if($searchkey != ""){
			 if($searchopt=="v_user_name"){
						
						if($_encryption_kind=="1"){

							$search_sql .= "  AND dbo.fn_DecryptString(v2.v_user_name) like '%{?}%' or  v2.v_user_name_en like '%{$searchkey}%' ";

						}else if($_encryption_kind=="2"){

							$search_sql .= "  AND v2.v_user_name = '".aes_256_enc($searchkey)."' or  v2.v_user_name_en like '%{$searchkey}%' ";
						}
					}else if($searchopt=="v_user_belong"){
				$search_sql .= " AND v2.v_user_belong like '%{$searchkey}%' ";

			}else if($searchopt=="elec_doc_number"){
				$search_sql .= " AND v3.elec_doc_number like '%{$searchkey}%' ";

			}else if($searchopt=="work_number"){
				$search_sql .= " AND v3.work_number like '%{$searchkey}%' ";

			}else if($searchopt=="confirmer"){
				$search_sql .= " AND vi.access_emp_name like '%{$searchkey}%' or vi.access_emp_id like '%{$searchkey}%'  ";

			}

		}



		

			$args = array("search_sql" => $search_sql);
			$total = $Model_Stat->getUserVistInoutListCount_IDC($args);
			$rows = $paging;			// 페이지당 출력갯수
			$lists = $_list;			// 목록수
			$page_count = ceil($total / $rows);
			if (!$page || $page > $page_count) $page = 1;
			$start = ($page - 1) * $rows;
			$no = $total - $start;
			$end = $start + $rows;

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby";
		} else {
			$order_sql = " ORDER BY vi.v_user_list_inout_log_seq DESC ";
		}
									
      $args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
	  $Model_Stat->SHOW_DEBUG_SQL = false;

	  $result = $Model_Stat->getUserVistInoutList_IDC($args);
					
		$cnt = 20;
		$iK = 0;
		$classStr = "";


		
		  //excel file name while downloading
			$excel_name = $_LANG_TEXT['work_log'][$lang_code].'_('.$_LANG_TEXT['idccheckinoutdetails'][$lang_code].')';?>
		
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/stat/idc_checkinout_list_excel.php?enc=" . ParamEnCoding($param); ?>
				<div class="right">
					<a  href="javascript:void(0)" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
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

			<th style='width:250px'><? echo trsLang('소속구분','belongdivtext');?></th>
			<th class="center" style='min-width:80px'><?= $_LANG_TEXT['nametext'][$lang_code] ?></th>

			<th style='width:250px'><? echo trsLang('소속','belongtext');?></th>
			<th style='min-width:100px'><? echo trsLang('검사장','inspection_center');?></th>
					<th class="center" style='min-width:100px'><? echo trsLang('센터위치','centerpositiontext');?></th>
							<th class="center"  style='min-width:100px'><? echo trsLang('작업번호','worknumbertext')?></th>
				<th class="center"  style='min-width:100px'><? echo trsLang('작업번호','worknumbertext')?>(<? echo trsLang('인증','certify_text')?>)</th>
				<th class="center" style='width:90px'><? echo trsLang('방문일자','visitdatetext')?></th>
				<th class="center" ><? echo trsLang('상태','statustext');?></th>
				<th class="center" ><? echo trsLang('처리 시간','process_time');?></th>
				<th class="center" ><? echo trsLang('확인자 이름','verifier_name');?></th>
				<th class="center" ><? echo trsLang('확인자 아이디','verifier_id');?></th>


		</tr>
<?php

			if ($result) {
				while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

				$cnt--;
				$iK++;
				
				$v_user_list_seq = $row['v_user_list_seq'];
				$v_user_name = aes_256_dec($row['v_user_name']);
				$v_user_name_en = $row['v_user_name_en'];
				$in_center_name = $row['in_center_name'];
				$v_user_belong = $row['v_user_belong'];

				  $v_user_type = $row['v_user_type'];
					
					
					
					$str_v_user_type = $_CODE_V_USER_TYPE_DETAILS[$v_user_type];
					if($str_v_user_type=="") $str_v_user_type = $v_user_type;
					
					$visit_center_desc = $row['visit_center_desc'];
					$elec_doc_number = $row['elec_doc_number'];
					$work_number = $row['work_number'];
					$visit_date = setDateFormat($row['visit_date']);
					if($visit_date=="") $visit_date = setDateFormat($row['in_time']);
					$visit_status = strVal($row['visit_status']);
					if($visit_status=="") $visit_status = "9";	//입실대기
					
					$str_visit_status = $_CODE_VISIT_STATUS[$visit_status];
					
					
				  $access_date = setDateFormat($row['access_date']);
				  $access_emp_name = $row['access_emp_name'];
				  $access_emp_id = $row['access_emp_id'];


				

				if( $row['security_agree_yn']=="Y"){
				$security_agree_yn = "<a href='javascript:void(0)' onclick='popSecurityAgree()' data-seq='{$v_user_list_seq}' class='text_link'>".trsLang('작성완료','writeoktext')."</a>";
			}else{
				$security_agree_yn = trsLang('미작성','notwritten');

			};


			
			

		  ?>	
			<tr>
				<td><?php echo $no; ?></td>
				<td><?=$str_v_user_type?></td>
				<td><?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?></td>
				<td><?=$v_user_belong?></td>
				<td><?=$in_center_name?></td>
				<td><?=$visit_center_desc?></td>
				<td><?=$elec_doc_number?></td>
				<td><?=$work_number?></td>
				<td><?=$visit_date?></td>
				<td><?=$str_visit_status?></td>
				<td><?=$access_date?></td>
				<td><?=$access_emp_name?></td>
				<td><?=$access_emp_id?></td>

			</tr>
			<?php
			
				$no--;
			}
				
		}

			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);
				if($total < 1) {

			?>
			<tr>
				<td colspan="15" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
<div id='popContent' style='display:none;'></div>
<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>