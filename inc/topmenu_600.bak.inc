<?php
/*$_PAGE
//auth_check.inc 참조
*/
?>
<div id="header" >
	<div id='menu_folder' >
	<?if($_ck_user_level=="MANAGER" || $_ck_user_level=="MANAGER_S"){	//매니저 등급 접속 ?>
			<div style='background-color:#fee300;height:40px;'>
				<div class="section_area" style='border:0px'>
					<div style='text-align:center;padding-top:5px;'>
						<img src="<?php echo $_www_server; ?>/images/kakao_bi_black.png" style='height:20px;'>
					</div>
					<div class="section" style='margin-top:-20px'>
							<img src="<?php echo $_www_server; ?>/images/icon.jpg"> <b><?=$_ck_user_name?></b> <!--<?=$_CODE["admin_level"][$_ck_user_level]?>--> <?=$_LANG_TEXT['signingin'][$lang_code]?><span class="line"></span><a href="<?=$_www_server?>/login/logout.php"><?=$_LANG_TEXT['logout'][$lang_code]?></a>
					</div>
				</div>
			</div>
		<?}else{?>
			<div class="section_area">
				<div class="section">
					<img src="<?php echo $_www_server; ?>/images/icon.jpg"> <a href="<?php echo $_www_server; ?>/user/my_info.php"><b><?=$_ck_user_name?></b></a> <?=$_CODE["admin_level"][$_ck_user_level]?> <?=$_LANG_TEXT['signingin'][$lang_code]?><span class="line"></span><a href="<?=$_www_server?>/login/logout.php"><?=$_LANG_TEXT['logout'][$lang_code]?></a>
				</div>
			</div>
		<?}?>
	

	<div class="gnb_area">
		<div class="gnb" >
			<div class="logo">
				<a href="<?php echo $_www_server; ?>/main.php"><img src="<?php echo $_www_server; ?>/images/<?=$_logo_img?>" alt="logo" ></a>
			</div>
			
			<div class="gnb_dep1">
				<ul>
					<?if($_ck_user_level=="MANAGER" || $_ck_user_level=="MANAGER_S"){	//매니저 접속
						//메인 대시보드 화면 없음
					}else{?>	
						<li class="<?php if($page_name == 'main') echo "active"; ?>"><a href="<?php echo $_www_server; ?>/main.php"><?=$_LANG_TEXT['m_home'][$lang_code]?></a></li>
					<?}?>
				
				<?if(in_array($_PAGE["USER"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["USER"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["USER"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_visitor'][$lang_code]?></a>
						<div class="sub" style="left:-45px">
							<div class="line"></div>
							<ul>
								<li><a href="<?php echo $_www_server; ?>/user/access_control.php"><?=$_LANG_TEXT['access_control'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/user/access_control_idc.php">IDC <?=$_LANG_TEXT['access_control'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/user/rental_details.php"><?=$_LANG_TEXT['rental_goods'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/user/parking_ticket_payment.php"><?=$_LANG_TEXT['parking_ticket_payment'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/user/external_training.php"><?=$_LANG_TEXT['External_training'][$lang_code]?></a></li>
							</ul>
						</div>
					</li>
				<?}?>
				
				<?if(in_array($_PAGE["RESULT"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["RESULT"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["RESULT"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_result'][$lang_code]?></a>
						<div class="sub" style="left:-33px">
							<div class="line"></div>
							<ul>
								<!--<li><a href="<?php echo $_www_server; ?>/result/check_result.php"><?=$_LANG_TEXT['m_result'][$lang_code]?></a></li>-->
								<li><a href="<?php echo $_www_server; ?>/result/result_list.php"><?=$_LANG_TEXT['m_result'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/result/file_in_apply_list.php"><?=$_LANG_TEXT['file_in_apply_list'][$lang_code]?></a></li>
							</ul>
						</div>
					</li>
				<?}?>
				
				<?	//조회통계
				if(in_array($_PAGE["STATISTICS"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["STATISTICS"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["STATISTICS"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_statistics'][$lang_code]?></a>
						<div class="sub" style="left:-33px">
							<div class="line"></div>
							<ul>
								<?if(in_array("R1000",$_ck_user_mauth)){?>
								<li><a href="<?php echo $_www_server; ?>/stat/file_import_history.php"><?=$_LANG_TEXT['file_inspection_details'][$lang_code]?></a></li>
								<?}?>
								<?if(in_array("U1000",$_ck_user_mauth)){?>
								<li><a href="<?php echo $_www_server; ?>/stat/rental_info_change_history.php"><?=trsLang('물품대여정보변경내역','rental_info_change_history');?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/user_agree_list.php"><?=trsLang('동의및서약서내역','user_agree_list');?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/idc_checkinout_list.php"><?=trsLang('IDC입퇴실내역','idccheckinoutdetails');?></a></li>
								<?}?>
								<?if($_ck_user_level == "SUPER"){?>
								<li><a href="<?php echo $_www_server; ?>/stat/login_log.php"><?=$_LANG_TEXT['m_manage_adminloginlog'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/work_log.php"><?=$_LANG_TEXT['work_log'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/app_update_log.php"><?=$_LANG_TEXT['app_update_log_text'][$lang_code]?></a></li>
								<li><a href="<?php echo $_www_server; ?>/stat/system_log.php"><?=$_LANG_TEXT['worklogtext'][$lang_code]?></a></li>
								 <?}?>
								<?if(in_array("U1000",$_ck_user_mauth)){?>
								<li  ><a href="<?php echo $_www_server; ?>/stat/k_visit_stat.php"><?=trsLang('출입현황','inoutstatus');?></a></li>
								<?}?>
								<?if(in_array("R1000",$_ck_user_mauth)){?>
								<li  ><a href="<?php echo $_www_server; ?>/stat/k_vcs_stat.php"><?=$_LANG_TEXT['m_statistics_result'][$lang_code]?></a></li>
								<?}?>
								<?if(in_array("U1000",$_ck_user_mauth)){?>			
								<li  ><a href="<?php echo $_www_server; ?>/stat/k_rental_stat.php"><?=$_LANG_TEXT['item_rental_status'][$lang_code]?></a></li>
								<?}?>
								<?if($_ck_user_level == "SUPER" ){?>
								<li ><a href="<?php echo $_www_server; ?>/stat/k_report.php"><?=$_LANG_TEXT['m_report'][$lang_code]?></a></li>
								<?}?>
							</ul>
						</div>
					</li>
				<?}?>


				<?if(in_array($_PAGE["MANAGE"]["MENU_CODE"],$_ck_user_mauth)){?>
					<li class="<?php if(is_array($_PAGE["MANAGE"]["PAGE"][$page_name])) echo "active"; ?>">
						<a href="<?php echo $_PAGE["MANAGE"]["MENU_LINK"]; ?>"><?=$_LANG_TEXT['m_manage'][$lang_code]?></a>
						<div class="sub" style="left:-33px">
							<div class="line"></div>
							<ul>
							<li><a href="<?php echo $_www_server; ?>/manage/tree_list.php"><?=$_LANG_TEXT['institution_settings'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/scan_center_list.php"><?=$_LANG_TEXT['inspection_site_settings'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/policy.php"><?=$_LANG_TEXT['m_manage_policy'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/admin_list.php"><?=$_LANG_TEXT['m_manage_admin.info'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/kabang_emp_list.php"><?=$_LANG_TEXT['staffinfo'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/access_ip_config.php"><?=$_LANG_TEXT['m_manage_accessip'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/code_list.php"><?=$_LANG_TEXT['m_manage_code'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; 
?>/manage/admin_conf/file_signature.php"><? echo trsLang('파일시그니처정보관리','mgtfilesignitureinfo')?></a></li>
							<li><a href="<?php echo $_www_server; 
?>/manage/app_update.php"><?=$_LANG_TEXT['m_manage_appupdate'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/agree_list.php"><?=$_LANG_TEXT['information_consent_manag'][$lang_code]?></a></li>
							<li><a href="<?php echo $_www_server; ?>/manage/query_editor.php"><?=$_LANG_TEXT['m_query_editor'][$lang_code]?></a></li>
							</ul>
						</div>
					</li>
				<?}?>

							
				</ul>
			</div>
		</div>
	</div>
	</div>
	<div class="navi_area">
		<div class="area" >
			<div id="page_navi" class="navi" >
				<img src="<?php echo $_www_server; ?>/images/home.jpg" style="margin:-2px 5px 0 0">
				<?php
				foreach($_PAGE as $cate => $arr){

					$page_arr = $arr["PAGE"];

					if(is_array($page_arr[$page_name])){
						list($step1,$step2,$step3,$step4) = $page_arr[$page_name];
						break;
					}
				}
				if($page_name == "main"){	
					echo $step1." > ".$step2." > <span class='organ'>".$step3."</span>";
				}else{
					echo "<span class='organ'>".$step1."</span> > ".$step2." > ".$step3;
				}
				?>
			</div>
<?php
	
	$visible_gate_pop = false;

	if($_ck_user_level=="SUPER"){

		$qry_params = array();
		$qry_label = QRY_COMMON_ORG_USE_ALL;
		$sql = query($qry_label,$qry_params);

		$result = sqlsrv_query($wvcs_dbcon, $sql);

		$visible_gate_pop = $result;
		
	}else{
		
		if($_ck_user_mng_org_auth){

			$qry_params = array("emp_seq"=> $_ck_user_seq);
			$qry_label = QRY_ADMIN_MNG_ORG;
			$sql = query($qry_label,$qry_params);

			//echo $sql;

			$result = sqlsrv_query($wvcs_dbcon, $sql);

			$visible_gate_pop = $result;
		}
	}

	if($visible_gate_pop){
?>
		<div class="gate">
			<?=$_LANG_TEXT['organselecttext'][$lang_code]?> <span class="gate_pop_btn"><img src="<?php echo $_www_server; ?>/images/bar.jpg"></span>
				<img name='arrow_menu_folder' id='arrow_menu_folder_show' src="<?php echo $_www_server; ?>/images/arrow_down.png" onclick="SetMenuFolder('menu_folder','show');"  style='display:none;' >
				<img name='arrow_menu_folder' id='arrow_menu_folder_hide' src="<?php echo $_www_server; ?>/images/arrow_up.png" onclick="SetMenuFolder('menu_folder','hide');">
				<span style="cursor: pointer;margin-left:30px;" onclick="window.open('<?=$_www_server?>/help/kr/index.html', 'help', 'height=800px, width=1000px, scrollbars=yes, resizable=yes');"><img src="<?=$_www_server?>/images/help.png"></span>
		</div>
		<div class="gate_pop" >
			<ul>
				<li><a href="<?=$_SERVER['PHP_SELF']?>?gate=all"><?=$_LANG_TEXT['alltext'][$lang_code]?></a></li>
			<?
				while($row=@sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC)){

					//$gate_param -- function.inc 참조
					
					$param_enc = ParamEnCoding("gate=".$row['org_id']."&".$gate_param);
			?>
				<li><a href="<?=$_SERVER['PHP_SELF']?>?enc=<?=$param_enc?>">#<?=$row['org_name']?></a></li>
			<?
				}	
			?>
			</ul>
		</div>
<?
	}//if($visible_gate_pop){
?>
		</div>
	</div>

</div>
<script type='text/javascript'>
	LoadMenuFolder('menu_folder');
</script>