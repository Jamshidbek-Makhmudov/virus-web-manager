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

$sign_map_seq = intVal($_REQUEST["sign_map_seq"]);
$searchopt = $_REQUEST['searchopt'];	// 검색옵션
$searchkey = $_REQUEST['searchkey'];	// 검색어
$orderby = $_REQUEST["orderby"];		//정렬

$param = "";
if($searchopt!="") $param .= ($param==""? "":"&")."searchopt=".$searchopt;
if($searchkey!="") $param .= ($param==""? "":"&")."searchkey=".$searchkey;
if($orderby!="") $param .= ($param==""? "":"&")."orderby=".$orderby;


if($sign_map_seq <> "") {

		if($searchkey != "" && $searchopt != "") {
					
			if($searchopt=="ext_name"){


				$search_sql .= " and ext_name like '%$searchkey%' "; //org

			}else if($searchopt=="str_name"){  //farq check

	$search_sql .= " and str_name like '%$searchkey%' ";
			
			}else if($searchopt=="file_id"){


				$search_sql .= " and file_id like '%$searchkey%' "; //org
			}
		}

		if($orderby != "") {
			$order_sql = " ORDER BY $orderby ";
		} else {
			$order_sql = " ORDER BY sign_map_seq DESC ";
		}
		
		$qry_label = QRY_SIGNATURE_MAPPING_INFO;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"sign_map_seq"=>$sign_map_seq);
		$sql = query($qry_label,$qry_params);
		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);

		$sign_map_seq = $row['sign_map_seq'];
		$ext_name = $row['ext_name'];
		$file_id = $row['file_id'];
		$str_name = $row['str_name'];
		$search_flag = $row['search_flag'];
		$fake_check = $row['fake_check'];
	
		$create_date = $row['create_date'];
		
		$rnum = $row['rnum']; //farq bu db da yoq

		//echo nl2br($sql);
		//이전,다음
		$prev_sql = " AND rnum > '$rnum' ORDER BY rnum asc";
		$qry_label = QRY_SIGNATURE_MAPPING_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$prev_sql);
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$prev_sign_map_seq = $row['sign_map_seq'];
		
		$next_sql = " AND  rnum < '$rnum' ORDER BY rnum desc ";
		$qry_label = QRY_SIGNATURE_MAPPING_PREV_NEXT;
		$qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql,"prev_next_sql"=>$next_sql);
		$sql = query($qry_label,$qry_params);

		$result = @sqlsrv_query($wvcs_dbcon, $sql);
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
		$next_sign_map_seq = $row['sign_map_seq'];

		
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
				<h1><span id='page_title'><?=$_LANG_TEXT["signaturemapping"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>
		<div class="page_right"><span style='cursor:pointer'
				onclick="history.back();"><?=$_LANG_TEXT["btngobeforepage"][$lang_code];?></span></div>

		<!--등록폼-->
		<form name="fromSigMap" id="fromSigMap" method="post">
			<input type='hidden' name='proc' id='proc'>
			<input type='hidden' name='proc_name' id='proc_name'>
			<input type="hidden" name="sign_map_seq" id="sign_map_seq" value="<?php echo $sign_map_seq; ?>">
			<table class="view">
				<tr>
					<th><label for='ext_name'><?=$_LANG_TEXT["file_ext"][$lang_code];?></label></th>
					<td>
						<input type="text" name="ext_name" id="ext_name" class="frm_input" value="<?php echo $ext_name; ?>"
							style="width:90%" maxlength="20">
					</td>
				</tr>
				<tr>
					<th><label for='file_id'><?=$_LANG_TEXT["fileidnntext"][$lang_code];?></label></th>
					<td>
						<input type="text" name="file_id" id="file_id" class="frm_input" value="<?php echo $file_id; ?>"
							style="width:90%" maxlength="20">
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT["id_name"][$lang_code];?></th>
					<td>
						<input type="text" name="str_name" id="str_name" class="frm_input" value="<?php echo $str_name; ?>"
							style="width:90%" maxlength="200">
					</td>
				</tr>
				<tr  style="display:none;">
					<th>
						<!-- ㄷㄷ -->
						<select name='search_flag' id="search_flag" class='search_select'>
							<option value="1" <?if($search_flag=="1" ) echo 'selected="selected"' ;?>>
								<? echo $_LANG_TEXT['searchtargetinnntext'][$lang_code]?>
							</option>
							<option value="0" <?if($search_flag=="0" ) echo 'selected="selected"' ;?> >
								<? echo $_LANG_TEXT['searchtargetoutnntext'][$lang_code]?>
							</option>

						</select>
					</th>
					<td>
						<!-- ㄷㄷ 시그니처여부포함 -->
						<select name='fake_check' id="fake_check" class='search_select'>
							<option value="1" <?if($fake_check=="1" ) echo 'selected="selected"' ;?>>
								<? echo $_LANG_TEXT['signatureinnntext'][$lang_code]?>
							</option>
							<option value="0" <?if($fake_check=="0" ) echo 'selected="selected"' ;?> >
								<? echo $_LANG_TEXT['signatureoutnntext'][$lang_code]?>
							</option>

						</select>
					</td>
				</tr>



			</table>



			<div class="btn_wrap">
				<?php
		if ($sign_map_seq != "") {
?>
				<div class="left display-none">
					<a href="<?if(empty($prev_sign_map_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("sign_map_seq=".$prev_sign_map_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnPrev'><?=$_LANG_TEXT["btnprev"][$lang_code];?></a>
					<a href="<?if(empty($next_sign_map_seq)){?>javascript:alert(qnodata[lang_code])<?}else{ echo $_SERVER['PHP_SELF']."
						?enc=".ParamEnCoding("sign_map_seq=".$next_sign_map_seq.($param ? " &" : "" ).$param); }?>"
						class="btn" id='btnNext'><?=$_LANG_TEXT["btnnext"][$lang_code];?><a>
				</div>
				<?php	}?>
				<div class="right">
					<a href="javascript:void(0)" onclick="sendPostForm('./signature_mapping.php')" class="btn" id="btnList"><?=$_LANG_TEXT["btnlist"][$lang_code];?></a>
					<?php
					if ($sign_map_seq == "") {
?>
					<a href="javascript:void(0)" onclick="SignatureMappingSubmit('CREATE')"
						class="btn required-create-auth hide"><?=$_LANG_TEXT["btnregist"][$lang_code];?></a>
					<?php
					}else{
?>
					<a href="#" onclick="SignatureMappingSubmit('UPDATE')"
						class="btn required-update-auth hide"><?=$_LANG_TEXT["btnsave"][$lang_code];?></a>
					<!-- <a href="#" onclick="SignatureMappingSubmit('DELETE')"
						class="btn"><?=$_LANG_TEXT["btndelete"][$lang_code];?></a> -->
					<a href="javascript:void(0)" class="btn required-delete-auth hide" onclick="event.stopPropagation();"><span onclick="SignatureMappingDelete('<?=$sign_map_seq?>')"
							style='cursor:pointer;'><?=$_LANG_TEXT['btndelete'][$lang_code]?></span></a>


					<?php
					}
?>
					<a href="./signature_mapping_reg.php" class="btn" id='btnClear'><?=$_LANG_TEXT["btnclear"][$lang_code];?></a>
				</div>
			</div>

		</form>

	</div>

</div>

<?php

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>
<!-- signature_mapping_reg -->