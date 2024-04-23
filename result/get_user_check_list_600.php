<?php
if(!$wvcs_dbcon) return;

/*
$src : 호출하는 페이지
1.USER_INFO_VIEW : /user/user_info_view.php
2.USER_VCS_LOG : /result/pop_user_check_log.php
3.RESULT_VIEW : /result/result_view_storage.php(/result/result_view_pc.php)
4.COM_INFO_VIEW : /user/com_info_view.php
5.VISIT_INFO_VIEW : /user/access_info.php
*/

//print_r($_REQUEST);

$src = $_REQUEST[src];
$v_user_list_seq = $_REQUEST[v_user_list_seq];
$view_v_wvcs_seq = $_REQUEST[view_v_wvcs_seq];
$v_user_seq = $_REQUEST[v_user_seq];
$v_asset_type = $_REQUEST[v_asset_type];
$v_notebook_key = $_REQUEST[v_notebook_key];
$storage_device_type = $_REQUEST[storage_device_type];
$check_result1 = $_REQUEST[check_result1];
$check_result2 = $_REQUEST[check_result2];
$orderby = $_REQUEST[orderby];		
$page = $_REQUEST[page];

if($src=="RESULT_VIEW"){
	$paging = 5;
}else{
	$paging = 15;
}
if($paging == "") $paging = $_paging;

//echo $src;

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($v_user_list_seq!="") $param .= ($param==""? "":"&")."v_user_list_seq=".$v_user_list_seq;
if($v_user_seq!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_user_seq;
if($v_asset_type!="") $param .= ($param==""? "":"&")."v_asset_type=".$v_asset_type;
if($v_notebook_key!="") $param .= ($param==""? "":"&")."v_notebook_key=".$v_notebook_key;
if($storage_device_type!="") $param .= ($param==""? "":"&")."storage_device_type=".$storage_device_type;
if($check_result1!="") $param .= ($param==""? "":"&")."check_result1=".$check_result1;
if($check_result2!="") $param .= ($param==""? "":"&")."check_result2=".$check_result2;


if($v_user_seq !=""){

	$search_sql = " AND us.v_user_seq = '".$v_user_seq."' ";
}

if($v_user_list_seq != ""){

	$search_sql = " AND vcs.v_user_list_seq = '".$v_user_list_seq."' ";
}

if($v_asset_type !=""){

	$search_sql .= " AND vcs.v_asset_type = '".$v_asset_type."' ";
}

if($v_asset_type =="NOTEBOOK"){
	
	if($v_notebook_key != ""){
		$search_sql .= " AND vcs.v_notebook_key = '".$v_notebook_key."' ";
	}
}

if($storage_device_type != ""){

	$search_sql .= " AND vcs.v_asset_type = 'RemovableDevice' ";

	//print_r($_CODE['storage_device_type']);


  if($storage_device_type=='DEVICE_ETC'){

	 $search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name,',') WHERE value not in ('Removable','HDD') and value > '' ) ";

  }else{

	$search_sql .=  " AND CHARINDEX('".$storage_device_type."',isnull(vcd.os_ver_name,'')) > 0 ";
  }
}

if($check_result2=="weak"){

	$search_sql .= " and exists (SELECT TOP 1 weakness_seq FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

}else if($check_result2=="virus"){

	$search_sql .= " and exists (
							SELECT TOP 1 vcc.vaccine_seq 
							FROM tb_v_wvcs_vaccine vcc
								INNER JOIN tb_v_wvcs_vaccine_detail vccd
									ON vcc.vaccine_seq = vccd.vaccine_seq
							WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
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

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
}

$args = array(
	"end"=> $end
	,"start"=>$start
	,"order_sql"=>$order_sql
	,"search_sql"=> $search_sql
);

$Model_result->SHOW_DEBUG_SQL = false;
$result = $Model_result->getVCSList($args);

if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
?>
<script type='text/javascript'>
$(function(){
	$("#uc_wrapper1").scroll(function(){
		$("#uc_wrapper2").scrollLeft($("#uc_wrapper1").scrollLeft());
	});
	$("#uc_wrapper2").scroll(function(){
		$("#uc_wrapper1").scrollLeft($("#uc_wrapper2").scrollLeft());
	});
	window.onresize = function(event) {
		var w = $("#tblUsrCheckList").width();
		$("#uc_div1").width(w);
	};

});
</script>
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
<div id='uc_wrapper1' class="wrapper">
	<div id='uc_div1' style='height:1px;width:1100px'></div>
</div>
<div id='uc_wrapper2' class="wrapper">
<table id='tblUsrCheckList' class="list" style="margin-top:0px;min-width:1100px;" >
<tr>
	<th style='min-width:60px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
	<th style='min-width:120px' ><? echo $_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<th class='cls_cfg_in_available_dt' style='min-width:120px' ><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></th>
	<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
	<th style='min-width:130px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<th style='min-width:80px' ><? echo trsLang('임직원','staff');?></th>
	<th style='min-width:80px' ><? echo trsLang('임직원소속','employee_affiliation');?></th>
	<th style='min-width:80px' ><? echo trsLang('전자문서번호','electronic_payment_document_number');?>(<? echo trsLang('출입번호','visitnumbertext');?>)</th>
	<th style='min-width:80px' >USB <? echo trsLang('관리번호','managenumber');?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
	<th style='min-width:70px' ><?=$_LANG_TEXT["scanfilecount"][$lang_code];?></th>
	<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
	<th style='min-width:70px' ><?=$_LANG_TEXT["importfilecount"][$lang_code];?></th>
	<?}?>
	<?if($src!="USER_VCS_LOG"){?>
	<th style='min-width:60px'><?=$_LANG_TEXT["detailviewtext"][$lang_code];?></th>
	<?}?>
	<th style='min-width:60px' ><? echo trsLang('PE 제작','madeby_pe');?></th>
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

		if ($row['make_winpe']=="1") {

				$make_winpe = trsLang('제작','produce_text');
		} else {
				$make_winpe = "";
		}

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
		<td  class='cls_cfg_in_available_dt' ><span name='in_available_date'><?=$in_available_date?></span></td>
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
		<?if($src !="USER_VCS_LOG"){?>
		<td>
			<?if($src =="RESULT_VIEW"){?>
				<a href="javascript:void(0)" class="pointer" onclick="sendPostForm('<? echo $view_url;?>')" ><?=$_LANG_TEXT["btnview"][$lang_code]?></a>

			<?}else{?>
				<a href="javascript:void(0)" onClick="return popUserVcsView('<?=$v_wvcs_seq?>');"><?=$_LANG_TEXT["btnview"][$lang_code]?></a>
			<?}?>
		</td>
		<?}?>
		<td><?=$make_winpe?></td>
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
</div>

<!--paging-->
<?php
if($total > $paging) {
	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
	print_pagelistNew3Func('user_check_list',$_www_server."/result/get_user_check_list.php",$page, $lists, $page_count, $param_enc, '', $total );
}else{
	echo "<div id='paging2'><ul><li><!--paging hide--></li></ul></div>";
}
?>
<? 
	$excel_down_flag = ($src=="VISIT_INFO_VIEW" ? "N" : "Y");
	$excel_name = trsLang('점검내역','checklisttext');

	if($v_user_seq > 0){
		$excel_name .= "(".$v_user_name.")";
	}

	$excel_down_url = "./result_list_600_excel.php?enc=" . ParamEnCoding($param); 
?>
<div class="right <?if($excel_down_flag=="N") echo "display-none";?>" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a  href="javascript:void(0)" title='<? echo trsLang('점검내역','checklisttext');?> <?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?>' class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
</div>