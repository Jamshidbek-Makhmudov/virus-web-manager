<?php
$page_name = "file_signature";
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

$sign_id_seq = intVal($_REQUEST["sign_id_seq"]);
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST["orderby"];		//정렬

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if($sign_id_seq <> "") {

		if($searchkey != "" && $searchopt != "") {
					
			if($searchopt=="ip"){

				$search_sql .= " and file_id like '%$searchkey%' "; //org

			}else if($searchopt=="idorname"){  //farq check

				$search_sql .= " and str_name like '%$searchkey%' ";
			
			}else if($searchopt=="Y"){

		$search_sql .= " and use_yn like '%$searchkey%' ";
	} else if ($searchopt == "N") {

					$search_sql .= " and use_yn like '%$searchkey%' ";
		}
		}

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby ";
		} else {
			$order_sql = " ORDER BY sign_id_seq DESC ";
		}
		//QRY_FILE_SIGNATURE_INFO
		$qry_label = QRY_FILE_SIGNATURE_INFO;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"sign_id_seq"=>$sign_id_seq);
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$sign_id_seq = $row['sign_id_seq'];
		$file_id = $row['file_id'];
		$str_name = $row['str_name'];
		$use_yn = $row['use_yn'];
		
		$create_date = $row['create_date'];
		
		$rnum = $row['rnum']; //farq bu db da yoq
			

	//QRY_FILE_SIGNATURE_PREV_NEXT
		//이전,다음
		$prev_sql = " AND rnum > '$rnum' ORDER BY rnum asc";
		$qry_label = QRY_FILE_SIGNATURE_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$prev_sql);
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$prev_sign_id_seq = $row['sign_id_seq'];
		// echo nl2br($sql);
		
		$next_sql = " AND  rnum < '$rnum' ORDER BY rnum desc ";
		$qry_label = QRY_FILE_SIGNATURE_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$next_sql);
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$next_sign_id_seq = $row['sign_id_seq'];

		// echo nl2br($sql);
}

?>
<script type="text/javascript">
//$(document).ready(function() {
//$(".search_select").select2();
//});
</script>
<div id="oper_input">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				<h1><span id='page_title'><?=$_LANG_TEXT["filesignature"][$lang_code];?></span></h1>
			
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer'
				onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="fromFileSig" id="fromFileSig" method="post">
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="sign_id_seq" id="sign_id_seq" value="<?php echo $sign_id_seq; ?>">
			<table class="view">
				<tr>
					<th><label for='file_id'><?=$_LANG_TEXT["fileidnntext"][$lang_code];?></label></th>
					<td>
						<input type="text" name="file_id" id="file_id" class="frm_input" value="<?php echo $file_id; ?>"
							style="width:90%" maxlength="20">
					</td>
				</tr>
				<tr class="bg">
					<th><label for='use_yn'><?=$_LANG_TEXT["useyesnonntext"][$lang_code];?></label></th>
					<td>
						<select name='use_yn' id="use_yn" class='search_select'>
							<option value="Y" <?if($use_yn=="Y" ) echo 'selected="selected"' ;?>>
								<? echo $_LANG_TEXT['useyesnntext'][$lang_code]?>
							</option>
							<option value="N" <?if($use_yn=="N" ) echo 'selected="selected"' ;?> >
								<? echo $_LANG_TEXT['usenonntext'][$lang_code]?>
							</option>

						</select>
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["filesignature"][$lang_code];?></th>
					<td>
						<input type="text" name="str_name" id="str_name" class="frm_input" value="<?php echo $str_name; ?>"
							style="width:90%" maxlength="200">
					</td>
				</tr>

			</table>



			<div class="btn_wrap">
				<?php
		if ($sign_id_seq != "") {
?>
				<!-- agar  prev nect buttojnlari ishlamasa "sign_id_seq=" shu yerdagi  qoshtirnoqni tekshir auto savda xato beradi -->
				<div class="left display-none">
					<a href="<?if(empty($prev_sign_id_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("sign_id_seq=".$prev_sign_id_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
					<a href="<?if(empty($next_sign_id_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("sign_id_seq=".$next_sign_id_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
				</div>
				<?php	}?>
				<div class="right">
					<a href="./file_signature.php" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<?php
					if ($sign_id_seq == "") {
?>
					<a href="#" onclick="FileSignatureSubmit('CREATE')" class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
					<?php
					}else{
?>
					<a href="#" onclick="FileSignatureSubmit('UPDATE')" class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
					<!-- <a href="#" onclick="FileSignatureSubmit('DELETE')" class="btn"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a> -->
					<a href="javascript:void(0)" class="btn required-delete-auth hide" onclick="event.stopPropagation();"><span onclick="FileSignatureDelete('<?=$sign_id_seq?>')"
							style='cursor:pointer;'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></a>


					<?php
					}
?>
					<a href="./file_signature_reg.php" class="btn" id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>
				</div>
			</div>

		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>