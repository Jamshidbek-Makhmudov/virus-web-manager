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

$usb_seq = intVal($_REQUEST["usb_seq"]);

$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST["orderby"];		//정렬


$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;

$Model_manage=new Model_manage();

if($usb_seq <> "") {

	if($searchkey != ""){

		if($searchopt=="user_id"){

		$search_sql .= " and user_id like '%$searchkey%' ";

		}else if($searchopt == "usb_id"){

		$search_sql .= " and usb_id like '%$searchkey%' ";

		}
	}

	if($orderby != "") {
					$order_sql = " ORDER BY $orderby";
				} else {
					$order_sql = " ORDER BY usb_seq DESC ";
		
				}	
	$args = array("order_sql" => $order_sql,"search_sql"=>$search_sql, "usb_seq" => $usb_seq);
	$result = $Model_manage->getUsbListInfo($args);

	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

$usb_seq = $row['usb_seq'];
$usb_id = $row['usb_id'];
$user_id = $row['user_id'];

$create_date = setDateFormat($row['create_date'], "Y-m-d H:i");
$access_date = setDateFormat($row['access_date'], "Y-m-d H:i");

$access_emp_seq = $row['access_emp_seq'];
$emp_name=aes_256_dec($row['emp_name']);
$rnum = $row['rnum'];

	//echo nl2br($sql);
	//이전,다음
	$prev_sql = " AND rnum > '$rnum' ORDER BY rnum asc";

	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$prev_sql);
	$result = $Model_manage->usbListPrevNext($args);
	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$prev_usb_seq = $row['usb_seq'];
		
	$next_sql = " AND  rnum < '$rnum' ORDER BY rnum desc ";
	$args = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$next_sql);
	$result = $Model_manage->usbListPrevNext($args);
	$row = @sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);
	$next_usb_seq = $row['usb_seq'];

		
}

?>
<script type="text/javascript">

</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["managesecurityusb"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer'
				onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="fromUsbList" id="fromUsbList" method="post">
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="usb_seq" id="usb_seq" value="<?php echo $usb_seq; ?>">
			<table class="view">
				<tr>
					<th><label for='user_id'><?=$_LANG_TEXT['user_id_key_text'][$lang_code]?>(<?=$_LANG_TEXT['key_text'][$lang_code]?>)</label></th>
					<td>
						<input type="text" name="user_id" id="user_id" class="frm_input" value="<?php echo $user_id; ?>"
							style="width:90%" maxlength="20">
					</td>
				</tr>
				<tr class='bg'>
					<th><label for='usb_id'><?=$_LANG_TEXT['usb_id_text'][$lang_code]?></label></th>
					<td>
						<input type="text" name="usb_id" id="usb_id" class="frm_input" value="<?php echo $usb_id; ?>"
							style="width:90%" maxlength="20">
					</td>
				</tr>
				<? if($usb_seq !=''){ ?>
					<tr>
						<th><label for='access_date'> <?= trsLang('수정일자','updated_date_text_value') ?> </label></th>
						<td>
							
							<?php echo $access_date; ?>
							
					</tr>
					<tr class='bg'>
						<th><label for='access_emp_seq'> <?= trsLang('처리자','manager_text') ?> </label></th>
						<td>
						
								
						
								<?php echo $emp_name; ?>
								
							
					</tr>
				<? } ?>

			</table>

			<div class="btn_wrap">
				<?php
		if ($usb_seq != "") {
?>
				<div class="left display-none">
					<a href="<?if(empty($prev_usb_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("usb_seq=".$prev_usb_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
					<a href="<?if(empty($next_usb_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("usb_seq=".$next_usb_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
				</div>
				<?php	}?>

				<div class="right">
					<a href="javascript:void(0)" onclick="sendPostForm('./usb_list.php')" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<?php
					if ($usb_seq == "") {
?>
					<a href="javascript:void(0)" onclick="UsbListSubmit('CREATE')"
						class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
					<?php
					}else{
?>
					<a href="#" onclick="UsbListSubmit('UPDATE')"
						class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
					<a href="#" onclick="UsbListSubmit('DELETE')"
						class="btn required-update-auth hide"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a>
					


					<?php
					}
?>
					<a href="./usb_reg.php" class="btn" id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>
				</div>
			</div>

		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>