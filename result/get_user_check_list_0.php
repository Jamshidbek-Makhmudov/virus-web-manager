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
$v_com_seq = $_REQUEST[v_com_seq];
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
}else if($src=="VISIT_INFO_VIEW"){
	$paging = 100;
}else{
	$paging = 15;
}
if($paging == "") $paging = $_paging;

//echo $src;

$param = "";
if($src!="") $param .= ($param==""? "":"&")."src=".$src;
if($v_user_list_seq!="") $param .= ($param==""? "":"&")."v_user_list_seq=".$v_user_list_seq;
if($v_com_seq!="") $param .= ($param==""? "":"&")."v_com_seq=".$v_com_seq;
if($v_user_seq!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_user_seq;
if($v_asset_type!="") $param .= ($param==""? "":"&")."v_asset_type=".$v_asset_type;
if($v_notebook_key!="") $param .= ($param==""? "":"&")."v_notebook_key=".$v_notebook_key;
if($storage_device_type!="") $param .= ($param==""? "":"&")."storage_device_type=".$storage_device_type;
if($check_result1!="") $param .= ($param==""? "":"&")."check_result1=".$check_result1;
if($check_result2!="") $param .= ($param==""? "":"&")."check_result2=".$check_result2;


if($v_com_seq != ""){

	$search_sql = " AND us.v_com_seq = '".$v_com_seq."' ";
}

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

$qry_params = array(
	"search_sql"=> $search_sql
);

$qry_label = QRY_RESULT_CHECK_LIST_COUNT;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
$total = $row['CNT'];

$rows = $paging;			// �������� ��°���
$lists = $_list;			// ��ϼ�
$page_count = ceil($total/$rows);
if(!$page || $page > $page_count) $page = 1;
$start = ($page-1)*$rows;
$no = $total-$start;
$end = $start + $rows;

if($orderby != "") {
	$order_sql = " ORDER BY $orderby";
} else {
	$order_sql= " ORDER BY vcs.v_wvcs_seq DESC ";
}

$qry_params = array(
	"end"=> $end
	,"start"=>$start
	,"order_sql"=>$order_sql
	,"search_sql"=> $search_sql
);
$qry_label = QRY_RESULT_CHECK_LIST;
$sql = query($qry_label,$qry_params);
$result = sqlsrv_query($wvcs_dbcon, $sql); 

//echo nl2br($sql);

$cnt = 20;
$iK = 0;

if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

//�˻���å��������
$_POLICY= getPolicy('file_scan_yn','N');	//���ϰ˻翩��
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
<!--�˻��������Ʈ-->
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
<?if($src=="COM_INFO_VIEW"){?>
	<th style='min-width:60px' ><?=$_LANG_TEXT["visitortext"][$lang_code];?></th>
<?}?>
	<th style='min-width:120px' ><?=$_LANG_TEXT["checkdatetext"][$lang_code];?></th>
	<th class='cls_cfg_in_available_dt' style='min-width:120px' ><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></th>
	<th class="cls_cfg_inout_info" style='min-width:80px' ><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
	<th class="cls_cfg_inout_info" style='min-width:80px' ><?=$_LANG_TEXT["outdatetext"][$lang_code];?></th>
	<th style='min-width:80px'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
	<th class='cls_cfg_check_type' style='min-width:80px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
	<th style='min-width:150px'><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></th>
	<th style='min-width:130px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
	<th style='min-width:80px' ><?=$_LANG_TEXT["scanfilecount"][$lang_code];?></th>
	<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
	<th style='min-width:80px' ><?=$_LANG_TEXT["importfilecount"][$lang_code];?></th>
	<?}?>
	<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
	<th style='min-width:150px'><?=$_LANG_TEXT["managertext"][$lang_code];?></th>
	<?if($src!="USER_VCS_LOG"){?>
	<th style='min-width:60px'><?=$_LANG_TEXT["detailviewtext"][$lang_code];?></th>
	<?}?>
</tr>

