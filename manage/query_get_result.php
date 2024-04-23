<?php
$page_name = "custom_query";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$_query_enc = $_POST[query_enc];
$query = trim(htmlentities(base64_decode($_query_enc),ENT_NOQUOTES));

$check_query = strtr($query,array("\r\n"=>'',"\r"=>'',"\n"=>''));
$check_query = strtolower($check_query);


if(substr($check_query,0,6) !="select" ){
	echo "<div style='text-align:center;margin-top:30px;'>Warning : ".trsLang('데이터조회만허용됩니다.','allowonlyselecttext').".</div>";
	exit;
}

if(strpos($check_query,"*")!==false){
	echo "<div style='text-align:center;margin-top:30px;'>Warning : '*' 문자열은 사용할 수 없습니다.</div>";
	exit;
}


$param = "";
if ($_query_enc != "") $param .= ($param == "" ? "" : "&") . "query_enc=" . $_query_enc;

$Model_utils= new Model_Utils();
$Model_utils->RETURN_SQL_ERRORS = true;

$args = array("query"=>$query);
$result =  $Model_utils->getQueryEditorsList($args);
$total =  @sqlsrv_num_rows($result);

if ($result) {
?>
	<div class="sub_tit left"> > <? echo trsLang('쿼리결과','queryresulttext')?></div>
	<!--엑셀다운로드-->
	<?  $excel_name=$_LANG_TEXT['m_query_editor'][$lang_code];	?>
	<? $excel_down_url = $_www_server."/manage/query_result_excel.php?enc=".ParamEnCoding($param);?>

	<div class="right" style='margin-top:20px'>
		<a href="javascript:void(0)" class="btnexcel required-print-auth hide" onclick="getHTMLSplit('<?=$total?>','<?=$excel_down_url?>','<?=$excel_name?>',this);"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
							<!-- <a href="#" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')"
						class="btnexcel required-print-auth hide"><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a> -->
	</div>

	<div id='wrapper1' class="wrapper">
	<div id='div1' style='height:1px;'></div>
	</div>
	<div id='wrapper2' class="wrapper">
		<?			
			//데이타가 aes256 암호화되어 저장된 컬럼명
			$enc_col = array("user_name","v_user_name","manager_name","mngr_name","emp_name","admin_name"
,"wvcs_authorize_name","approver_name","v_phone","phone_no","user_phone","v_email","email");

			$headerPrinted = false;
			$no =$total;

			echo "<table class='list' id='tblList' cellpadding='2' style='margin:0px;auto; white-space: nowrap; ' >";
			
			while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
				
				if (!$headerPrinted) {
					echo '<thead><tr>';
								echo "<th style='width:60px;'>".trsLang('번호','numtext')."</th>";
					foreach ($row as $columnName => $value) {
						echo '<th style="text-align:left;padding:0 5px">' . strtolower($columnName) . '</th>';
					}
					echo '</tr></thead>';
					$headerPrinted = true;
				}
				
				echo '<tbody>';
				echo '<tr>';
				echo "<td style='text-align:center'>" . $no . "</td>";
				foreach ($row as $col=>$value) {

					if(in_array($col,$enc_col)) $value = aes_256_dec($value);

					if ($value instanceof DateTime) {
						echo '<td>' . $value->format('Y-m-d H:i:s') . '</td>';
					} else {
						echo '<td>' . $value . '</td>';
					}
				}
				echo '</tr>';
				$no--;
			}
			echo '</tbody>';
			echo '</table>';
				
			if ($result) sqlsrv_free_stmt($result);
			sqlsrv_close($wvcs_dbcon);	
		}else{
			$sql_errors = $Model_utils->SQL_ERRORS[0];
			echo "<div style='text-align:center;margin-top:30px;'> SQL Errors : ".$sql_errors[message]."</div>";
			exit;
		}
		?>
	</div>
<script language="javascript">
$("document").ready(function(){
	var w = $("#tblList").width();
	$("#div1").width(w);
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