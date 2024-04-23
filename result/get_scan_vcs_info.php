<?
$_server_path = $_SERVER['DOCUMENT_ROOT'];
$_REQUEST_URI = substr($_SERVER['REQUEST_URI'], 1, strLen($_SERVER['REQUEST_URI'])-1);
$_apos = stripos($_REQUEST_URI,  "/");
if($_apos > 0) {
	$_REQUEST_URI = substr($_REQUEST_URI, 0, $_apos);
}
$_site_path = $_REQUEST_URI;

include_once $_server_path . "/" . $_site_path . "/inc/common.inc";

$barcode = $_POST['barcode'];
$checkinout_flag = $_POST['checkinout_flag'];

if($barcode){

	$alert_msg = "";
	$search_sql = " AND vcs.barcode = '".$barcode."' ";

	$qry_params = array("search_sql"=>$search_sql);
	$qry_label = QRY_RESULT_VCS_INFO;
	$sql = query($qry_label,$qry_params);

	//echo nl2br($sql);
	//exit;

	$result = sqlsrv_query($wvcs_dbcon, $sql,array(), array( "Scrollable" => SQLSRV_CURSOR_KEYSET ));

	if($result){
		$vcs_count = @sqlsrv_num_rows( $result );  
		$row = @sqlsrv_fetch_array($result,SQLSRV_FETCH_ASSOC);
	}

	if($row){
		
		$v_user_seq = $row['v_user_seq'];
		$check_date = $row['check_date'];
		$in_available_date = $row['checkin_available_dt'];
		if($in_available_date){
			
			$hour = substr($in_available_date,8,2);
			$min = substr($in_available_date,10,2);

			$in_available_date = substr($in_available_date,0,4)."-".substr($in_available_date,4,2)."-".substr($in_available_date,6,2);
			
			$in_available_date = $in_available_date." ".($hour? $hour : "00").":".($min? $min : "00");
		}

		$in_date = substr($row['wvcs_authorize_dt'],0,16);
		$out_date = substr($row['checkout_dt'],0,16);
		$v_user_name = aes_256_dec($row['v_user_name']);
		$v_com_name = $row['v_com_name'];
		
	
		if($_encryption_kind=="1"){

			$phone_no = $row['v_phone_decript'];
			$email = $row['v_email_decript'];

		}else if($_encryption_kind=="2"){

			$phone_no = aes_256_dec($row['v_phone']);
			$email = aes_256_dec($row['v_email']);
		}

		if($_cfg_user_identity_name=="phone"){
			$v_user_name_com = $phone_no;
			$v_user_name= $phone_no;
		}else if($_cfg_user_identity_name=="email"){
			$v_user_name_com =$email;
			$v_user_name= $email;
		}else{
			if($v_com_name=="-") $v_com_name="";
			$v_user_name_com = $v_user_name.($v_com_name? "/" :"").$v_com_name;
		}		

		$check_type = $row['wvcs_type'];
		$org_name = $row['org_name'];
		
		$apprv_yn = $row['wvcs_authorize_yn'];
		$scan_center_name = $row['scan_center_name'];
		
		$apprv_dt = ($apprv_yn=="Y") ? $row['wvcs_authorize_dt'] : "";


		$v_wvcs_seq = $row['v_wvcs_seq'];
		
		$today = date("Y-m-d H:i");
		

		$scan_result = "";

		if($checkinout_flag=="Y"){

			if($in_date > ''){
							
				$scan_result = "ok";
				
				
				if($out_date > ''){

					$alert_msg = $_LANG_TEXT['cancelcheckoutprocesstext'][$lang_code];	//해당 기기를 반출 취소 처리합니다.

					$checkout_dt = "NULL";
					$vcs_status = "IN";

				}else{

					$alert_msg = $_LANG_TEXT['checkoutprocesstext'][$lang_code];	//해당 기기를 반출 처리합니다. 반출 취소를 하려면 한번 더 바코드를 스캔해 주세요.

					$checkout_dt = "getdate()";
					$vcs_status = "OUT";
				}

				//반출처리
				if($_ck_user_level=="SECURITOR_S"){	//보안관리자(조회용)

				}else{

					$qry_params = array(
						"v_wvcs_seq"=>$v_wvcs_seq
						,"checkout_dt"=>$checkout_dt
						,"vcs_status"=>$vcs_status
					);
					$qry_label = QRY_RESULT_PC_CHECKOUT;
					$sql = query($qry_label,$qry_params);

					$result = @sqlsrv_query($wvcs_dbcon, $sql);

					if($result){

						#반입/반출 연동
						include "./result_check_api_inc.php";

						$out_date = ($checkout_dt=="NULL")? "" : $today;
						$scan_result = "ok";

					}else{
						
						$scan_result = "error";
						$alert_msg = $_LANG_TEXT['checkouterrortext'][$lang_code];	//반출처리중 오류발생
					}

				}//if($_ck_user_level=="SECURITOR_S"){
				

			}else{
				
				if(strtotime($in_available_date) < strtotime($today)){
					
					$scan_result = "error";
					$alert_msg = $_LANG_TEXT['checkinavailabledateovertext'][$lang_code];	//반입가능일 경과
				
				}else{
					
					//반입처리
					if($_ck_user_level=="SECURITOR_S"){	//보안관리자(조회용)

					}else{

						$apprv_yn = "Y";
						$apprv_name = aes_256_enc($_ck_user_name);
						$apprv_dt = "getdate()";
						$vcs_status = "IN";

						$qry_params = array(
							"v_wvcs_seq"=>$v_wvcs_seq
							,"wvcs_authorize_yn"=>$apprv_yn
							,"wvcs_authorize_name_encrypt"=>$apprv_name
							,"wvcs_authorize_dt"=>$apprv_dt
							,"vcs_status"=>$vcs_status
						);
						$qry_label = QRY_RESULT_PC_CHECKIN;
						$sql = query($qry_label,$qry_params);

						$result = @sqlsrv_query($wvcs_dbcon, $sql);

						if($result){

							#반입/반출 연동
							include "./result_check_api_inc.php";

							$in_date = $today;
							$scan_result = "ok";
							$alert_msg = $_LANG_TEXT['checkinprocesstext'][$lang_code];	// "해당 기기를 반입 처리합니다.";
						}else{
							
							$scan_result = "error";
							$alert_msg = $_LANG_TEXT['checkinerrortext'][$lang_code];	//반입처리중 오류발생
						}

					}//if($_ck_user_level=="SECURITOR_S"){
				}
			}
		}

	}else{
		
		$scan_result = "error";
		$alert_msg = $_LANG_TEXT['novcsdata'][$lang_code];
	}

	//**scan log 기록
	if($_ck_user_level=="SECURITOR_S"){	//보안관리자(조회용)

	}else{

		if($checkinout_flag=="Y"){	
			
			//로그기록
			$qry_params = array(
				"barcode"=>$barcode,
				"v_wvcs_seq"=>$v_wvcs_seq,
				"scan_result_msg"=> $alert_msg,
				"create_emp_seq"=>$_ck_user_seq
			);
			$qry_label = QRY_VCS_SCAN_LOG_INSERT;
			$sql = query($qry_label,$qry_params);
			$result = @sqlsrv_query($wvcs_dbcon, $sql);

		}//if($checkinout_flag=="Y"){

	}

}//if($barcode){
?>
<input type="hidden" name="v_wvcs_seq" id="v_wvcs_seq" value="<?=$v_wvcs_seq?>">
<table class='view'>
	<tr>
		<th style='width:150px'><?=$_LANG_TEXT['visitortext'][$lang_code]?></th>
		<td style='min-width:150px'><?=$v_user_name_com?></td>
		<th class="line" style='width:150px'><?=$_LANG_TEXT['checkdatetext'][$lang_code]?></th>
		<td style='min-width:150px'><?=$check_date?></td>
		<th class="line" style='width:150px'><?=$_LANG_TEXT['checkgubuntext'][$lang_code]?></th>
		<td style='min-width:100px'><?=$check_type?></td>
	</tr>
	<tr class="bg">
		<th><?=$_LANG_TEXT['inlimitdatetext'][$lang_code]?></th>
		<td><?=$in_available_date?></td>
		<th class="line"><?=$_LANG_TEXT['indatetext'][$lang_code]?></th>
		<td><?=$in_date?></td>
		<th class="line"><?=$_LANG_TEXT['outdatetext'][$lang_code]?></th>
		<td><?=$out_date?></td>
	</tr>
</table>
<BR>

<?
	if($alert_msg > ''){
		if($scan_result=="ok") {
			if($vcs_status == "IN") {
					$span_class_name = "txtblue";
			}else{
					$span_class_name = "txtgreen";
			}
		}else{
			$span_class_name = "txt";
		}
?>
	<div id='scan_alert_msg' class='scan_alert_msg'><br/><span class='<?=$span_class_name?>'><?=$alert_msg?></span><br/></div>
<?}?>

<audio id="audio_ok">
  <source src="<?=$_www_server?>/sound/ok.wav" type="audio/wav">
  <source src="<?=$_www_server?>/sound/ok.mp3" type="audio/mpeg">
</audio>
<audio id="audio_error">
  <source src="<?=$_www_server?>/sound/error.wav" type="audio/wav">
  <source src="<?=$_www_server?>/sound/error.mp3" type="audio/mpeg">
</audio>

<?if($barcode && $scan_result){?>
	<script type='text/javascript'>
		var x = document.getElementById("audio_<?=$scan_result?>"); 
		x.play(); 
	</script>
<?}?>
