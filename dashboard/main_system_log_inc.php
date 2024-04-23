	<?php 
	$Model_Dashboard->SHOW_DEBUG_SQL = false;	
	$result = $Model_Dashboard->getSystemLog();
	$total = sqlsrv_num_fields($result);

?>
<div class='outline_left'>

 <div class=' doughnut_title flex-between'>
 	<div>					
 		<h3><?= trsLang('작업로그','worklogtext'); ?> </h3>
 	</div>
 	
 	<div class='flex-center' onclick="location.href='<? echo $_www_server?>/stat/system_log.php'" style='cursor:pointer;'>
 		
 		<h4><?= trsLang('더보기','btnmore'); ?> </h4>
 		<div class=" flex-center ">
 			<span class='logo_more' ></span>
 		</div>
 	</div>
 </div>
 <div class='table_box'>
													
	<table class="list3" >
			<thead>
				<th style='width:60px;'><? echo trsLang('번호','numtext')?></th>
				<th style='width:33%;'><? echo trsLang('구분','gubuntext')?></th>
				<th style='width:34%;'><? echo trsLang('작업결과','workresulttext')?></th>
				<th style='width:30%;'><? echo trsLang('작업일시','worktimetext')?></th>

			</thead>
<?php 
	if ($result) {
		$no=$total;
	while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {
		$system_log_seq = $row['system_log_seq'];
		$log_div = $row['log_div'];
		$str_log_div = $_CODE_SYSTEM_LOG_LIST[$log_div];
		if ($str_log_div=="") $str_log_div = $log_div;

		$workresult = $row['result'];
		$content = $row['content'];
	  $str_create_date=setDateFormat($row['create_date'],"Y-m-d H:i:s");
			?>
			<tr>
				<td style='width:60px;'><?=$no?></td>
				<td style='width:20px;'><?=$str_log_div?></td>
				<td style='width:20%;'><?=$workresult?></td>
				<td style='width:20%;'><?=$str_create_date?></td>

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
				<td colspan="12" align='center'><?php echo $_LANG_TEXT["nodata"][$lang_code]; ?></td>
			</tr>
			<?php
			}
			?>

		</table>

		<!--페이징-->
		<?php
		// if($total > 0) {
		// 	$param_enc = ($param)? "enc=".ParamEnCoding($param) : "";
			
		// 	print_pagelistNew3($page, $lists, $page_count, $param_enc, '', $total );
		// }
		?>
			
		</div>
							
</div>