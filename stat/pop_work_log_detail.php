<?php
$page_name = "work_log";
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$act_log_seq = $_POST['log_seq'];

$args = array("act_log_seq"=>$act_log_seq);
$Model_Stat = new Model_Stat();
$Model_Stat->SHOW_DEBUG_SQL = false;
$result = $Model_Stat->getAdminActLogDetails($args);


if($result){

	while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

		$act_log_seq = $row['act_log_seq'];

		$admin_id = $row['emp_no'];
		$admin_name = aes_256_dec($row['emp_name']);

		$log_title = $row['log_title'];
		$ip = $row['ip_addr'];
		$log_dt=setDateFormat($row['log_date'],"Y-m-d");
		$referer = $row['referer'];
		$act_type = $row['act_type'];
		$recv_data = json_decode($row['recv_data'],true);

		//enc,proc_name 파라메터값은 제외하고 보여준다.
		$remove_param = array("enc","proc_name","proc","record_count");
		foreach($remove_param as $key) unset($recv_data[$key]);

		if(count($recv_data) > 0){
			$str_recv_data = str_replace("Array","Parameter",print_r($recv_data,true));
		}else $str_recv_data = "";

		$nodata= $_LANG_TEXT["nodata"][$lang_code];
		if($recv_data!=''&& $recv_data !='null' ){
				$log_desc .= "<li style='margin-top:10px; margin-left:5px; list-style-type: none;'>".$str_recv_data."</li>";
			}else{
				$log_desc .= "<li style='margin-top:10px;margin-left:5px;'>".$nodata."</li>";

			}

	}

}
?>
<script language="javascript">
$("document").ready(function(){


});
</script>
<div id="mark">
	<div class='container'>
		<div class="content"  style='height:600px;min-height:600px;width:600px;'>
			<div class='tit'>
				<div class='txt'><?=$_LANG_TEXT["work_log"][$lang_code];?></div>
				<div class='right'>
					<div class='close' onClick="ClosepopContent();"></div>
				</div>
			</div>
			
			<div class='wrapper2'>
				<!--작업정보-->
				<div class="sub_tit">
					<?=$_LANG_TEXT["workinfotext"][$lang_code];?>
				</div>
				<table class="view">
				<tr>
					<th style="width:20%"><?=$_LANG_TEXT["workertext"][$lang_code];?></th>
					<td ><?=$admin_name?><?if($admin_id) echo "({$admin_id})";?></td>
					<th class='line'><?=$_LANG_TEXT["work_type"][$lang_code];?></th>
					<td>
						<?=$act_type?>
					</td>
				</tr>
				<tr class="bg">
					<th><?=$_LANG_TEXT['ipaddresstext'][$lang_code]?></th>
					<td>
						<?=$ip?>
					</td>
					<th class='line'><?=$_LANG_TEXT["work_date"][$lang_code];?></th>
					<td>
						<?=$log_dt?>
					</td>
				</tr>
				<tr>
					<th><?=$_LANG_TEXT['work_detail'][$lang_code]?></th>
					<td colspan='3'><?=$log_title?></td>
				</tr>
				<tr class='bg'>
					<th><?=$_LANG_TEXT['workcallpagetext'][$lang_code]?></th>
					<td colspan='3'><a href='<?=$referer?>' target='_blank'><?=$referer?></a></td>
				</tr>
				</table>
				
				<!--상세정보-->
				<div  class="sub_tit">
					<?=$_LANG_TEXT["detailinfotext"][$lang_code];?>
				</div>
				<div class='work_detail'>
					<?=$log_desc?>
				</div>
		</div>
	</div>
</div>
