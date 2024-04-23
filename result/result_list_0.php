<?php
if(!$wvcs_dbcon) return;

$src = $_REQUEST[src];
$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$asset_type = $_REQUEST[asset_type];
$storage_device_type = $_REQUEST[storage_device_type];
$vcs_type = $_REQUEST[vcs_type];
$scan_center_code = $_REQUEST[scan_center_code];
$check_result1 = $_REQUEST[check_result1];
$check_result2 = $_REQUEST[check_result2];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$io_gubun = $_REQUEST[io_gubun];
$iodate1 = $_REQUEST[iodate1];
$iodate2 = $_REQUEST[iodate2];
$status = $_REQUEST[status];
$org_name = $_REQUEST[org_name];
$v_user_seq = $_REQUEST[v_user_seq];
$v_user_name = $_REQUEST[v_user_name];
$v_sys_sn = $_REQUEST[v_sys_sn];
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];

if($paging == "") $paging = $_paging;

if($check_result1 == "") $check_result1 = "last";


$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($asset_type!="") $param .= ($param==""? "":"&")."asset_type=".$asset_type;
if($storage_device_type!="") $param .= ($param==""? "":"&")."storage_device_type=".$storage_device_type;
if($vcs_type!="") $param .= ($param==""? "":"&")."vcs_type=".$vcs_type;
if($scan_center_code!="") $param .= ($param==""? "":"&")."scan_center_code=".$scan_center_code;
if($check_result1!="") $param .= ($param==""? "":"&")."check_result1=".$check_result1;
if($check_result2!="") $param .= ($param==""? "":"&")."check_result2=".$check_result2;
if($checkdate1!="") $param .= ($param==""? "":"&")."checkdate1=".$checkdate1;
if($checkdate2!="") $param .= ($param==""? "":"&")."checkdate2=".$checkdate2;
if($io_gubun!="") $param .= ($param==""? "":"&")."io_gubun=".$io_gubun;
if($iodate1!="") $param .= ($param==""? "":"&")."iodate1=".$iodate1;
if($iodate2!="") $param .= ($param==""? "":"&")."iodate2=".$iodate2;
if($status!="") $param .= ($param==""? "":"&")."status=".$status;
if($org_name!="") $param .= ($param==""? "":"&")."org_name=".$org_name;
if($v_user_seq!="") $param .= ($param==""? "":"&")."v_user_seq=".$v_user_seq;
if($v_user_name!="") $param .= ($param==""? "":"&")."v_user_name=".$v_user_name;
if($v_sys_sn!="") $param .= ($param==""? "":"&")."v_sys_sn=".$v_sys_sn;
if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}



