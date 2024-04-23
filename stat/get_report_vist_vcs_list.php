<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$start_date = $_REQUEST[start_date];
$end_date = $_REQUEST[end_date];
$scan_center_code = $_REQUEST[scan_center_code];
$paging = "99999";

$param = "";
if($start_date!="") $param .= ($param==""? "":"&")."start_date=".$start_date;
if($end_date!="") $param .= ($param==""? "":"&")."end_date=".$end_date;
if($scan_center_code!="") $param .= ($param==""? "":"&")."scan_center_code=".$scan_center_code;

$search_sql = "";
if($start_date != "" && $end_date != ""){

	$str_start_date = preg_replace("/[^0-9]*/s", "", $start_date)."000000";
	$str_end_date = preg_replace("/[^0-9]*/s", "", $end_date)."235959";

	$search_sql .= " AND vl.in_time between '{$str_start_date}' and '{$str_end_date}' ";
}

if($scan_center_code != "" ){
	$search_sql .= " AND vl.in_center_code = '{$scan_center_code}'  ";
}

$Model_result = new Model_result();
$args = array("search_sql"=> $search_sql);

$total = $Model_result->getVCSListCount($args);

$rows = $paging;			// 페이지당 출력갯수
$lists = $_list;			// 목록수
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
$end = $start + $rows;

$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";

$args = array(
	"end"=> $end
	,"start"=>$start
	,"order_sql"=>$order_sql
	,"search_sql"=> $search_sql
);

$Model_result->SHOW_DEBUG_SQL = false;
$result = $Model_result->getVCSList($args);

?>
<div class="wrapper">
	<div style='float:right'>
		<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){?>
		<img src="<? echo $_www_server?>/images/b_clean.png"> <? echo trsLang('위변조의심','suspectforgerytext');?>
		<?}?>
		<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){?>
		<img src="<? echo $_www_server?>/images/v_clean.png"> <? echo $_LANG_TEXT["viruscleantext"][$lang_code];?>
		<?}?>
		<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)){?>
		<img src="<? echo $_www_server?>/images/w_clean.png"> <? echo $_LANG_TEXT["weaknesscleantext"][$lang_code];?>
		<?}?>
	</div>
</div>
<table id='tblUsrCheckList' class="list" style="margin-top:0px;min-width:1100px;" >
<tr>
	<th style='min-width:60px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
	<th style='min-width:120px' ><? echo $_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
	<th style='min-width:130px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<th style='min-width:80px' ><? echo trsLang('담당자','managertext');?></th>
	<th style='min-width:80px' ><? echo trsLang('담당부서','managedepartmenttext');?></th>
	<th style='min-width:80px' ><? echo trsLang('전자문서번호','electronic_payment_document_number');?></th>
	<th style='min-width:80px' >USB <? echo trsLang('관리번호','managenumber');?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
	<th style='min-width:70px' ><?=$_LANG_TEXT["scanfilecount"][$lang_code];?></th>
	<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
	<th style='min-width:70px' ><?=$_LANG_TEXT["importfilecount"][$lang_code];?></th>
	<?}?>
</tr>