<?

 if($result){
  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$cnt--;
		$iK++;

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
		$out_date	= $row['out_date'];

		$v_scan_center_name = $row['org_name']." ".$row['scan_center_name'];
		$v_asset_type = $row['v_asset_type'];
		$sys_sn = $row['v_sys_sn'];
		$hdd_sn = $row['v_hdd_sn'];
		$board_sn = $row['v_board_sn'];
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
		$vacc_scan_count = $row['vacc_scan_count'];

		$import_file_cnt = $row['import_file_cnt'];

		$scan_file_count = $row['scan_file_cnt'];
					
		//파일정보를 서버로 전송하는 경우는 바이러스 검사파일수 대신 전송된 파일정보수를 표시해 준다.
		if($scan_file_count > 0){
			$vacc_scan_count = $scan_file_count;
		}
		
		$file_bad_cnt = $row['file_bad_cnt'];


		$check_type = $row['wvcs_type'];
		$disk_cnt = $row['disk_cnt'];

		//$check_result = "<span class='icontxt clean'>".$_LANG_TEXT["safetytext"][$lang_code]."</span> ";

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
				//$check_result .= "<span class='icontxt weakness'>".$_LANG_TEXT["weaknessshorttext"][$lang_code]."</span> ";
				$check_result .="<img src='".$_www_server."/images/w_clean.png'>";
			}else{
				$check_result .="<img src='".$_www_server."/images/c_clean.png'>";
			}

		}

		if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){

			if($virus_cnt > 0){

				//$check_result .= "<span class='icontxt virus'>".$_LANG_TEXT["virusshorttext"][$lang_code]."</span> ";
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

		$vcs_status = $_CODE['vcs_status'][$row['vcs_status']];

		
		
		$param_enc = ParamEnCoding("view_src=".$src."&v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

		$param_view_enc = ParamEnCoding("view_src=".$src."&v_wvcs_seq=".$v_wvcs_seq."&user_check_list_page=".$page.($param==""? "":"&").$param);

		if($v_asset_type=='NOTEBOOK'){
			$view_url = $_www_server.'/result/result_view_pc.php?enc='.$param_view_enc;
		}else{
			$view_url = $_www_server.'/result/result_view_storage.php?enc='.$param_view_enc;
		}

		
  ?>	
	<tr id='r_<?=$v_wvcs_seq?>' <?if($v_wvcs_seq==$view_v_wvcs_seq){ echo "style='background-color:#d6e5ff;'";}?> onclick="return popUserVcsView('<?=$v_wvcs_seq?>');" style='cursor:pointer'>
		<td><?php echo $no; ?></td>
	<?if($src=="COM_INFO_VIEW"){?>
		<td><?=$vv_user_name ?></td>
	<?}?>
		<td><?=$check_date?></td>
		<td  class='cls_cfg_in_available_dt' ><?=$in_available_date?></td>
		<td class="cls_cfg_inout_info"><?=$in_date?></td>
		<td class="cls_cfg_inout_info"><?=$out_date?></td>
		<td><?=$v_scan_center_name?></td>
		<td><?=$_CODE['asset_type'][$v_asset_type]?><span class='blue'>(<?=$disk_cnt?>)</span></td>
		<td class="cls_cfg_check_type"><?=$check_type?></td>
		<td><?=$os?></td>
		<td><?=$sys_sn?></td>
		<td><?=$vcs_status?></td>
		<td><?=number_format($vacc_scan_count)?></td>
		<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
		<td><?=number_format($import_file_cnt);?>
			<!--<a href='javascript:void(0)' onClick="return popUserInFileList('<?=$v_wvcs_seq?>','<? echo $src;?>');" ><?=number_format($import_file_cnt);?></a>-->
		</td>
		<?}?>
		<td>
			<?if($src=="USER_VCS_LOG"){
				echo $check_result;
			}else{ ?>
				<a href="javascript:" onClick="return popUserVcsView('<?=$v_wvcs_seq?>');"><?=$check_result?></a>
			<?}?>
		</td>
		<td><?=$mngr?></td>
		<?if($src!="USER_VCS_LOG"){?>
		<td><a href='<?=$view_url?>' class='btn_link'><?=$_LANG_TEXT["btnview"][$lang_code]?></a></td>
		<?}?>
	</tr>
	<?php
	
		$no--;
	}
	
}

if($total < 1) {
	
?>
	<tr>
		<td colspan="13" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
	$excel_param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
	$excel_down_url = $_www_server."/result/user_check_list_excel.php?enc=".$excel_param_enc;
?>
<div class="right <?if($excel_down_flag=="N") echo "display-none";?>" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="javascript:" id='btnexcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnexcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
</div>