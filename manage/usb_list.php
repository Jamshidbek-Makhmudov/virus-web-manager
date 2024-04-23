<?php
$page_name = "usb_list";
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

$searchopt = $_REQUEST["searchopt"];	// 검색옵션
$searchkey = $_REQUEST["searchkey"];	// 검색어

$page = intval($_REQUEST[page]);	
$paging = $_REQUEST[paging];
$orderby = $_REQUEST[orderby];
if($paging == "") $paging = $_paging;

if($start_date=="") $start_date = date( "Y-m-d", strtotime( date("Y-m-d")." -1 month" ) );
if($end_date=="") $end_date = date("Y-m-d");

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if($paging!="") $param .= ($param==""? "":"&")."paging=".$paging;
//검색 로그 기록

$proc_name = $_POST['proc_name'];
if($proc_name != ""){
	$work_log_seq = WriteAdminActLog($proc_name,'SEARCH');
}


$Model_manage=new Model_manage();
?>


<div id="oper_list">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["managesecurityusb"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<!--검색폼-->
		<form name="searchForm" id="searchForm" method="POST">
			<input type="hidden" name="page" value="<?=$page?>">
			<input type='hidden' name='proc_name' id='proc_name'>
			<table class="search">

				<tr>
					<th><?=$_LANG_TEXT['usersearchtext'][$lang_code]?></th>
					<td>

						<select name="searchopt" id="searchopt">
							<option value="" <?php if($searchopt == "") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['totallist'][$lang_code]?></option>
							
							<option value="user_id" <?php if($searchopt == "user_id") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['user_id_key_text'][$lang_code]?>(<?=$_LANG_TEXT['key_text'][$lang_code]?>)</option>
							<option value="usb_id" <?php if($searchopt == "usb_id") { echo ' selected="selected"'; } ?>>
								<?=$_LANG_TEXT['usb_id_text'][$lang_code]?></option>

						</select>

						<input type="text" name="searchkey" id="searchkey" value="<?=$searchkey?>" class="frm_input"
							style="width:60%" onKeyPress="if(event.keyCode==13){return SearchSubmit(document.searchForm);}"
							maxlength="50">
						<input type="submit" value="<?=$_LANG_TEXT['btnsearch'][$lang_code]?>" class="btn_submit"
							onclick="SearchSubmit(document.searchForm);">

							<input type="button" value="<? echo trsLang('초기화','btnclear');?>" class="btn_submit_no_icon" onclick="location.href='<? echo $_www_server?>/manage/usb_list.php'">

					</td>
				</tr>
			</table>

			<div class="btn_confirm">
				<a href="./usb_reg.php" class="btn required-create-auth hide"><?=$_LANG_TEXT['register'][$lang_code]?></a>
			</div>

			<?php

			
if($searchkey != ""){

		if($searchopt=="user_id"){

		$search_sql .= " and user_id like '%$searchkey%' ";

		}else if($searchopt == "usb_id"){

		$search_sql .= " and usb_id like '%$searchkey%' ";

		}
	}

 $Model_manage->SHOW_DEBUG_SQL = false;
				$args = array("search_sql"=>$search_sql);
	
				$total=$Model_manage->getUsbListCount($args); 
	
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
					$order_sql = " ORDER BY usb_seq DESC ";
		
				}	

				$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"end"=>$end,"start"=>$start);			
		   //$Model_manage->SHOW_DEBUG_SQL = true;	
				$result = $Model_manage->getUsbList($args);

$cnt = 20;
$iK = 0;
$classStr = "";
				
 $excel_name=$_LANG_TEXT['managesecurityusb'][$lang_code];	
			?>

			<div class="btn_wrap" style='margin-right:120px;'>
				<? $excel_down_url = $_www_server."/manage/usb_list_excel.php?enc=".ParamEnCoding($param);?>
				<div class="right">
					<a href="javascript:void(0)" id="usb_list_excel" class="btnexcel required-print-auth hide"
						onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<?=$excel_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
				</div>

				<div class="btn_wrap" style='margin-right:120px;'>
					<div style='line-height:30px;' class="left">
						Results : <span style='color:blue'><?=number_format($total)?></span> /
						Records : <select name='paging' onchange="searchForm.submit();">
							<option value='20' <?if($paging=='20' ) echo "selected" ;?>>20</option>
							<option value='40' <?if($paging=='40' ) echo "selected" ;?>>40</option>
							<option value='60' <?if($paging=='60' ) echo "selected" ;?>>60</option>
							<option value='80' <?if($paging=='80' ) echo "selected" ;?>>80</option>
							<option value='100' <?if($paging=='100' ) echo "selected" ;?>>100</option>
						</select>
					</div>
				</div>
			</div>

		</form>

		<!--검색결과리스트-->
		<table class="list" style="margin-top:10px">

			<tr>
				<th class="num" style='width:100px;min-width:100px;'><?=$_LANG_TEXT['numtext'][$lang_code]?></th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['user_id_key_text'][$lang_code]?>(<?=$_LANG_TEXT['key_text'][$lang_code]?>)</th>
				<th style='width:200px;min-width:200px;'><?=$_LANG_TEXT['usb_id_text'][$lang_code]?></th>
				<th style='width:100px;min-width:100px;'><?=$_LANG_TEXT['deletetext'][$lang_code]?></th>
			</tr>




			<?php


  if($result){
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

		$cnt--;
		$iK++;
					$usb_seq = $row['usb_seq'];
					$usb_id = $row['usb_id'];
					$user_id = $row['user_id'];
					$create_date = $row['create_date'];
					$access_date = $row['access_date'];
					$access_emp_seq = $row['access_emp_seq'];
		
$param_enc = ParamEnCoding("usb_seq=" . $usb_seq . ($param ? "&" : "") . $param);
	?>

			<tr onclick="sendPostForm('./usb_reg.php?enc=<?=$param_enc?>')" style='cursor:pointer'>
				<td><?=$no?></td>
				<td><?=$user_id?></td>
				<td><?=$usb_id?></td>


				<td onclick="event.stopPropagation();"><span onclick="UsbListDelete('<?=$usb_seq?>')"
						style='cursor:pointer;' class=' required-delete-auth hide'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></td>
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
				<td colspan="8" align="center"><?php echo $_LANG_TEXT["noneresult"][$lang_code]; ?></td>
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
<!--  -->

<script>
document.addEventListener('DOMContentLoaded', function() {
	const selectElement = document.getElementById('searchopt');
	const inputElement = document.getElementById('searchkey');

	selectElement.addEventListener('change', function() {
		// Get the selected option's value
		const selectedOptionValue = selectElement.value;

		if (selectedOptionValue === 'Y' || selectedOptionValue === 'N') {
			// Autofill the input for Option 2 and Option 3
			inputElement.value = selectedOptionValue;
		} else {
			// Clear the input for other options (e.g., Option 1)
			inputElement.value = '';
		}
	});
});
</script>
<?php


include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>