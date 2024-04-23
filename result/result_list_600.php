<?php
if(!$wvcs_dbcon) return;

$src = $_REQUEST[src];
$storage_device_type = $_REQUEST[storage_device_type];
$scan_center_code = $_REQUEST[scan_center_code];
$check_result2 = $_REQUEST[check_result2];
$checkdate1 = $_REQUEST[checkdate1];
$checkdate2 = $_REQUEST[checkdate2];
$status = $_REQUEST[status];
$searchopt = $_REQUEST[searchopt];	// 검색옵션
$searchkey = $_REQUEST[searchkey];	// 검색어
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];

if($paging == "") $paging = $_paging;

if($checkdate1=="") $checkdate1 = date("Y-m-d",strtotime("-1 months"));
if($checkdate2=="") $checkdate2 = date("Y-m-d");
if($searchopt=="")  $searchopt  = "USER_NAME";

$param = "";

if($storage_device_type!="") $param .= ($param==""? "":"&")."storage_device_type=".$storage_device_type;
if($scan_center_code!="") $param .= ($param==""? "":"&")."scan_center_code=".$scan_center_code;
if($check_result2!="") $param .= ($param==""? "":"&")."check_result2=".$check_result2;
if($checkdate1!="") $param .= ($param==""? "":"&")."checkdate1=".$checkdate1;
if($checkdate2!="") $param .= ($param==""? "":"&")."checkdate2=".$checkdate2;
if($status!="") $param .= ($param==""? "":"&")."status=".$status;
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

