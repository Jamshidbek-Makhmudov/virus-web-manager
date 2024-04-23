<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";



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
<div class="wrapper">
	<!-- <div style='float:right'>
		<?//if(in_array("BAD_EXT",$_CODE_INSPECT_OPTION)){?>
		<img src="<?// echo $_www_server?>/images/b_clean.png"> <? //echo trsLang('위변조의심','suspectforgerytext');?>
		<?//}?>
		<?//if(in_array("VIRUS",$_CODE_INSPECT_OPTION)){?>
		<img src="<? //echo $_www_server?>/images/v_clean.png"> <? //echo $_LANG_TEXT["viruscleantext"][$lang_code];?>
		<?//}?>
		<?//if(in_array("WEAK",$_CODE_INSPECT_OPTION)){?>
		<img src="<?// echo $_www_server?>/images/w_clean.png"> <?// echo $_LANG_TEXT["weaknesscleantext"][$lang_code];?>
		<?//}?>
	</div> -->
</div>
<div id='uc_wrapper1' class="wrapper">
	<div id='uc_div1' style='height:1px;width:1100px'></div>
</div>
<div id='uc_wrapper2' class="wrapper">
				<table class="list" style="margin-top:10px; ">
					<tr>
						<th style='min-width:200px'><?= $_LANG_TEXT['inspection_center'][$lang_code] ?></th>
						<th style='min-width:100px'><?= $_LANG_TEXT['device'][$lang_code] ?></th>
						<th style='min-width:100px'><?= $_LANG_TEXT['malicious_code'][$lang_code] ?></th>
						<th style='min-width:100px'><?= $_LANG_TEXT['suspected_forgery'][$lang_code] ?></th>
						<th style='min-width:100px'><?= $_LANG_TEXT['number_imported_files'][$lang_code] ?></th>
						<th style='min-width:100px'><?= $_LANG_TEXT['number_scanned_files'][$lang_code] ?></th>
						<th style='min-width:100px'><?= $_LANG_TEXT['detailstext'][$lang_code] ?></th>

					</tr>
					<?php




					?>



					<tr>
						<tr>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>
<td></td>


					</tr>

					</tr>
					<?php

					?>

				</table>

</div>

<!--����¡-->
<?php


?>
<? 
	$excel_param_enc = ParamEnCoding($param.(($orderby)? "&orderby=".$orderby : ""));
	$excel_down_url = $_www_server."/result/user_check_list_excel.php?enc=".$excel_param_enc;
?>
<div class="right" style='margin-top:<?=$total > 0 ? "-70" : "10" ?>px;'>
	<a href="javascript:" id='btnExcelDown' onclick="ExcelDown('<?=$excel_down_url?>','btnExcelDown')" class="btnexcel required-print-auth hide" ><?=$_LANG_TEXT["btnexceldownload"][$lang_code];?></a>
</div>