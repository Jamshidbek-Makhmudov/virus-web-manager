<?php
$page_name = "user_list";
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
$useyn = $_REQUEST[useyn];
$orderby = $_REQUEST[orderby];		// 정렬순서
$page = $_REQUEST[page];			// 페이지
$paging = $_REQUEST[paging];

if($paging == "") $paging = $_paging;
if($useyn=="") $useyn ="Y";


$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($useyn!="") $param .= ($param==""? "":"&")."useyn=".$useyn;
if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;

//검색 로그 기록
$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

//검사정책가져오기
$_POLICY= getPolicy('file_scan_yn','N');	//파일검사여부
?>
<div id="user_list">
	<div class="container">
		
		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["m_visitor_info"][$lang_code];?></span></h1>

			</div>
			<span class="line"></span>
		</div>
		
		<!--검색폼-->
		<form name="searchForm" action="<?php echo $_SERVER[PHP_SELF]?>" method="GET">
		<input type="hidden" name="page" value="">
		<input type='hidden' name='proc_name' id='proc_name'>
		<table class="search">
		<tr>
			<th><?=$_LANG_TEXT["usersearchtext"][$lang_code];?></th>
			<td>
				<select name="searchopt" id="searchopt">
					<option value=''><?=$_LANG_TEXT["searchkeywordselecttext"][$lang_code];?></option>
					<option value='USER_NAME' <?if($searchopt=="USER_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["visitortext"][$lang_code];?></option>
					<option value='USER_COM_NAME' <?if($searchopt=="USER_COM_NAME") echo "selected=selected"?>><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></option>
					<option value='EMAIL' <?if($searchopt=="EMAIL") echo "selected=selected"?>><?=$_LANG_TEXT["emailtext"][$lang_code];?></option>
					<option value='PHONE' <?if($searchopt=="PHONE") echo "selected=selected"?>><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></option>
				</select>
				<input type="text" name="searchkey" id="searchkey" class="frm_input" value="<?=$searchkey?>" maxlength="50">
				<select name="useyn" id="useyn">
					<option value='A'><?=$_LANG_TEXT["approvedyesnotext"][$lang_code];?></option>
					<option value='Y' <?if($useyn=="Y") echo "selected"?>><?=$_LANG_TEXT["approvedtext"][$lang_code];?></option>
					<option value='N' <?if($useyn=="N") echo "selected"?>><?=$_LANG_TEXT["unapprovedtext"][$lang_code];?></option>
				</select>

				<input type="submit" value="<?=$_LANG_TEXT["btnsearch"][$lang_code];?>" class="btn_submit" onclick="return SearchSubmit(document.searchForm);" >
			</td>
		</tr>
		</table>
		<div class="btn_wrap">
			<? 
				$excel_param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
				$excel_down_url = $_www_server."/user/user_list_excel.php?enc=".$excel_param_enc;
			?>
			<div class="right">
				<a href="#" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')" class="btnexcel" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
			</div>
		</div>
<?
		 if($useyn=="Y" || $useyn=="N"){

			  $search_sql .= " and vu.use_yn = '$useyn' ";
		  }


		  if($searchkey != ""){

			  if($searchopt=="USER_NAME"){
				
				if($_encryption_kind=="1"){

					 $search_sql .= "and dbo.fn_DecryptString(vu.v_user_name) like '%$searchkey%' ";

				  }else if($_encryption_kind=="2"){

					 $search_sql .= " and vu.v_user_name = '".aes_256_enc($searchkey)."' ";
				  }

			  }else if($searchopt=="USER_COM_NAME"){
				
				$search_sql .= " and vc.v_com_name like '%$searchkey%' ";

			  }else if($searchopt=="EMAIL"){

				  if($_encryption_kind=="1"){

					 $search_sql .= "and dbo.fn_DecryptString(vu.v_email) like '%$searchkey%' ";

				  }else if($_encryption_kind=="2"){

					 $search_sql .= " and vu.v_email = '".aes_256_enc($searchkey)."' ";
				  }

			  }else if($searchopt=="PHONE"){

				  $searchkey = preg_replace("/[^0-9-]*/s", "", $searchkey); 

				  if($_encryption_kind=="1"){

					 $search_sql .= "and dbo.fn_DecryptString(vu.v_phone) like '%$searchkey%' ";

				  }else if($_encryption_kind=="2"){

					 $search_sql .= " and vu.v_phone = '".aes_256_enc($searchkey)."' ";
				  }

			  }
		  }
			
			$qry_params = array("search_sql"=> $search_sql);
			$qry_label = QRY_USER_LIST_COUNT;
			$sql = query($qry_label,$qry_params);
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
				$order_sql = " ORDER BY vu.v_user_seq DESC ";
			}
	
			$qry_params = array("end"=> $end,"order_sql"=>$order_sql,"search_sql"=>$search_sql,"start"=>$start);
			$qry_label = QRY_USER_LIST;
			$sql = query($qry_label,$qry_params);
			$result = sqlsrv_query($wvcs_dbcon, $sql); 

			//echo nl2br($sql);
			
			$cnt = 20;
			$iK = 0;

			if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;
		
