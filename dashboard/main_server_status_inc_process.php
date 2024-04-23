<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI']) - 1);
$_apos = stripos($_REQUEST_URI, "/");
if ($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$scan_center_code = $_POST['scan_center_code'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

$search_sql = "";
if ($scan_center_code != "") {

	$search_sql .= " and v2.in_center_code = '{$scan_center_code}'  ";
}

$Model_Dashboard = new Model_Dashboard();

$result = $Model_Dashboard->getServerStatus();

if ($result) {
	$server_name = $result['server_name'];
	$server_ip = $result['server_ip'];
	$disk_usage_rate = $result['disk_usage_rate']; //not used
	$web_connection = $result['web_connection'];
	$db_connection = $result['db_connection'];
	$disk_total = $result['disk_total'];
	$disk_free = $result['disk_free'];
	//
	$disk_total_chart = $result['disk_total_chart']; //total
	$disk_free_chart = $result['disk_free_chart']; //free
}

//find the used disk
$used = $disk_total_chart - $disk_free_chart; //used
$used_percent = ($used / $disk_total_chart) * 100; //used in %
$free_percent = ($disk_free_chart / $disk_total_chart) * 100; //free in %

$disk_total_value = round($used_percent);
$disk_free_value = round($free_percent);


?>
<!-- chart -->
<div class='doughnut_box flex-evenly'>



	<div class='flex-col doughnut_box_inner'>


		<div class='doughnut_chart flex-center '>
			<?php $canvasId = 3 ?>
			<canvas style="" id="<?php echo $canvasId ?>"></canvas>
			<?php
			$data = [$disk_total_value, $disk_free_value];
			$labels = ['used space in %: ', 'free space in %: '];
			$colors = ['#159CFA', '#e5e5e5'];
			for ($i = 0; $i < count($data); $i++) {
				$colors[$i];
			}
			if (empty(array_filter($data))) {

				$labels = ['no data'];
				$data = [1];
				$colors = ["#dddddd"];
			}
			?>
			<script>
				Chart.defaults.global.legend.display = false;
				var ctx2 = document.getElementById('<?php echo $canvasId ?>').getContext('2d');
				var myChart = new Chart(ctx2, {
					type: 'doughnut',
					data: {
						datasets: [{
							backgroundColor: <?php echo json_encode($colors); ?>,
							borderWidth: 0,
							hoverOffset: 2,
							data: <?php echo json_encode($data); ?>,
						}],
						labels: <?php echo json_encode($labels) ?>,
					},
					options: {
						rotation: -1.0 * Math.PI, // start angle in radians
						circumference: Math.PI, // sweep angle in radians
						responsive: true,
						plugins: {
							tooltip: {
								enabled: true,
								titleAlign: 'center',
								bodyAlign: 'center',
								displayColors: false,
							},
							legend: {
								display: false
							}
						},
						maintainAspectRatio: false,
						cutoutPercentage: 55,
					}
				});
			</script>


		</div>

		<span class='gauge_title'>
			<?= trsLang('Disk Free', 'disk_free_text'); ?>
			<?= $disk_free ?>
		</span>

	</div>
	<!-- list -->
	<div class='list flex-col'>
		<span>
			<?= $server_name ?>
		</span>
		<span>
			<?= trsLang('IP', 'iptext'); ?> :
			<?= $server_ip ?>
		</span>
		<span>
			<?= trsLang('Total', 'total_text'); ?> :
			<?= $disk_total ?>
		</span>
		<span>
			<?= trsLang('Free', 'free_text'); ?> :
			<?= $disk_free ?>
		</span>

	</div>

</div>

<!-- chart -->
<div class='info_box flex-col '>
	<div class='info flex-between '>
		<?php $web_connection == 'HEALTHY' ? $color = "#4bc560" : $color = "#ff8d6b" ?>
		<?php $web_connection == 'HEALTHY' ? $color1 = "#8087A3" : $color1 = "#ff8d6b" ?>
		<span style='color: <?= $color1 ?>;'>&#x2022;
			<?= trsLang('Web Connection', 'web_connection_text'); ?>
		</span>
		<span class='last' style='color: <?= $color ?>;'>
			<?= $web_connection ?>
		</span>
	</div>
	<div class='info flex-between'>
		<?php $db_connection == 'HEALTHY' ? $color = "#4bc560" : $color = "#ff8d6b" ?>
		<?php $db_connection == 'HEALTHY' ? $color1 = "#8087A3" : $color1 = "#ff8d6b" ?>
		<span style='color: <?= $color1 ?>;'> &#x2022;
			<?= trsLang('DB Connection', 'db_connection_text'); ?>
		</span>
		<span class='last ' style='color: <?= $color ?>;'>
			<?= $db_connection ?>
		</span>
	</div>
</div>