//상세검색
for($i = 1 ; $i < 4 ; $i++) {
	${"searchopt".$i} = $_REQUEST['searchopt'.$i];			// 검색옵션
	${"searchandor".$i} = $_REQUEST['searchandor'.$i];	// 검색연결자
	${"searchkey".$i} = $_REQUEST['searchkey'.$i];		// 검색어
	
	if(${"searchopt".$i}!="") $param .= ($param==""? "":"&")."searchopt{$i}=".${"searchopt".$i};
	if(${"searchandor".$i}!="") $param .= ($param==""? "":"&")."searchandor{$i}=".${"searchandor".$i};
	if(${"searchkey".$i}!="") $param .= ($param==""? "":"&")."searchkey{$i}=".${"searchkey".$i};
}

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != "") {
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

//검색키워드목록
$searchopt_list = array(
	"USER_NAME"=>trsLang("이름","nametext")
	,"USER_BELONG"=>trsLang("소속","belongtext")
	,"MANAGER"=>trsLang("임직원","executives")
	,"MANAGER_DEPT"=>trsLang("임직원 소속","employee_affiliation")
	,"DOC_NO"=>trsLang("전자문서번호","electronic_payment_document_number")
	,"USB_MGT_NO"=>"USB ".trsLang("관리번호","managenumber")
	,"SN"=>trsLang("Serial No","serialnumbertext")
);

?>

<script language="javascript">
$("document").ready(function() {
	var w = $("#tblList").width();
	$("#div1").width(w);
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
</script>

<div id="result_list">
	<div class="container">
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["m_result"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<?if($src) {?>
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
						<th><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
						<td>
							<select name='scan_center_code' id='scan_center_code' style='min-width:202px;'>
								<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?php
								$Model_manage = new Model_manage;
								$result = $Model_manage->getCenterList();
								
								if($result) {
									while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
										$_scan_center_code = $row['scan_center_code'];
										$_scan_center_name = $row['scan_center_name'];
										$selected = ($_scan_center_code == $scan_center_code) ? "selected" : "";

										echo "<option value='{$_scan_center_code}' {$selected}>{$_scan_center_name}</option>";
									}
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th><? echo trsLang('장비구분','devicegubuntext');?></th>
						<td>
							<select id='storage_device_type' name='storage_device_type' style='width:150px'>
								<option value=''><? echo trsLang('장비구분','devicegubuntext');?></option>
								<?
								foreach($_CODE['storage_device_type'] as $key => $name) {
									$selected = ($storage_device_type == $key) ? "selected" : "";

									echo "<option value='{$key}' {$selected}>{$name}</option>";
								}
								?>
							</select>
						</td>
						<th><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
						<td>
							<select id='check_result2' name='check_result2'>
								<option value='' <?if($check_result2=='') echo "selected";?>><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
								<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)) {?>
								<option value='bad_ext' <?if($check_result2=='bad_ext') echo "selected";?>><?=trsLang('위변조의심','suspectforgerytext');?></option>
								<?}?>
								<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)) {?>
								<option value='weak' <?if($check_result2=='weak') echo "selected";?>><?=$_LANG_TEXT["weaknessdetectiontext"][$lang_code];?></option>
								<?}?>
								<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)) {?>
								<option value='virus' <?if($check_result2=='virus') echo "selected";?>><?=$_LANG_TEXT["virusdetectiontext"][$lang_code];?></option>
								<?}?>
							</select>
							<select id='status' name='status'>
								<option value=''><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></option>
								<?
								foreach($_CODE['vcs_status'] as $key => $name) {
									if($key=="OUT") continue;

									$selected = ($status == $key) ? "selected" : "";

									echo "<option value='{$key}' {$selected}>{$name}</option>";
								}
								?>
							</select>
						</td>
					</tr>
					<tr>
						<th><?=$_LANG_TEXT["keywordsearchtext"][$lang_code];?></th>
						<td colspan="3" style='padding:5px 13px'>
							<select name="searchopt" id="searchopt" style='max-width:150px;height:31px;margin-top:1px;'>
								<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
								<?
								foreach($searchopt_list as $key=>$name) {
									$selected = ($searchopt == $key) ? "selected" : "";

									echo "<option value='{$key}' {$selected} >{$name}</option>";
								}
								?>
							</select>
							<input type="text" class="frm_input" style="width:calc(100vw - 667px);max-width:610px;min-width:296px;" name="searchkey" id="searchkey" value="<?= $searchkey ?>" maxlength="100">
							<input type="submit"  value="<?= $_LANG_TEXT['usersearchtext'][$lang_code] ?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);">
							<input type="button" value="<?= $_LANG_TEXT['userdetailsearchtext'][$lang_code] ?>" class="btn_submit_no_icon" onclick="$('#search_detail').toggle()">
							<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/result/result_list.php'">
							<!--상세검색-->
							<?
								$search_detail_visible = (($searchopt1 && $searchkey1) || ($searchopt2 && $searchkey2) || ($searchopt3 && $searchkey3));
							?>
							<div id='search_detail' style='<? if(!$search_detail_visible) echo "display:none";?>'>
								<? for($i = 1 ; $i < 4 ; $i++) {?>
								<div  style='margin-top:5px;'>
									<select name="searchandor<? echo $i?>" id="searchandor<? echo $i?>" style="height:31px;margin-top:1px;">
										<option value='AND' <? if(${"searchandor".$i}=="AND") echo "selected";?>>AND</option>
										<option value='OR' <? if(${"searchandor".$i}=="OR") echo "selected";?>>OR</option>
									</select>
									<select name="searchopt<? echo $i?>" id="searchopt<? echo $i?>"  style='max-width:150px;height:31px;margin-top:1px;'>
										<option value="" <?php if(${"searchopt".$i} == "") { echo ' selected="selected"'; } ?>><?=$_LANG_TEXT['select_search_item'][$lang_code]?></option>
										<?
										foreach($searchopt_list as $key=>$name) {
											$selected = (${"searchopt".$i}==$key) ? "selected" : "";

											echo "<option value='{$key}' {$selected} >{$name}</option>";
										}
										?>
									</select>
									<input style="width:547px;max-width:547px;" type="text" class="frm_input" name="searchkey<? echo $i?>" id="searchkey<? echo $i?>" maxlength="100" value='<? echo ${'searchkey'.$i}?>'>
								</div>
								<?}?>
							</div>
						</td>
					</tr>
				</table>
				<?
				$search_sql = "";

				if($storage_device_type != "") {
					$search_sql .=  " AND vcs.v_asset_type = 'RemovableDevice' ";

					if($storage_device_type=='DEVICE_ETC') {
						$search_sql .= " AND exists (select value from dbo.fn_split(vcd.os_ver_name, ',') WHERE value not in ('Removable','HDD','CD/DVD') and value > '' ) ";
					}else{
						$search_sql .= " AND CHARINDEX('{$storage_device_type}', vcd.os_ver_name) > 0 ";
					}
				}

				if($scan_center_code != "") {
				$search_sql .=  " AND vcs.scan_center_code = '{$scan_center_code}' ";
				}

				if($checkdate1 != "" && $checkdate2 != "") {
					$search_sql .= " AND vcs.wvcs_dt between '{$checkdate1} 00:00:00.000' and '{$checkdate2} 23:59:59.999' ";
				}

				if($status !="") {
					$search_sql .= " AND vcs.vcs_status = '{$status}' ";
				}

				//키워드검색
				$searchkey_sql= array(
					"USER_BELONG" => " vl.v_user_belong like N'%{?}%' "
					,"MANAGER_DEPT" => " vl.manager_dept like '%{?}%' "
					,"SN" => " vcd.v_sys_sn like '%{?}%' "
					,"DOC_NO" => " vi.elec_doc_number like '%{?}%' "
					,"USB_MGT_NO" => " (vi.label_name='ITEM_MGT_NO' and vi.label_value like N'%{?}%' ) "
				);

				$searchandor0 = " and ( ";
				$searchopt0 = $searchopt;
				$searchkey0 = $searchkey;	
				$keyword_search_sql = "";

				for($i = 0 ; $i < 4 ;$i++) {
					$_searchopt = ${"searchopt".$i};	
					$_searchkey = ${"searchkey".$i};	
					$_searchandor = ${"searchandor".$i};	

					if($_searchopt != "" && $_searchkey != "") {
						if($_searchopt=="USER_NAME") {
							$keyword_search_sql .= " {$_searchandor} vl.v_user_name  = '".aes_256_enc($_searchkey)."'";
						}else if($_searchopt=="MANAGER") {
							$keyword_search_sql .= " {$_searchandor} ( vl.manager_name  = '".aes_256_enc($_searchkey)."' or  vl.manager_name_en like '%{$_searchkey}%' ) ";
						}else {
							$keyword_search_sql .= " {$_searchandor} ".str_replace('{?}', $_searchkey, $searchkey_sql[$_searchopt]);
						}
					}
				}

				if ($keyword_search_sql != "") {
					$search_sql .= $keyword_search_sql.")";
				}

				if ($check_result2=="weak") {
					$search_sql .= " and exists (
											SELECT TOP 1 weakness_seq
											FROM tb_v_wvcs_weakness 
											WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
				} else if($check_result2=="virus") {
					$search_sql .= " and exists (
											SELECT TOP 1 vcc.vaccine_seq 
											FROM tb_v_wvcs_vaccine vcc
												INNER JOIN tb_v_wvcs_vaccine_detail vccd ON vcc.vaccine_seq = vccd.vaccine_seq
											WHERE vcs.v_wvcs_seq = v_wvcs_seq ) ";
				// 위변조의심
				} else if($check_result2=="bad_ext") {
					$search_sql .= " and exists (
											SELECT TOP 1 f.v_wvcs_file_seq
											FROM tb_v_wvcs_info_file f
											WHERE f.v_wvcs_seq = vcs.v_wvcs_seq
												AND f.file_scan_result ='BAD_EXT' ) ";
				}

				if ($_ck_user_level == "SUPER") {
					$auth_office = true;
					$auth_idc    = true;
				} else {
					if(in_array("U1000", $_ck_user_mauth)) {
						$U1000 = $comm_user_page_auth["U1000"];
						
						if (array_key_exists('all', $U1000)) {
							$auth_office = true;
							$auth_idc    = true;
						} else {
							$auth_office = array_key_exists('access_control', $U1000);
							$auth_idc    = array_key_exists('access_control_idc', $U1000);
						}
					} else {
						$auth_office = false;
						$auth_idc    = false;
					}
				}
				
				$Model_result = new Model_result();
				$args  = array("search_sql"=> $search_sql);
				$total = $Model_result->getVCSListCount($args);
			
				$rows  = $paging;			// 페이지당 출력갯수
				$lists = $_list;			// 목록수
				$page_count = ceil($total/$rows);
				if(!$page || $page > $page_count) $page = 1;
				$start = ($page-1)*$rows;
				$no  = $total-$start;
				$end = $start + $rows;

				if($orderby != "") {
					$order_sql = " ORDER BY {$orderby}";
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

				if ($orderby != "") {
					$param .= ($param==""? "":"&")."orderby={$orderby}";
				}
				?>

				<!--excel download-->
				<?
					$excel_name = trsLang('점검결과','checkresulttext');
					$excel_down_url = $_www_server . "/result/result_list_600_excel.php?enc=" . ParamEnCoding($param); 
				?>
				<div class="btn_wrap">
					<div class="right">
						<a  href="javascript:void(0)" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?= $total ?>','<?= $excel_down_url ?>','<?= $excel_name ?>',this);"><?= $_LANG_TEXT["btnexceldownload"][$lang_code]; ?></a>
					</div>
				</div>


				<!--검색결과리스트-->
				<div class="btn_wrap right " style=''>
					<div class="right">
						<?if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)) {?>
						<img src="<? echo $_www_server?>/images/b_clean.png"> <? echo trsLang('위변조의심','suspectforgerytext');?>
						<?}?>
						<?if(in_array("VIRUS",$_CODE_INSPECT_OPTION)) {?>
						<img src="<? echo $_www_server?>/images/v_clean.png"> <? echo $_LANG_TEXT["viruscleantext"][$lang_code];?>
						<?}?>
						<?if(in_array("WEAK",$_CODE_INSPECT_OPTION)) {?>
						<img src="<? echo $_www_server?>/images/w_clean.png"> <? echo $_LANG_TEXT["weaknesscleantext"][$lang_code];?>
						<?}?>
					</div>
					<div style='margin-right:10px; line-height:20px;' class="right">
						Results : <span style='color:blue'><?=number_format($total)?></span> / 
						Records : <select style='position:relative; bottom:5px;' name='paging' onchange="document.searchForm.submit();">
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
						<th style='min-width:100px'><? echo trsLang('방문자명','visitor_name');;?></a></th>
						<th style='min-width:120px'><? echo trsLang('소속','belongtext');;?></a></th>
						<th style='min-width:120px' ><? echo $_LANG_TEXT["checkdatetext"][$lang_code];?></th>
						<th class='cls_cfg_in_available_dt' style='min-width:120px' ><?=$_LANG_TEXT["inlimitdatetext"][$lang_code];?></th>
						<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["indatetext"][$lang_code];?></th>
						<th style='min-width:120px'  class="cls_cfg_inout_info"><?=$_LANG_TEXT["outdatetext"][$lang_code];?></th>
						<th style='min-width:80px'><?=$_LANG_TEXT["scancentertext"][$lang_code];?></th>
						<th style='min-width:80px' ><?=$_LANG_TEXT["devicegubuntext"][$lang_code];?></th>
						<th style='min-width:130px'><?=$_LANG_TEXT["serialnumbertext"][$lang_code];?></th>
						<th style='min-width:120px' ><? echo trsLang('임직원','executives');?></th>
						<th style='min-width:100px' ><? echo trsLang('임직원 소속','employee_affiliation');?></th>
						<th style='min-width:200px' ><? echo trsLang('전자문서번호','electronic_payment_document_number');?></th>
						<th style='min-width:100px' >USB <? echo trsLang('관리번호','managenumber');?></th>
						<th style='min-width:80px' ><?=$_LANG_TEXT["progressstatustext"][$lang_code];?></th>
						<th style='min-width:80px' ><?=$_LANG_TEXT["checkresulttext"][$lang_code];?></th>
						<th style='min-width:70px' ><?=$_LANG_TEXT["scanfilecount"][$lang_code];?></th>
						<?if($_P_CHECK_FILE_SEND_TYPE !="N") {?>
						<th style='min-width:70px' ><?=$_LANG_TEXT["importfilecount"][$lang_code];?></th>
						<?}?>
						<th style='min-width:60px' ><?=$_LANG_TEXT["logtext"][$lang_code];?></th>
						<th style='min-width:60px' ><? echo trsLang('PE 제작','madeby_pe');?></th>
					</tr>
					<?php
					if ($result) {
						$images_b_clean = "<img src='{$_www_server}/images/b_clean.png'>";
						$images_w_clean = "<img src='{$_www_server}/images/w_clean.png'>";
						$images_v_clean = "<img src='{$_www_server}/images/v_clean.png'>";
						$images_c_clean = "<img src='{$_www_server}/images/c_clean.png'>";

						while ($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
							$v_wvcs_seq         = $row['v_wvcs_seq'];
							$v_user_seq         = $row['v_user_seq'];
							$v_user_list_seq    = $row['v_user_list_seq'];
							$v_user_belong      = $row['v_user_belong'];
							$v_user_name        = ($_encryption_kind == "1") ? $row['v_user_name_decript'] : aes_256_dec($row['v_user_name']);
							$v_user_phone_no    = ($_encryption_kind == "1") ? $row['v_phone_decript'] : aes_256_dec($row['v_phone']);
							$v_user_email       = ($_encryption_kind == "1") ? $row['v_email_decript'] : aes_256_dec($row['v_email']);
							$v_asset_type       = $row['v_asset_type'];
							$v_scan_center_name = $row['scan_center_name'];
							$v_scan_center_div  = $row['scan_center_div'];

							$check_date         = $row['check_date'];
							$in_available_date  = $row['checkin_available_dt'];
							
							if($in_available_date) {
								$hour = substr($in_available_date,8,2);
								$min  = substr($in_available_date,10,2);
								$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
								$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
							}

							$in_date  = $row['in_date'];
							$out_date = $row['out_date'];

							$v_type   = $row['v_type'];
							$v_sys_sn = $row['v_sys_sn'];

							$manager_name      = ($_encryption_kind == "1") ? $row['manager_name_decript'] : aes_256_dec($row['manager_name']);
							$manager_name_en   = $row['manager_name_en'];
							$manager_dept      = $row['manager_dept'];
							$str_manager_name  = $manager_name . (empty($manager_name_en) ? "" : " ({$manager_name_en})");

							$weak_cnt          = $row['weak_cnt'];
							$virus_cnt         = $row['virus_cnt'];
							$file_bad_cnt      = $row['file_bad_cnt'];		
							$scan_file_cnt     = $row['scan_file_cnt'];
							$disk_cnt          = $row['disk_cnt'];
							$import_file_cnt   = $row['import_file_cnt'];
							$vacc_scan_count   = $row['vacc_scan_count'];	//바이러스검사파일
							
							//파일정보를 서버로 전송하는 경우는 바이러스 검사파일수 대신 전송된 파일정보수를 표시해 준다.
							if ($scan_file_cnt > 0) {
								$vacc_scan_count = $scan_file_cnt;
							}

							$os_ver_name       = $row['os_ver_name'];
							$usb_mgt_no        = $row['label_value'];
							$wvcs_authorize_yn = $row['wvcs_authorize_yn'];
							$elec_doc_number   = $row['elec_doc_number'];
							$str_vcs_status    = $_CODE['vcs_status'][$row['vcs_status']];
							$make_winpe        = ($row['make_winpe'] == "1") ? trsLang('제작','produce_text') : "";

							$check_result      = "";
							
							//위변조의심
							if (in_array("BAD_EXT", $_CODE_INSPECT_OPTION)) {
								$check_result .= ($file_bad_cnt > 0) ? $images_b_clean : $images_c_clean;
							}

							if (in_array("WEAK", $_CODE_INSPECT_OPTION)) {
								$check_result .= ($weak_cnt > 0) ? $images_w_clean : $images_c_clean;
							}

							if (in_array("VIRUS", $_CODE_INSPECT_OPTION)) {
								$check_result .= ($virus_cnt > 0) ? $images_v_clean : $images_c_clean;
							}

							$param_enc      = ParamEnCoding("v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);
							$view_param_enc = ParamEnCoding("view_src=RESULT_LIST&page=".$page."&v_wvcs_seq=".$v_wvcs_seq.($param==""? "":"&").$param);
							$view_page      = ($v_asset_type == 'NOTEBOOK') ? "result_view_pc.php" : "result_view_storage.php";
							$view_url       = "./{$view_page}?enc={$view_param_enc}";
					?>	
					<tr style='text-align:center'>
						<td><?php echo $no--; ?></td>
						<td>
							<?
							//방문객 페이지 접근권한 체크
							$send_page = ($v_scan_center_div != "IDC") ? "access_info.php" : "access_info_idc.php";
							$person_enc    = paramEncoding("v_user_list_seq={$v_user_list_seq}");
							$person_office = "{$_www_server}/user/access_info.php?enc={$person_enc}";
							$person_idc    = "{$_www_server}/user/access_info_idc.php?enc={$person_enc}";

							if ($v_scan_center_div != "IDC") {
								if ($auth_office) {
									echo "<a class='text_link' onclick=\"sendPostForm('{$person_office}')\" >{$v_user_name}</a>";
								} else {
									echo $v_user_name;
								}
							} else {
								if ($auth_idc) {
									echo "<a class='text_link' onclick=\"sendPostForm('{$person_idc}')\" >{$v_user_name}</a>";
								} else if ($auth_office) {
									echo "<a class='text_link' onclick=\"sendPostForm('{$person_office}')\" >{$v_user_name}</a>";
								} else {
									echo $v_user_name;
								}
							}
							?>
						</td>
						<td><?=$v_user_belong?></td>
						<td><a onclick="sendPostForm('<?=$view_url?>')" class='text_link'><?=$check_date?></a></td>
						<td class='cls_cfg_in_available_dt'><span name='in_available_date'><?=$in_available_date?></span></td>
						<td class="cls_cfg_inout_info"><span name='in_date'><?=$in_date?></span></td>
						<td class="cls_cfg_inout_info"><span name='out_date'><?=$out_date?></span></td>
						<td><?=$v_scan_center_name?></td>
						<td><?=$os_ver_name?></td>
						<td><?=$v_sys_sn?></td>
						<td><?=$str_manager_name?></td>
						<td><?=$manager_dept?></td>
						<td><?=$elec_doc_number?></td>
						<td><?=$usb_mgt_no?></td>
						<td><a onclick="sendPostForm('<?=$view_url?>')" class='text_link'><?=$str_vcs_status?></a></td>
						<td onClick="event.stopPropagation();"><a href="javascript:void(0)" onClick="return popUserVcsView('<?=$v_wvcs_seq?>');"><?=$check_result?></a></td>
						<?php
						if ($_ck_user_level != "SECURITOR_S") {
						?>
						<td onClick="event.stopPropagation();" class="cls_cfg_inout_info">
							<span name='btnin' <?if($in_date > "") echo "style='display:none'";?>><a href='javascript:' onClick="return ResultCheckInSubmit2(this);" class='btn20 cyan' ><?=$_LANG_TEXT["btnin"][$lang_code]?></a></span>
							<span name='btnincancel' <?if($in_date == "") echo "style='display:none'";?>><a href='javascript:' onClick="return ResultCheckInSubmit2(this);" class='btn20 gray' ><?=$_LANG_TEXT["btncancel"][$lang_code]?></a></span>
						</td>
						<td onClick="event.stopPropagation();" class="cls_cfg_inout_info">
							<span name='btnout' <?if($in_date=="" || $out_date > "") echo "style='display:none'";?>><a href='javascript:' onClick="return ResultCheckOutSubmit2(this);" class='btn20 orange' ><?=$_LANG_TEXT["btnout"][$lang_code]?></a></span>
							<span name='btnoutcancel' <?if($in_date=="" || $out_date=="") echo "style='display:none'";?>><a href='javascript:' onClick="return ResultCheckOutSubmit2(this);" class='btn20 gray'><?=$_LANG_TEXT["btncancel"][$lang_code]?></a></span>
						</td>
						<?php
						}
						?>
						<td><?=number_format($scan_file_cnt);?></td>
						<?php
						if ($_P_CHECK_FILE_SEND_TYPE !="N") {
							$str_file_cnt = number_format($import_file_cnt);
							echo "<td>{$str_file_cnt}</td>";
							/*<!--<td  onClick="event.stopPropagation();"><a href='javascript:void(0)' onClick="return popUserInFileList('<?=$v_wvcs_seq?>','USER_FILE_LIST');" ><?=number_format($import_file_cnt);?></a></td>-->*/
						}
						?>
						<td onClick="event.stopPropagation();"><a href='javascript:' onClick="return popUserVcsLog('<?=$v_user_seq?>','<?=$vv_user_name?>','<?=$v_notebook_key?>','<?=$v_asset_type?>');" class='btn_link'><?=$_LANG_TEXT["btnview"][$lang_code]?></a></td>
						<td><?=$make_winpe?></td>
					</tr>
					<?php
						}
					}

					if ($total < 1) {
						$str_no_data = $_LANG_TEXT["nodata"][$lang_code];

						echo "<tr><td colspan=\"20\" align=\"center\">{$str_no_data}</td></tr>";
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