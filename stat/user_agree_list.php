<?php
$page_name = "user_agree_list";
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

//검색 로그 기록

$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}

$Model_Stat = new Model_Stat();
?>
<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT['user_agree_list'][$lang_code]?></span></h1>
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

				<div class='col header'><? echo trsLang('검사장','scancentertext');?></div>
				<div class='col'>
						<select name='scan_center_code' id='scan_center_code'>
							<option value=''><?=$_LANG_TEXT["alltext"][$lang_code];?></option>
							<?php
							$Model_manage = new Model_manage;
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
			</td>
		</tr>
		<?
		//검색키워드목록
		$searchopt_list = array(
			"v_user_name"=>trsLang("이름","nametext")
			,"v_user_belong"=>trsLang("소속","belongtext")
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
					<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/stat/user_agree_list.php'">

			</td>
		</tr>

		</table>

	
		<?php 	
		//검색항목
		 $search_sql = "";
		if ($start_date != "" && $end_date != "") {
				$search_sql .= " and v2.visit_date between '" . str_replace('-', '', $start_date) . "000000' AND '" . str_replace('-', '', $end_date) . "235959' ";
			}

				if($scan_center_code !=""){ 

				$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
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

			}

		}



		

			$args = array("search_sql" => $search_sql);
			$total = $Model_Stat->getUserAgreeListCount($args);
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
			$order_sql = " ORDER BY v2.v_user_list_seq DESC ";
		}
									
      $args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);
			$Model_Stat->SHOW_DEBUG_SQL = false;

			$result = $Model_Stat->getUserAgreeList($args);
					
		$cnt = 20;
		$iK = 0;
		$classStr = "";


		
		  //excel file name while downloading
			$excel_name = $_LANG_TEXT['work_log'][$lang_code].'_('.$_LANG_TEXT['user_agree_list'][$lang_code].')';?>
		
			<div class="btn_wrap right" style='margin-bottom:10px;'>
				<? $excel_down_url = $_www_server . "/stat/user_agree_list_excel.php?enc=" . ParamEnCoding($param); ?>
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
			<th style='width:250px'><? echo trsLang('방문일자','visitdatetext');?></th>
			<th style='width:150px'><?=$_LANG_TEXT['nametext'][$lang_code]?></th>
			<th style='width:250px'><? echo trsLang('소속','belongtext');?></th>
			<th style='min-width:100px'><? echo trsLang('검사장','inspection_center');?></th>
			<th style='width:250px'><? echo trsLang('개인정보 동의서 작성여부','personel_info_consent_text');?></th>
			<th style='width:250px'><? echo trsLang('개인정보 동의서 작성일자','personel_info_date_text');?></th>
			<th style='width:250px'><? echo trsLang('정보보호서약서 작성여부','info_protec_pledge_text');?></th>
			<th style='width:250px'><? echo trsLang('정보보호서약서 작성일자','info_protec_pledge_date_text');?></th>

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
				$visit_date = setDateFormat($row['visit_date']);
	
				$v_agree_date = setDateFormat($row['v_agree_date'],'Y-m-d H:i');
				if( $row['v_agree_yn']=="Y"){
					$v_agree_yn = trsLang('동의','agree_text');
				}else{
					$v_agree_yn = trsLang('미동의','dis_agree_text');
					
				};
				
				$security_agree_date = setDateFormat($row['security_agree_date'],'Y-m-d H:i');
				if( $row['security_agree_yn']=="Y"){
				$security_agree_yn = "<a href='javascript:void(0)' onclick='popSecurityAgree()' data-seq='{$v_user_list_seq}' class='text_link'>".trsLang('작성완료','writeoktext')."</a>";
			}else{
				$security_agree_yn = trsLang('미작성','notwritten');

			};

			

		  ?>	
			<tr>
				<td><?php echo $no; ?></td>
				<td><?=$visit_date?></td>
				<td><?= $v_user_name ?><? if($v_user_name_en != "") echo " ($v_user_name_en)"; ?></td>
				<td><?=$v_user_belong?></td>
				<td><?=$in_center_name?></td>


				<td><?=$v_agree_yn?></td>
				<td><?=$v_agree_date?></td>

				<td><?=$security_agree_yn?></td>
				<td><?=$security_agree_date?></td>
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