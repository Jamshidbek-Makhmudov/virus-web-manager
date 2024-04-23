<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";
include_once $_server_path . "/" . $_site_path . "/inc/header.inc";

$barcode = $_REQUEST['barcode'];

?>
<style type="text/css">  
	html {overflow:hidden;}  
</style>
<script type='text/javascript'>
$(window).unload(function() {
	$(opener.document.all.barcode).focus();
});
</script>
<div id="header">
	<div class="pop_gnb">
		<div class="logo">
			<img src="<?php echo $_www_server; ?>/images/logo.png">
		</div>
		<div class="title"><?=$_LANG_TEXT['m_manage_checkinscanlog'][$lang_code]?></div>
	</div>
</div>
<div id='popup'>
	<div class='container'>
		<div class='barcode'>Barcode : <?=$barcode?></div>
		<div style='overflow-y:auto;height:440px;'>
			<table id='tblList' class="list">
				<tr>
					<th width='150px'><?=$_LANG_TEXT['barcodescandatetext'][$lang_code]?></th>
					<th><?=$_LANG_TEXT['scanresulttext'][$lang_code]?></th>
				</tr>
			<?php

				$search_sql .= " and lg.barcode = '{$barcode}' ";
				$order_sql = " ORDER BY scan_log_seq DESC ";
											
				$qry_params = $qry_params = array("order_sql"=>$order_sql,"search_sql"=>$search_sql);
				$qry_label = QRY_VCS_BARCODE_SCANLOG_LIST;
				$sql = query($qry_label,$qry_params);

				$result =@sqlsrv_query($wvcs_dbcon, $sql);

				//echo nl2br($sql);
				$cnt = 0;
				if($result){
				  while($row=sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

						$create_dt = $row['create_dt'];
						$scan_result_msg = $row['scan_result_msg'];
						
						

			 ?>	
					<tr>
						<td><?=$create_dt?></td>
						<td style='text-align:left'><?=$scan_result_msg?></td>
					</tr>
				<?php
					$cnt++;
					}
						
				}

				if($result) sqlsrv_free_stmt($result);  
				if($wvcs_dbcon) sqlsrv_close($wvcs_dbcon);
				if($cnt < 1) {
					
				?>
					<tr>
						<td colspan="2" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
					</tr>
				<?php
				}
				?>						
			</table>
		</div>
		
	</div>
</div>
<?php
include_once $_server_path . "/" . $_site_path . "/inc/footer.inc";
?>