?>
		<div style='line-height:30px;'>
			Results : <span style='color:blue'><?=number_format($total)?></span> / 
			Records : <select name='paging' onchange="searchForm.submit();">
				<option value='20' <?if($paging=='20') echo "selected";?>>20</option>
				<option value='40' <?if($paging=='40') echo "selected";?>>40</option>
				<option value='60' <?if($paging=='60') echo "selected";?>>60</option>
				<option value='80' <?if($paging=='80') echo "selected";?>>80</option>
				<option value='100' <?if($paging=='100') echo "selected";?>>100</option>
			</select>
		</div>
		</form>
		<!--검색결과리스트-->
		<table class="list" style="margin-top:2px">
		<tr>
			<th style='min-width:60px;width:60px;'><?=$_LANG_TEXT["numtext"][$lang_code];?></th>
			<th style='min-width:180px;width:180px;'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="v_com_name"? "v_com_name desc" : "v_com_name"))?>" class="sort"><?=$_LANG_TEXT["usercompanynametext"][$lang_code];?></a></th>
			<th style='min-width:80px;width:100px;'><a href="<?=$PHP_SELF?>?enc=<?=ParamEnCoding($param.($param? "&":"")."orderby=".($orderby=="v_user_name"? "v_user_name desc" : "v_user_name"))?>"  class="sort"><?=$_LANG_TEXT["visitortext"][$lang_code];?></a></th>
			<th style='width:120px;min-width:120px;'><?=$_LANG_TEXT["contactphonetext"][$lang_code];?></th>
			<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT["emailtext"][$lang_code];?></th>
			<!--<th style='min-width:100px;width:100px;'><?=$_LANG_TEXT["detailviewtext"][$lang_code];?></th>-->
			<!--
			<th style='min-width:200px;text-align:left;padding-left:10px'>
				<?=$_LANG_TEXT["checkstatustext"][$lang_code];?>
				<div class='checkstatus'>
					<span class='checkbar tot'></span> <?=$_LANG_TEXT["allcheckresulttext"][$lang_code];?>
					<span class='checkbar weak'></span> <?=$_LANG_TEXT["weaknessshorttext"][$lang_code];?>
					<span class='checkbar virus'></span> <?=$_LANG_TEXT["virusshorttext"][$lang_code];?>
				</div>
			</th>
			-->
			<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT["allcheckresulttext"][$lang_code];?></th>
			<th style='width:200px;min-width:200px;'><? echo trsLang('취약점발견','weaknessdetectiontext');?></th>
			<th style='width:200px;min-width:200px;'><? echo trsLang('악성코드발견','virusdetectiontext');?></th>
			<?if($_POLICY['file_scan_yn']=="Y"){?>
			<th style='width:200px;min-width:200px;'><? echo trsLang('위변조의심','suspectforgerytext');?></th>
			<?}?>
		</tr>
		
		<?php
				 if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$cnt--;
						$iK++;
						
						$v_user_seq = $row['v_user_seq'];
						$v_com_seq = $row['v_com_seq'];
						$com_name = $row['v_com_name'];

						if($_encryption_kind=="1"){

							$phone_no = $row['v_phone_decript'];
							$email = $row['v_email_decript'];
							$user_name = $row['v_user_name_decript'];

						}else if($_encryption_kind=="2" || $_encryption_kind=="3"){

							$phone_no = aes_256_dec($row['v_phone']);
							$email = aes_256_dec($row['v_email']);
							$user_name = aes_256_dec($row['v_user_name']);
						}
						
						$vcs_cnt = $row['vcs_cnt'];
						$weak_cnt = $row['weak_cnt'];
						$virus_cnt = $row['virus_cnt'];
						$bad_cnt = $row['bad_cnt'];
						
						$weak_bar_width = 0;
						$virus_bar_width = 0;

						if($row['vcs_cnt'] > 0){
							$weak_bar_width = (int)($weak_cnt / $vcs_cnt * 100);
							$virus_bar_width = (int)($virus_cnt / $vcs_cnt * 100);
						};
						

						$view_param_enc1 = ParamEnCoding("page=".$page."&v_user_seq=".$v_user_seq.($param==""? "":"&").$param);
						$view_param_enc2 = ParamEnCoding("page=".$page."&v_com_seq=".$v_com_seq.($param==""? "":"&").$param);
						
						if($com_name=="") $com_name="<span class='blank'></span>";
						if($user_name=="") $user_name="<span class='blank'></span>";
				  ?>	
					<tr>
						<td class="num"><?php echo $no; ?></td>
						<td class="center">
							<!--<a href="javascript:" onclick="return popCompanyVcsSummary('<?=$v_com_seq?>');"><?=$com_name?></a>-->
							<a href='./com_info_view.php?enc=<?=$view_param_enc2?>' class='btn_link'><?=$com_name?></a>
						</td>
						<td class="center">
							<!--<a href="javascript:" onclick="return popUserVcsSummary('<?=$v_user_seq?>');"><?=$user_name?></a>-->
							<a href='./user_info_view.php?enc=<?=$view_param_enc1?>' class='btn_link'><?=$user_name?></a>
						</td>
						<td class="center"><?=$phone_no?></td>
						<td class="center"><?=$email?></td>
						<!--<td class="center">
							<a href='./com_info_view.php?enc=<?=$view_param_enc2?>' class='btn20 gray'><?=$_LANG_TEXT["usercompanytext"][$lang_code]?></a>
							<a href='./user_info_view.php?enc=<?=$view_param_enc1?>' class='btn20 gray'><?=$_LANG_TEXT["visitortext"][$lang_code]?></a>
						</td>-->
						<!--
						<td class="center">
							<div class='totbar'><?=number_format($vcs_cnt)?></div>
							<div style='float:left;display:inline;'>
								<div class='weakbar' style='width:<?=$weak_bar_width?>px;'>
									<span style='width:<?=$weak_bar_width+45?>px;'><?=$weak_bar_width?>% (<?=number_format($weak_cnt)?>)</span>
								</div>
								<div class='virusbar' style='width:<?=$virus_bar_width?>px;'>
									<span style='width:<?=$virus_bar_width+45?>px;'><?=$virus_bar_width?>% (<?=number_format($virus_cnt)?>)</span>
								</div>
							</div>
						</td>
						-->
						<td class="center"><?=number_format($vcs_cnt)?></td>
						<td class="center"><?=number_format($weak_cnt)?></td>
						<td class="center"><?=number_format($virus_cnt)?></td>
						<?if($_POLICY['file_scan_yn']=="Y"){?>
						<td class="center"><?=number_format($bad_cnt)?></td>
						<?}?>
					</tr>
					<?php
					
						$no--;
					}
					
				}

			 
				if($total < 1) {
					
				?>
					<tr>
						<td colspan="7" align="center"><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
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
	</div>

</div>
<div id='popContent' style='display:none'></div>
<?php

if($result) sqlsrv_free_stmt($result);  
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>