<?

 if($result){
  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$v_wvcs_seq = $row['v_wvcs_seq'];

		$check_date = $row['check_date'];
		$in_available_date  = $row['checkin_available_dt'];
		
		if($in_available_date){

			$hour = substr($in_available_date,8,2);
			$min = substr($in_available_date,10,2);

			$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
			
			$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
			
		}
		$in_date	= $row['in_date'];
		$out_date = $row['out_date'];
		
		$v_user_seq = $row['v_user_seq'];
		$v_user_list_seq = $row['v_user_list_seq'];
		$v_asset_type = $row['v_asset_type'];
		$v_scan_center_name = $row['scan_center_name'];
		$sys_sn = $row['v_sys_sn'];
		$manager_dept = $row['manager_dept'];
		$manager_name = aes_256_dec($row['manager_name']);
		$manager_name_en = $row['manager_name_en'];
		$v_user_name = aes_256_dec($row['v_user_name']);
		$v_user_sq = $row['v_user_seq'];
		$weak_cnt = $row['weak_cnt'];
		$virus_cnt = $row['virus_cnt'];
		$wvcs_authorize_yn = $row['wvcs_authorize_yn'];
		$vacc_scan_count = $row['vacc_scan_count'];	//바이러스검사파일
		$file_bad_cnt = $row['file_bad_cnt'];		
		$scan_file_cnt = $row['scan_file_cnt'];
		$os_ver_name = $row['os_ver_name'];

		$v_user_belong = $row['v_user_belong'];
		$usb_mgt_no = $row['label_value'];
		$elec_doc_number = $row['elec_doc_number'];

		if($manager_name_en==""){
			$str_manager_name = $manager_name;
		}else{
			$str_manager_name = $manager_name." (".$manager_name_en.")";
		}
		
		//파일정보를 서버로 전송하는 경우는 바이러스 검사파일수 대신 전송된 파일정보수를 표시해 준다.
		if($scan_file_cnt > 0){
			$vacc_scan_count = $scan_file_cnt;
		}

		$disk_cnt = $row['disk_cnt'];
		$import_file_cnt = $row['import_file_cnt'];
		
		$param_enc = ParamEnCoding("v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

		$check_result = "";
		
		//위변조의심
		if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){

			if($file_bad_cnt > 0){
				$check_result .="<img src='".$_www_server."/images/b_clean.png'>";
			}else{
				$check_result .="<img src='".$_www_server."/images/c_clean.png'>";
			}
		
		}

		if(in_array("WEAK",$_CODE_INSPECT_OPTION)){
		
			if($weak_cnt > 0){
				$check_result .="<img src='".$_www_server."/images/w_clean.png'>";
			}else{
				$check_result .="<img src='".$_www_server."/images/c_clean.png'>";
			}

		}

		if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){

			if($virus_cnt > 0){

				$check_result .="<img src='".$_www_server."/images/v_clean.png'>";
			}else{
				$check_result .="<img src='".$_www_server."/images/c_clean.png'>";
			}

		}

		if($_encryption_kind=="1"){

			$phone_no = $row['v_phone_decript'];
			$email = $row['v_email_decript'];

		}else if($_encryption_kind=="2"){

			$phone_no = aes_256_dec($row['v_phone']);
			$email = aes_256_dec($row['v_email']);
		}

		$vcs_status = $row['vcs_status'];
		$str_vcs_status = $_CODE['vcs_status'][$vcs_status];

		$view_param_enc = ParamEnCoding("view_src=RESULT_LIST&page=".$page."&v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

		if($v_asset_type=='NOTEBOOK'){
			$view_url = './result_view_pc.php?enc='.$view_param_enc;
		}else{
			$view_url = './result_view_storage.php?enc='.$view_param_enc;
		}
		
		//점검결과상세보기에서 현재 보고 있는 목록을 표시해 준다.
		if($view_v_wvcs_seq==$v_wvcs_seq){
			$view_row = "background-color:#e7e7e7;";
		}else $view_row = "";
		
  ?>	
	<tr style='<? echo $view_row;?>'>
		<td><?php echo $no; ?></td>
		<td><?=$check_date?></td>
		<td class="cls_cfg_inout_info"><span name='in_date'><?=$in_date?></span></td>
		<td class="cls_cfg_inout_info"><span name='out_date'><?=$out_date?></span></td>
		<td><?=$v_scan_center_name?></td>
		<td><?=$os_ver_name?></td>
		<td><?=$sys_sn?></td>
		<td><?=$str_manager_name?></td>
		<td><?=$manager_dept?></td>
		<td><?=$elec_doc_number?></td>
		<td><?=$usb_mgt_no?></td>
		<td><?=$str_vcs_status?></td>
		<td><?=$check_result?></td>
		<td ><?=number_format($scan_file_cnt);?></td>
		<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
		<td  ><?=number_format($import_file_cnt);?></td>
		<?} ?>
	</tr>
	<?php
	
		$no--;
	}
	
}

if($total < 1) {
	
?>
	<tr>
		<td colspan="15" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
	</tr>
<?php
}
?>				
		
</table>