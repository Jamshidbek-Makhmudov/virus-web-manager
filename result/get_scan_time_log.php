<?php
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common2.inc";

$Model_Result=new Model_result();

$v_wvcs_seq = $_POST['v_wvcs_seq'];

if($v_wvcs_seq){

	//**검사 로그 정보
	$args = array("v_wvcs_seq"=>$v_wvcs_seq);
	$result = $Model_Result->getVcsScanTimeLog($args);

		$str_scan_detail = "<table class='info2'>
							<tr > 
								<th  style='border:1px solid #737296; width:50px'>".$_LANG_TEXT['numtext'][$lang_code]."</th>
								<th style='border:1px solid #737296; width:100px'>".$_LANG_TEXT['gubuntext'][$lang_code]."</th>
								<th style='border:1px solid #737296; width:100px'>".$_LANG_TEXT['start_time'][$lang_code]."</th>
								<th  style='border:1px solid #737296; width:100px'>".$_LANG_TEXT['end_time'][$lang_code]."</th>
								<th  style='border:1px solid #737296; width:100px'>".$_LANG_TEXT['complete_time'][$lang_code]."</th>

							</tr>";

	if($result) {
		$no=1;
		while ($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)) {

			$event_div=$row['event_div'];
			
			$str_event_div = $_CODE_SCAN_EVENT_LOG_LIST[$event_div];
			if($str_event_div=="") $str_event_div = $event_div;

			$start_time=$row['start_time'];
			$end_time=$row['end_time'];

			$date1 = strtotime($start_time);
			$date2 = strtotime($end_time);

   		$res = abs($date1 - $date2);
			$hours = floor($res / 3600);
			$minutes = floor(($res % 3600) / 60);
			$seconds = $res % 60;
      $overall = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);

			// $rowColor = ($no % 2 == 0) ? '#fff' : '#f7f8fa';

			$start_time=setDateFormat($row['start_time'],"Y-m-d H:i");
			$end_time=setDateFormat($row['end_time'],"Y-m-d H:i");

					$str_scan_detail .= "<tr style='background-color:#fff !important;'>
									<td>".$no."</td>
									<td>".$str_event_div."</td>
									<td>".$start_time."</td>
									<td>".$end_time."</td>
									<td>".$overall."</td>
								</tr>";

					$no++;
				}
			}else{

				$str_scan_detail .= "<tr style='background-color:#fff !important;'><td colspan='5'>".$_LANG_TEXT['nodata'][$lang_code]."</td></tr>";
			}

			$str_scan_detail .= "</table>";

      ?>
			<table class="view"  >
			<tr>
				<!--  -->
			</tr>
				<tr class="bg" >
					<th  style="width:150px"><?=$_LANG_TEXT['scantime_log'][$lang_code]?></th>
					<td  style='text-align:left'><?=$str_scan_detail;?></td>
				</tr>
			</table>
		 <?php 
 }else{
	echo "<table class='view'><tr><td align='center' style='height:35px'>".$_LANG_TEXT["nodata"][$lang_code]."</td></tr></table>";
}
