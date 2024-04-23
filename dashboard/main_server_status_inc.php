<div class='outline_right'>
	<form name="chart_main_server_status" id='chart_main_server_status'>
		<input type="hidden" name="start_date" value="<?= $start_date ?>">
		<input type="hidden" name="end_date" value="<?= $end_date ?>">
		<input type="hidden" id="scan_center_code" name="scan_center_code" value="<?= $scan_center_code ?>">
		<input type="hidden" id="scan_center_code" name="scan_center_code" value="<?= $scan_center_code ?>">

		<input type="hidden" id="disk_total_value" name="disk_total_value" value="<?= $disk_total_value ?>">
		<input type="hidden" id="disk_free_value" name="disk_free_value" value="<?= $disk_free_value ?>">
	</form>
	<div class='chart_title flex-between '>
		<h2>
			<?= trsLang('서버현황', 'server_status'); ?>
		</h2>
		<!-- <span class='logo_refresh  ' style='cursor:pointer;' onclick="loadMainServerStatusChart()"></span>	 -->
		<span class='logo_refresh  ' style='cursor:pointer;' onclick="loadMainServerStatus()"></span>
	</div>

	<div id='main_server_status'>
		<table class="view">
			<tr>
				<td class='text-center bg'>Loading..</td>
			</tr>
		</table>
	</div>
	<!-- chart -->

</div>