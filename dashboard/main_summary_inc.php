<?php 
$param = "";
$today = date("Y-m-d");
if ($start_date != "") $param .= ($param == "" ? "" : "&") . "start_date=" . $today;
if ($end_date != "") $param .= ($param == "" ? "" : "&") . "end_date=" . $today;
if ($scan_center_code != "") $param .= ($param == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

$param2 = "";
if ($start_date != "") $param2 .= ($param2 == "" ? "" : "&") . "start_date=" . $start_date;
if ($end_date != "") $param2 .= ($param2 == "" ? "" : "&") . "end_date=" . $end_date;
if ($scan_center_code != "") $param2 .= ($param2 == "" ? "" : "&") . "scan_center_code=" . $scan_center_code;

//오늘통계
$param_enc = ParamEnCoding($param);
$param_visit_enc = ParamEnCoding($param."&visit_div=OUT_VISIT");
//주요통계
$param_enc2 = ParamEnCoding($param2);
$param_visit_enc2 = ParamEnCoding($param2."&visit_div=OUT_VISIT");


	$Model_Dashboard->SHOW_DEBUG_SQL = false;
	$args = array("end_date"=>$end_date,"start_date"=>$start_date,"search_sql"=>$search_sql);			
	$result = $Model_Dashboard->getVisitPeriodSummary($args);
	if ($result) {
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

			$visit_count = $row['visit_count'];
			$visit_file_count = $row['visit_file_count'];
			$visit_pass_count = $row['visit_pass_count'];
			$visit_goods_count = $row['visit_goods_count'];
	
	}
	?>
<div class='section001 flex-center '>
		<div class='outline left'>
			<h1><?= trsLang('주요 통계','key_statistics'); ?> ( <?= $start_date_dot?> ~ <?= $end_date_dot?>) </h1>
		<div class='wrapper'>
			<div class=' flex-around' style='margin-top:10px;'>
				<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control.php?enc=<?=$param_visit_enc2?>'">
			<div class='box  flex-evenly '>			
			<div class='flex-center'>				
				<span class='logo1' ></span>
			</div>
				<div class='child_box'>
					<span class='title'><?= trsLang('방문자 출입','visitor_visit'); ?> </span>
					<span class='num' style='color:#0274eb;'><?=$visit_count?></span>
				</div>
			</div>
		 </a>
		  <a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_file.php?enc=<?=$param_enc2?>'">
			<div class='box  flex-evenly'>		
			<div class='flex-center'>		
				<span class='logo2'  ></span>
			</div>
				<div class='child_box'>
					<span class='title'><?= trsLang('파일반입','fileimport'); ?> </span>
					<span class='num' style='color:#43c55b;'><?=$visit_file_count?></span>
				</div>		
			</div>
			</a>
			 <a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_goods.php?enc=<?=$param_enc2?>'">
			<div class='box  flex-evenly'>			
			<div class='flex-center'>			
				<span class='logo3' ></span>
			</div>
				<div class='child_box'>
					<span class='title'><?= trsLang('외부 자산 반입','bring_out_good'); ?> </span>
					<span class='num' style='color:#27aece;'><?=$visit_goods_count?></span>
				</div>		
			</div>
				</a>
				 <a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_pass.php?enc=<?=$param_enc2?>'">	
			<div class='box  flex-evenly'>		
			<div class='flex-center'>		
				<span class='logo4' ></span>
			</div>
				<div class='child_box'>
					<span class='title'><?= trsLang('임시 출입증 발급','tempoprary_pass_issue'); ?> </span>
					<span class='num' style='color:#fc9d33;'><?=$visit_pass_count?></span>
				</div>			
			</div>
			</a>
				</div>	
		</div>

		</div>

	<?php 
	$Model_Dashboard->SHOW_DEBUG_SQL = false;
	$args = array("end_date"=>$end_date,"start_date"=>$end_date,"search_sql"=>$search_sql);			
	$result = $Model_Dashboard->getVisitPeriodSummary($args);
	if ($result) {
			$row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC);

			$visit_count = $row['visit_count'];
			$visit_file_count = $row['visit_file_count'];
			$visit_pass_count = $row['visit_pass_count'];
			$visit_goods_count = $row['visit_goods_count'];
	
	}
	?>
		<div class='outline right'>
			<h1 class='title_left'><?= trsLang('오늘 통계','today_statistics'); ?> (<?= $end_date_dot?>) </h1>
		<div class="right_inner">
			
		
				<div class='wrapper '>
								<div class='inner_wrap  flex-between '>
									<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control.php?enc=<?=$param_visit_enc?>'">
										<div class='box  flex-between ' >
											
											<span class='title'><?= trsLang('방문자 출입','visitor_visit'); ?> </span>
											<span class='num2 bold' style='color:#0274eb; font-weight:800px;'><?=$visit_count?></span>
											
										</div>
									</a>
									<div class='divider'>	</div>								
									<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_file.php?enc=<?=$param_enc?>'">
										<div class='box  flex-between'>
											<span class='title'><?= trsLang('파일반입','fileimport'); ?> </span>
											<span class='num2' style='color:#43c55b;'><?=$visit_file_count?></span>
											
										</div>
									</a>
								</div>
								
								
								
								<div class='inner_wrap flex-between '>
					
					<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_goods.php?enc=<?=$param_enc?>'">
						<div class='box  flex-between  '>
							
							<span class='title'><?= trsLang('자산 반입','bring_goods'); ?> </span>
							<span class='num2' style='color:#27aece;'><?=$visit_goods_count?></span>
							
						</div>
					</a>
			<div class='divider'></div>
						<a style='cursor:pointer;' onclick="javascript:location.href='<? echo $_www_server?>/user/access_control_pass.php?enc=<?=$param_enc?>'">
							<div class='box  flex-between'>
								<span class='title'><?= trsLang('출입증 발급','tempoprary_pass'); ?> </span>
								<span class='num2' style='color:#fc9d33;'><?=$visit_pass_count?></span>
								
							</div>
						</a>			
			</div>
				</div>
		</div>
		</div>
	</div>