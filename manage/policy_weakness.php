<?php
$page_name = "policy";
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

?>
<div id="policy">
	<div class="outline">
	<div class="container">

		<div id="tit_area">
			<div class="tit_line">
				 <h1><span id='page_title'><?=$_LANG_TEXT["m_manage_policy_weakness"][$lang_code];?></span></h1>
			</div>
			<span class="line"></span>
		</div>

		<div class="page_right" style='margin-top:-25px;'><span style='cursor:pointer' onclick="history.back();"><?=$_LANG_TEXT['btngobeforepage'][$lang_code]?></span></div>
		
		<!--등록폼-->
		<form name='frmWeakness' id='frmWeakness' method='get'>
		<table class="list" >
		<tr>
			<th style='min-width:60px;width:60px'><a href="javascript:" onclick="UseCheck()"><?=$_LANG_TEXT['useyestext'][$lang_code]?></a></th>
			<th style='width:400px'>
				<?=$_LANG_TEXT['checkitemtext'][$lang_code]?>
			</td>
			<th style='min-width:90px;width:100px' class="line"><?=$_LANG_TEXT['windowversiontext'][$lang_code]?></th>
			<th><?=$_LANG_TEXT['solutionstext'][$lang_code]?></td>
		</tr>
<?
$qry_params = array();
$qry_label = QRY_POLICY_WEAKNESS;
$sql = query($qry_label,$qry_params);

$result = sqlsrv_query( $wvcs_dbcon, $sql);

$CNT = 0;
if($result){

	while($row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC)){

		$weakness_seq = $row['weakness_seq'];
		$weakness_name = $row['weakness_name'];
		$windows_ver = $row['window_ver'];
		$use_yn = $row['use_yn'];
		$solutions = $row['solutions'];
	
?>
		<tr <?if($CNT % 2 == 1) echo "class='bg'";?>>
			<td><input type='checkbox' name='use_yn[]' id='use_yn_<?=$weakness_seq?>' value='<?=$weakness_seq?>' <?if($use_yn=="Y") echo "checked";?>> </td>
			<td style='text-align:left'><label for='use_yn_<?=$weakness_seq?>' style='cursor:pointer;'><?=$weakness_name?></label></td>
			<td><?=$windows_ver?></td>
			<td style='text-align:left'><?=$solutions?></td>
		</tr>
<?
	$CNT++;
	}

}

if($CNT==0){
	echo "<tr><td colspan='4'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
}
?>		
		</table>

		<div class="btn_wrap">
			<div class="right">
				<a href="#" class="btn required-create-auth hide" onclick="return PolicyWeaknessSubmit()"><?=$_LANG_TEXT['btnsave'][$lang_code]?></a>
			</div>
		</div>
		
		</form>

	</div>
	</div>
</div>

<?php

if($result) sqlsrv_free_stmt($result);
sqlsrv_close($wvcs_dbcon);

include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";

?>