//검사정책가져오기
$_POLICY= getPolicy('file_scan_yn','N');	//파일검사여부
?>
<script language="javascript">
$("document").ready(function(){

	var w = $("#tblList").width();
	$("#div1").width(w);
	
	setAssetGubun(document.all.vcs_type);
	setStoargeDeviceType(document.all.asset_type);

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
<div id="result_list">
	<div class="container">
		
		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_result"][$lang_code];?></span></h1>
			</div>
			<div class='right'>
			<?if($_ck_user_level=="SECURITOR_S"){?>
				<a href='javascript:' onclick="popScan('N');" class="btnbarcode search" ><img src="<?=$_www_server?>/images/scann_box_icon.png">  Barcode <?=$_LANG_TEXT['btnsearch'][$lang_code]?></a>
			<?}else{?>
				<a href='javascript:' onclick="popScan('Y');" class="btnbarcode checkin  cls_cfg_inout_info" ><img src="<?=$_www_server?>/images/scann_box_icon.png"> Barcode <?=$_LANG_TEXT['btninout'][$lang_code]?></a>
				<a href='javascript:' onclick="popScan('N');" class="btnbarcode search" ><img src="<?=$_www_server?>/images/scann_box_icon.png">  Barcode <?=$_LANG_TEXT['btnsearch'][$lang_code]?></a>
			<?}?>
			</div>
			<span class="line"></span>
		</div>

		<?if($src){?>
		<div class="page_right" style='margin-top:-25px;'><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>
		<?}?>
		
		<div>
			<!--검색폼-->
			<form id='searchForm' name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="POST">
				<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="page" value="">
			<table class="search">
			<tr>
				<th style='width:100px'><?=$_LANG_TEXT["checkperiodtext"][$lang_code];?></th>
				<td style='width:400px'>
					<input type="text" name="checkdate1" id="checkdate1" class="frm_input datepicker" value="<?=$checkdate1?>" placeholder="" style="width:90px" maxlength="10"> ~ <input type="text" name="checkdate2" id="checkdate2" class="frm_input datepicker" value="<?=$checkdate2?>" placeholder="" style="width:90px"  maxlength="10">
				</td>
				<th style='width:100px;line-height:30px;'>
					<select name='io_gubun' id='io_gubun' style='font-weight:bold;'>
						<option value='indate' <? if($io_gubun=="indate") echo "selected"; ?>><?=$_LANG_TEXT["inperiodtext"][$lang_code];?></option>
						<option value='outdate' <? if($io_gubun=="outdate") echo "selected"; ?>><?=$_LANG_TEXT["outperiodtext"][$lang_code];?></option>
					</select>
				</th>
				<td style='min-width:300px'>
					<input type="text" name="iodate1" id="iodate1" class="frm_input datepicker" value="<?=$iodate1?>" placeholder="" style="width:90px" maxlength="10"> ~ <input type="text" name="iodate2" id="iodate2" class="frm_input datepicker" value="<?=$iodate2?>" placeholder="" style="width:90px" maxlength="10">
				</td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT["checkndevicegubuntext"][$lang_code];?></th>
				<td>
					<select id='vcs_type' name='vcs_type' class='cls_cfg_check_type' style='width:120px' onchange="setAssetGubun(this)">
						<option value=''><?=$_LANG_TEXT["checkgubunchoosetext"][$lang_code];?></option>
					<?
					foreach($_CODE['vcs_type'] as $key => $name){
						echo "<option value='".$key."' ".($vcs_type==$key ? "selected" : "").">".$name."</option>";
					}
					?>
					</select>
					<select id='asset_type' name='asset_type' style='width:120px' onchange="setStoargeDeviceType(this)">
						<option value=''><?=$_LANG_TEXT["devicegubunchoosetext"][$lang_code];?></option>
					<?
					foreach($_CODE['asset_type'] as $key => $name){
						echo "<option value='".$key."' ".($asset_type==$key ? "selected" : "").">".$name."</option>";
					}
					?>
					</select>
					<select id='storage_device_type' name='storage_device_type' style='width:140px' >
						<option value=''><?=$_LANG_TEXT["storagegubunchoosetext"][$lang_code];?></option>
					<?
					foreach($_CODE['storage_device_type'] as $key => $name){
						echo "<option value='".$key."' ".($storage_device_type==$key ? "selected" : "").">".$name."</option>";
					}
					?>
					</select>
				</td>
				<th><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
				<td>
					<select id='status' name='status'>
						<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
					<?
					foreach($_CODE['vcs_status'] as $key => $name){
						echo "<option value='".$key."' ".($status==$key ? "selected" : "").">".$name."</option>";
					}
					?>
					</select>
				</td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
				<td>
					<select name='scan_center_code' id='scan_center_code'>
						<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
					<?php
						$qry_params = array();
						$qry_label = QRY_COMMON_SCAN_CENTER_USE_ALL;
						$sql = query($qry_label,$qry_params);

						$result = sqlsrv_query($wvcs_dbcon, $sql);
						
						if($result){
							while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

								$_scan_center_code = $row['scan_center_code'];
								$_scan_center_name = $row['scan_center_name'];
					?>
							<option value='<?=$_scan_center_code?>' <?if($_scan_center_code==$scan_center_code) echo "selected";?>><?=$_scan_center_name?></option>	
					<?php
							}
						}
					?>
					</select>
				</td>
				<th><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
				<td>
					<select id='check_result1' name='check_result1' >
						<option value='last' <?if($check_result1=='last') echo "selected";?>><?=$_LANG_TEXT["lastcheckresulttext"][$lang_code];?></option>	
						<option value='all' <?if($check_result1=='all') echo "selected";?>><?=$_LANG_TEXT["allcheckresulttext"][$lang_code];?></option>
					</select>
					<select id='check_result2' name='check_result2'>
						<option value='' <?if($check_result2=='') echo "selected";?>><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
						<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){?>
						<option value='bad_ext' <?if($check_result2=='bad_ext') echo "selected";?>><?=trsLang('위변조의심','suspectforgerytext');?></option>
						<?}?>
						<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)){?>
						<option value='weak' <?if($check_result2=='weak') echo "selected";?>><?=$_LANG_TEXT["weaknessdetectiontext"][$lang_code];?></option>
						<?}?>
						<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){?>
						<option value='virus' <?if($check_result2=='virus') echo "selected";?>><?=$_LANG_TEXT["virusdetectiontext"][$lang_code];?></option>
						<?}?>
					</select>
				</td>
			</tr>
			<tr>
				<th><?=$_LANG_TEXT["keywordsearchtext"][$lang_code];?></th>
				<td colspan="3">
					<select name="searchopt" id="searchopt">
						<option value='USER_NAME' <?if($searchopt=="USER_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["visitortext"][$lang_code];?></option>
						<option value='MANAGER' <?if($searchopt=="MANAGER") echo "selected=selected"?>><?=$_LANG_TEXT["executives"][$lang_code];?></option>
						<option value='CHECK_APRV_NAME' <?if($searchopt=="CHECK_APRV_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["checkapprovertext"][$lang_code];?></option>
						<option value='SN' <?if($searchopt=="SN") echo "selected=selected"?>><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></option>
						<option value='OS' <?if($searchopt=="OS") echo "selected=selected"?>><?=$_LANG_TEXT["ostext"][$lang_code];?></option>
						<option value='MODEL' <?if($searchopt=="MODEL") echo "selected=selected"?>><?=$_LANG_TEXT["modeltext"][$lang_code];?></option>
						<option value='MANUFACTURER' <?if($searchopt=="MANUFACTURER") echo "selected=selected"?>><?=$_LANG_TEXT["manufacturertext"][$lang_code];?></option>
						<option value='MANAGER_DEPT' <?if($searchopt=="MANAGER_DEPT") echo "selected=selected"?>><?=$_LANG_TEXT["employee_affiliation"][$lang_code];?></option>
						<option value='ORG_NAME' <?if($searchopt=="ORG_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["organtext"][$lang_code];?></option>
						<option value='COM_NAME' <?if($searchopt=="COM_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></option>
						<option value='COM_SEQ' <?if($searchopt=="COM_SEQ") echo "selected=selected"?>><?=$_LANG_TEXT["usercompanycodetext"][$lang_code];?></option>
					</select>
					<input type="text" name="searchkey" id="searchkey" class="frm_input" value="<?=$searchkey?>" maxlength="50">
					<input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" >
				</td>
			</tr>
			</table>
			<div class="btn_wrap">
				<? 
					$param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
					$excel_down_url = $_www_server."/result/result_list_excel.php?enc=".$param_enc;
				?>
				<div class="right">
					<a href="#" id='btnexcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnexcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
				</div>
			</div>
			<?
			$search_sql = "";

			if($asset_type != ""){

				$search_sql .=  " AND vcs.v_asset_type = '".$asset_type."' ";
			}


			if($storage_device_type != ""){

				$search_sql .=  " AND vcs.v_asset_type = 'RemovableDevice' ";

				if($storage_device_type=='DEVICE_ETC'){

				  $search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name,',') WHERE value not in ('Removable','HDD','CD/DVD') and value > '' ) ";

				}else{

					$search_sql .=  " AND CHARINDEX('".$storage_device_type."',vcd.os_ver_name) > 0 ";

				}//if($storage_device_type=='DEVICE_ETC'){
			}


			if($vcs_type != ""){

			   $search_sql .=  " AND vcs.wvcs_type = '".$vcs_type."' ";
			}

			if($scan_center_code != ""){

			   $search_sql .=  " AND vcs.scan_center_code = '".$scan_center_code."' ";
			}


			if($checkdate1 != "" && $checkdate2 !=""){

				$search_sql .= " AND vcs.wvcs_dt between '$checkdate1 00:00:00.000' and '$checkdate2 23:59:59.999' ";
			}

			if($io_gubun=="indate" && $iodate1 !="" && $iodate2 != ""){	

				$search_sql .= " AND vcs.wvcs_authorize_dt between '$iodate1 00:00:00.000' and '$iodate2 23:59:59.999' ";
			}

			if($io_gubun=="outdate" && $iodate1 !="" && $iodate2 != ""){	

				$search_sql .= " AND vcs.checkout_dt between '$iodate1 00:00:00.000' and '$iodate2 23:59:59.999' ";
			}

			if($status !=""){

				$search_sql .= " AND vcs.vcs_status = '$status' ";
				
			}//if($status !=""){

			if($v_user_seq !=""){
				
				$search_sql .= " AND us.v_user_seq = '$v_user_seq' ";
			}

			if($v_sys_sn !=""){
				
				$search_sql .= " and vcd.v_sys_sn like '%$v_sys_sn%' ";
			}


			if($searchkey != ""){
					
				  if($searchopt=="CHECK_APRV_NAME"){
					
					$search_sql .= " and vcs.wvcs_authorize_name = '".aes_256_enc($searchkey)."' ";

				  }else if($searchopt=="USER_NAME"){

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

				  }else if($searchopt=="OS"){
					
					$search_sql .= " and vcs.v_asset_type='NOTEBOOK' and dbo.fn_os_name(vcd.os_ver_name,vcd.os_ver_major,vcd.os_ver_minor) like '%$searchkey%' ";

				  }else if($searchopt=="MODEL"){
					
					$search_sql .= " and vcs.v_asset_type='NOTEBOOK' and vcd.v_model_name like '%$searchkey%' ";

				  }else if($searchopt=="MANUFACTURER"){
					
					$search_sql .= " and vcs.v_asset_type='NOTEBOOK' and vcd.v_manufacturer like '%$searchkey%' ";

				  }else if($searchopt=="MANAGER"){
					
					$search_sql .= " and vcs.mngr_name = '".aes_256_enc($searchkey)."' ";

				  }else if($searchopt=="MANAGER_DEPT"){
					
					$search_sql .= " and vcs.mngr_department like '%$searchkey%' ";

				  }else if($searchopt=="ORG_NAME"){
					
					$search_sql .= " and org.org_name like '%$searchkey%' ";

				  }else if($searchopt=="SN"){
					
					$search_sql .= " and vcd.v_sys_sn like '%$searchkey%' ";

				  }else if($searchopt=="COM_NAME"){
					
					$search_sql .= " and vc.v_com_name like '%$searchkey%' ";	

				  }else if($searchopt=="COM_SEQ"){
					
					$search_sql .= " and vc.v_com_seq = '{$searchkey}' ";	

				  }

				  

			}//if($searchkey != ""){


			if($check_result2=="weak"){

				$search_sql .= " and exists (SELECT TOP 1 weakness_seq FROM tb_v_wvcs_weakness WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";

			}else if($check_result2=="virus"){

				$search_sql .= " and exists (
										SELECT TOP 1 vcc.vaccine_seq 
										FROM tb_v_wvcs_vaccine vcc
											INNER JOIN tb_v_wvcs_vaccine_detail vccd
												ON vcc.vaccine_seq = vccd.vaccine_seq
										WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
			
			}else if($check_result2=="bad_ext"){	//위변조의심
				
				$search_sql .= " and exists (
										SELECT TOP 1 f.v_wvcs_file_seq
										from tb_v_wvcs_info_file f
										WHERE f.v_wvcs_seq = vcs.v_wvcs_seq
											AND f.file_scan_result ='BAD_EXT' ) ";
			}

			$qry_params = array("search_sql"=> $search_sql);

			if($check_result1=="last"){
				$qry_label = QRY_RESULT_LASTCHECK_LIST_COUNT;
			}else{
				$qry_label = QRY_RESULT_CHECK_LIST_COUNT;
			}
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql); 

			//echo nl2br($sql);
			
			$total = 0;
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
				$order_sql = " ORDER BY vcs.v_wvcs_seq DESC ";
			}

			$qry_params = array(
				"end"=> $end
				,"start"=>$start
				,"order_sql"=>$order_sql
				,"search_sql"=> $search_sql
			);

			if($check_result1=="last"){
				$qry_label = QRY_RESULT_LASTCHECK_LIST;
			}else{
				$qry_label = QRY_RESULT_CHECK_LIST;
			}
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql); 

			//echo nl2br($sql);

			$cnt = 20;
			$iK = 0;

			if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

			?>

			<!--검색결과리스트-->
		<div class="btn_wrap right " style=''>
						<div class="right">
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
				<div style='margin-right:10px; line-height:20px;' class="right">
					Results : <span style='color:blue'><?=number_format($total)?></span> / 
					Records : <select style='position:relative; bottom:5px;' name='paging' onchange="searchForm.submit();">
						<option value='20' <?if($paging=='20') echo "selected";?>>20</option>
						<option value='40' <?if($paging=='40') echo "selected";?>>40</option>
						<option value='60' <?if($paging=='60') echo "selected";?>>60</option>
						<option value='80' <?if($paging=='80') echo "selected";?>>80</option>
						<option value='100' <?if($paging=='100') echo "selected";?>>100</option>
					</select>
				</div>

			</div>
			</form>
			<div id='wrapper1' class="wrapper">
			  <div id='div1' style='height:1px;'></div>
			</div>
			<div id='wrapper2' class="wrapper">
			<table id='tblList' class="list" style="margin-top:0px;min-width:1400px;" >
			<tr>
				<th style='min-width:60px' ><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
				<th style='min-width:150px'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="v_user_name"? "v_user_name desc" : "v_user_name"))?>" class='sort'><?=$_LANG_TEXT["visitortext"][$lang_code];?></a></th>
				<th style='min-width:120px' ><? echo $_LANG_TEXT["checkdatetext"][$lang_code];?></th>
				<th class='cls_cfg_in_available_dt' style='min-width:120px' ><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></th>
				<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
				<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></th>
				<th style='min-width:80px'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="scan_center_name"? "scan_center_name desc" : "scan_center_name"))?>"       class='sort'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></a></th>
				<th class='cls_cfg_check_type' style='min-width:90px' ><?=$_LANG_TEXT["checkgubuntext"][$lang_code];?></th>
				<th style='min-width:80px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
				<th style='min-width:140px'><?=$_LANG_TEXT["osndevicetext"][$lang_code];?></th>
				<th style='min-width:130px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
				
				<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
				<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
				<?if($_ck_user_level!="SECURITOR_S"){?>
				<th style='min-width:60px' class="cls_cfg_inout_info"><?=$_LANG_TEXT["inaccesstext"][$lang_code];?></th>
				<th style='min-width:60px' class="cls_cfg_inout_info"><?=$_LANG_TEXT["outaccesstext"][$lang_code];?></th>
				<?}?>
				<th style='min-width:70px' ><?=$_LANG_TEXT["scanfilecount"][$lang_code];?></th>
				<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
				<th style='min-width:70px' ><?=$_LANG_TEXT["importfilecount"][$lang_code];?></th>
				<?}?>
				<th style='min-width:60px' ><?=$_LANG_TEXT["logtext"][$lang_code];?></th>
			</tr>

			<?

			//echo nl2br($sql);

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
					$out_date = $row['out_date'];
					
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
					$vacc_scan_count = $row['vacc_scan_count'];	//바이러스검사파일
					$file_bad_cnt = $row['file_bad_cnt'];

					
					$scan_file_cnt = $row['scan_file_cnt'];
					
					//파일정보를 서버로 전송하는 경우는 바이러스 검사파일수 대신 전송된 파일정보수를 표시해 준다.
					if($scan_file_cnt > 0){
						$vacc_scan_count = $scan_file_cnt;
					}

					$check_type = $row['wvcs_type'];
					$disk_cnt = $row['disk_cnt'];
					$import_file_cnt = $row['import_file_cnt'];
					
					$param_enc = ParamEnCoding("v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

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

					}else if($_encryption_kind=="2" || $_encryption_kind=="3"){

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

					$vcs_status = $row['vcs_status'];
					$str_vcs_status = $_CODE['vcs_status'][$vcs_status];

					$view_param_enc = ParamEnCoding("view_src=RESULT_LIST&page=".$page."&v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);

					if($v_asset_type=='NOTEBOOK'){
						$view_url = './result_view_pc.php?enc='.$view_param_enc;
					}else{
						$view_url = './result_view_storage.php?enc='.$view_param_enc;
					}

					
			  ?>	
				<tr onclick="javascript:location.href='<?=$view_url?>'" style='cursor:pointer;text-align:center'>
					<td><span name='seq' style='display:none;' ><?=$v_wvcs_seq?></span><?php echo $no; ?></td>
					<td><?=$user_name_com?></td>
					<td><?=$check_date?></td>
					<td  class='cls_cfg_in_available_dt' ><span name='in_available_date'><?=$in_available_date?></span></td>
					<td class="cls_cfg_inout_info"><span name='in_date'><?=$in_date?></span></td>
					<td class="cls_cfg_inout_info"><span name='out_date'><?=$out_date?></span></td>
					<td><?=$v_scan_center_name?></td>
					<td class='cls_cfg_check_type' ><?=$check_type?></td>
					<td><?=$_CODE['asset_type'][$v_asset_type]?><span class='blue'>(<?=$disk_cnt?>)</span></td>
					<td><?=$os?></td>
					<td><?=$sys_sn?></td>
					<td><span name='vcs_status'><?=$str_vcs_status?></span></td>
					<td onClick="event.stopPropagation();"><a href="javascript:" onClick="return popUserVcsView('<?=$v_wvcs_seq?>');"><?=$check_result?></a></td>
				
					
					<?if($_ck_user_level!="SECURITOR_S"){?>
						<td onClick="event.stopPropagation();" class="cls_cfg_inout_info">
							<span name='btnin' <?if($in_date > "") echo "style='display:none'";?>>
								<a href='javascript:' onClick="return ResultCheckInSubmit2(this);" class='btn20 cyan' ><?=$_LANG_TEXT["btnin"][$lang_code]?></a>
							</span>
							<span name='btnincancel' <?if($in_date == "") echo "style='display:none'";?>>
								<a href='javascript:' onClick="return ResultCheckInSubmit2(this);" class='btn20 gray' ><?=$_LANG_TEXT["btncancel"][$lang_code]?></a>
							</span>
						</td>
						<td onClick="event.stopPropagation();" class="cls_cfg_inout_info">
							<span name='btnout' <?if($in_date=="" || $out_date > "") echo "style='display:none'";?>>
								<a href='javascript:' onClick="return ResultCheckOutSubmit2(this);" class='btn20 orange' ><?=$_LANG_TEXT["btnout"][$lang_code]?></a>
							</span>
							<span name='btnoutcancel' <?if($in_date=="" || $out_date=="") echo "style='display:none'";?>>
								<a href='javascript:' onClick="return ResultCheckOutSubmit2(this);" class='btn20 gray'><?=$_LANG_TEXT["btncancel"][$lang_code]?></a>
							</span>
						</td>
					<?}?>
					<td ><?=number_format($vacc_scan_count);?></td>
					<?if($_P_CHECK_FILE_SEND_TYPE !="N"){?>
					<td  ><?=number_format($import_file_cnt);?></td>
					<!--<td  onClick="event.stopPropagation();"><a href='javascript:void(0)' onClick="return popUserInFileList('<?=$v_wvcs_seq?>','USER_FILE_LIST');" ><?=number_format($import_file_cnt);?></a></td>-->
					<?} ?>
					
					<td onClick="event.stopPropagation();"><a href='javascript:' onClick="return popUserVcsLog('<?=$vv_user_sq?>','<?=$vv_user_name?>','<?=$v_notebook_key?>','<?=$v_asset_type?>');" class='btn_link'><?=$_LANG_TEXT["btnview"][$lang_code]?></a></td>
				</tr>
				<?php
				
					$no--;
				}
				
			}

			if($total < 1) {
				
			?>
				<tr>
					<td colspan="20" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
</div>
<div id='popContent' style='display:none'></div>
<?php

if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>